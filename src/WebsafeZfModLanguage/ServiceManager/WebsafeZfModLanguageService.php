<?php
/**
 * WebsafeZfModLanguage (http://github.com/websafe/zf-mod-language)
 *
 * PHP version >=5.4
 *
 * @author    Thomas Szteliga <ts@websafe.pl>
 * @copyright 2013-2014 WEBSAFE.PL. (https://websafe.pl/)
 * @license   http://websafe.pl/license/mit MIT
 * @link      http://github.com/websafe/zf-mod-language GitHub repository
 */

namespace WebsafeZfModLanguage\ServiceManager;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Log\LoggerAwareInterface;
use WebsafeZfModLanguage\ServiceManager\LanguageServiceInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Log\LoggerAwareTrait;
use Zend\Mvc\MvcEvent;
use Zend\Http\Header\Cookie;
use Zend\Session\Container;
use Zend\Http\Header\SetCookie;
use Locale;

class WebsafeZfModLanguageService implements
    ServiceLocatorAwareInterface,
    LoggerAwareInterface,
    LanguageServiceInterface
{

    use ServiceLocatorAwareTrait;
    use LoggerAwareTrait;

    /**
     *
     * @var array
     */
    protected $cfg               = array();
    /**
     *
     * @var array
     */
    protected $detectedLanguages = array();
    /**
     *
     * @var string
     */
    protected $locale;
    // -------------------------------------------------------------------------
    /**
     *
     * @param  \Zend\Mvc\MvcEvent          $e
     * @return WebsafeZfModLanguageService
     */
    protected function detectLanguagesInHeader(MvcEvent $e)
    {
        $this->logger->debug(__FUNCTION__);
        //
        $headers = $e->getApplication()->getRequest()->getHeaders();
        // Trying to detect languages supported by client via Accept-Language:
        if ($headers->has('Accept-Language')) {
            // Accept-Language languages sorted by priority
            $languages = $headers->get('Accept-Language')->getPrioritized();
            //
            foreach ($languages as $language) {
                // If language does NOT contain a dash ('-'):
                if (false === stripos($language->getLanguage(), '-')) {
                    // "repair" the language without a dash:
                    // 'pl' becomes 'pl-pl', 'de' becomes 'de-de',...
                    $this->detectedLanguages[] = $language->getLanguage()
                        . '-' . $language->getLanguage();
                } else {
                    // otherwise, use the incoming language as is:
                    $this->detectedLanguages[] = $language->getLanguage();
                }
            }
        }
        $this->logger->debug($this->detectedLanguages);
        //
        return $this;
    }
    /**
     *
     * @param  \Zend\Mvc\MvcEvent          $e
     * @return WebsafeZfModLanguageService
     */
    protected function detectLanguageInCookie(MvcEvent $e)
    {
        $this->logger->debug(__FUNCTION__);
        $cookie = $e->getApplication()->getRequest()->getCookie();
        //var_dump($cookie);
        if ($cookie instanceof Cookie) {
            //
            if ($cookie->offsetExists($this->cfg['cookie_name'])) {
                // prepend the locale detected in the cookie to te front
                // of $detectedLanguages:
                array_unshift(
                    $this->detectedLanguages,
                    $cookie->offsetGet($this->cfg['cookie_name'])
                );
            }
        }
        $this->logger->debug($this->detectedLanguages);
        //
        return $this;
    }
    /**
     *
     * @param  \Zend\Mvc\MvcEvent          $e
     * @return WebsafeZfModLanguageService
     */
    protected function detectLanguageInSession(MvcEvent $e)
    {
        $this->logger->debug(__FUNCTION__);
        $session = new Container($this->cfg['session_container']);
        if ($session->offsetExists($this->cfg['session_variable'])) {
            // prepend the locale detected in the session to te front
            // of $detectedLanguages:
            array_unshift(
                $this->detectedLanguages,
                $session->offsetGet($this->cfg['session_variable'])
            );
        }
        $this->logger->debug($this->detectedLanguages);
        //
        return $this;
    }
    /**
     *
     * @param  \Zend\Mvc\MvcEvent          $e
     * @return WebsafeZfModLanguageService
     */
    protected function detectLanguageInQuery(MvcEvent $e)
    {
        $this->logger->debug(__FUNCTION__);
        $queryParams = $e->getRequest()->getQuery();
        //
        if (array_key_exists($this->cfg['query_param'], $queryParams)) {
            array_unshift(
                $this->detectedLanguages,
                $queryParams[$this->cfg['query_param']]
            );
        }
        $this->logger->debug($this->detectedLanguages);
        //
        return $this;
    }
    /**
     *
     * @param  \Zend\Mvc\MvcEvent          $e
     * @return WebsafeZfModLanguageService
     */
    protected function detectLanguageInRoute(MvcEvent $e)
    {
        $this->logger->debug(__FUNCTION__);
        $this->logger->debug($this->detectedLanguages);
        //
        return $this;
    }
    /**
     *
     * @param  \Zend\Mvc\MvcEvent          $e
     * @return WebsafeZfModLanguageService
     */
    protected function detectLanguages(MvcEvent $e)
    {
        $this->logger->debug(__FUNCTION__);
        //  Detecting languages/locales provided via Accept-Language:
        if (true === $this->cfg['detect_in_header']) {
            $this->detectLanguagesInHeader($e);
        }
        // Next step is detecting the language from the language cookie
        // and inserting the language at the beginning of $detectedLanguages
        // Trying to detect language via Cookie:
        if (true === $this->cfg['detect_in_cookie']) {
            $this->detectLanguageInCookie($e);
        }
        //
        if (true === $this->cfg['detect_in_session']) {
            $this->detectLanguageInSession($e);
        }
        //
        if (true === $this->cfg['detect_in_query']) {
            $this->detectLanguageInQuery($e);
        }
        //
        if (true === $this->cfg['detect_in_route']) {
            $this->detectLanguageInRoute($e);
        }
        //
        return $this;
    }
    // -------------------------------------------------------------------------
    protected function detectMatchingLocale()
    {
        $this->logger->debug(__FUNCTION__);
        // Now iterate through all `detectedLanguages` and stop at the first
        // that matches an entry on the `supported_locales` list:
        foreach ($this->detectedLanguages as $language) {
            // $matchedLocale will contain an empty string if current
            // $language did not match any locale in $supportedLocales.
            $matchedLocale = \Locale::lookup(
                $this->cfg['supported_locales'],
                $language
            );
            // Found match:
            if (!empty($matchedLocale)) {
                $this->locale = $matchedLocale;
                break;
            }
        }
        // If no matching locale was found at all:
        if (empty($matchedLocale)) {
            // falling back to the configured default locale
            $this->locale = $this->cfg['default_locale'];
        }
        $this->logger->debug($this->locale);
        //
        return $this;
    }
    protected function applyMatchingLocale(MvcEvent $e)
    {
        $this->logger->debug(__FUNCTION__);
        //
        $sm         = $e->getApplication()->getServiceManager();
        $translator = $sm->get('translator');
        // Apply the matching locale
        $translator
            ->setLocale($this->locale)
            ->setFallbackLocale('en_US');
        //
        return $this;
    }
    // -------------------------------------------------------------------------
    public function onDispatch(MvcEvent $e)
    {
        $this->logger->debug(__FUNCTION__);
        //
        $sm        = $e->getApplication()->getServiceManager();
        $appConfig = $sm->get('Config');
        if (array_key_exists('WebsafeZfModLanguage', $appConfig)) {
            $this->cfg = array_merge($appConfig['WebsafeZfModLanguage']);
        }
        //
        $this
            ->detectLanguages($e)
            ->detectMatchingLocale()
            ->applyMatchingLocale($e);
    }
    // -------------------------------------------------------------------------
    /**
     * @return array
     */
    public function getSupportedLocales()
    {
        $this->logger->debug(__FUNCTION__);
        $this->logger->debug($this->cfg['supported_locales']);

        return $this->cfg['supported_locales'];
    }
    /**
     * @return array
     */
    public function getCurrentLocale()
    {
        $this->logger->debug(__FUNCTION__);
        $this->logger->debug($this->locale);

        return $this->locale;
    }
    /**
     * @return array
     */
    public function setCurrentLocale($locale)
    {
        $this->logger->debug(__FUNCTION__);
        $this->locale = $locale;

        return $this;
    }
    public function switchClientLocale($locale)
    {
        $this->logger->debug(__FUNCTION__);
        $sl       = $this->getServiceLocator();
        $response = $sl->get('Response');
        $headers  = $response->getHeaders();
        // @fixme: make cookie configurable
        $cookie   = new SetCookie(
            'language',
            $locale,
            time() + 365 * 60 * 60 * 24
        );
        $headers->addHeader($cookie);
    }
    public function getCurrentLanguage()
    {
        $currentLocale = $this->getCurrentLocale();
        $parsedLocale  = Locale::parseLocale($currentLocale);
        $language      = null;
        if (array_key_exists('language', $parsedLocale)) {
            $language = $parsedLocale['language'];
        }

        return $language;
    }
}
