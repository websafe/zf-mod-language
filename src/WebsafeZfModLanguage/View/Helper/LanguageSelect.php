<?php
/**
 * WebsafeZfModLanguage (http://github.com/websafe/zf-mod-language)
 *
 * PHP version >=5.4
 *
 * @author    Thomas Szteliga <ts@websafe.pl>
 * @copyright 2013-2014 WEBSAFE.PL. (https://websafe.pl/)
 * @license   http://websafe.pl/license/bsd-3-clause BSD-3-Clause
 * @link      http://github.com/websafe/zf-mod-language GitHub repository
 */

namespace WebsafeZfModLanguage\View\Helper;

use Zend\View\Helper\AbstractHelper;
use WebsafeZfModLanguage\ServiceManager\LanguageServiceAwareInterface;
use WebsafeZfModLanguage\ServiceManager\LanguageServiceAwareTrait;
use Locale;

/**
 * LanguageSelect view helper.
 */
class LanguageSelect extends AbstractHelper
    implements LanguageServiceAwareInterface
{

    use LanguageServiceAwareTrait;
    /**
     * Retrieve HTML Select based on data provided by the language service.
     *
     * @return string
     */
    public function __invoke()
    {
        $languageService  = $this->getLanguageService();
        $supportedLocales = $languageService->getSupportedLocales();
        $currentLocale    = $languageService->getCurrentLocale();
        //$result           = print_r($supportedLocales, true);
        $o                = '';
        $o .= '<form';
        $o .= ' method="post"';
        $o .= ' role="form"';
        $o .= ' action="';
        $o .= $this->view->url('websafe-language/switch/client-locale');
        $o .= '"';
        $o .= '>';
        $o .= '<div class="form-group">';
        $o .= '<select';
        //$o .= ' class="form-control"';
        $o .= ' name="language"';
        $o .= ' onchange="this.form.submit();"';
        $o .= '>';
        foreach ($supportedLocales as $locale) {
            $o .= '<option';
            $o .= ' value="';
            $o .= $locale;
            $o .= '"';
            if ($currentLocale == $locale) {
                $o .= ' selected="selected"';
            }
            $o .= '>';
            $o .= Locale::getDisplayLanguage($locale, $locale);
            $o .= ' - ';
            $o .= Locale::getDisplayRegion($locale, $locale);
            if ('en_US' != $locale) {
                $o .= ' (';
                $o .= Locale::getDisplayLanguage($locale, 'en_US');
                $o .= ' - ';
                $o .= Locale::getDisplayRegion($locale, 'en_US');
                $o .= ' )';
            }
            $o .= '</option>';
        }
        $o .= '</select>';
        $o .= '</div>';
        $o .= '</form>';

        return $o;
    }
}
