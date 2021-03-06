<?php
/**
 * WebsafeZfModLanguage (http://github.com/websafe/zf-mod-language)
 *
 * PHP version >=5.4
 *
 * @author    Thomas Szteliga <ts@websafe.pl>
 * @copyright 2013-2014 WEBSAFE.PL Thomas Szteliga (https://websafe.pl/)
 * @license   http://websafe.pl/license/mit MIT
 * @link      http://github.com/websafe/zf-mod-language GitHub repository
 */

namespace WebsafeZfModLanguage\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use WebsafeZfModLanguage\ServiceManager\LanguageServiceAwareInterface;
use WebsafeZfModLanguage\ServiceManager\LanguageServiceAwareTrait;

/**
 * The main controller of the WebsafeZfModLanguage module.
 */
class WebsafeZfModLanguageController extends AbstractActionController implements
    LanguageServiceAwareInterface
{

    use LanguageServiceAwareTrait;
    /**
     * Switch Client Locale action.
     */
    public function switchClientLocaleAction()
    {
        //
        $languageService = $this->getLanguageService();
        //$routeName = $languageService->getRouteMatch()->getMatchedRouteName();
        //$routeParams = $languageService->getRouteMatch()->getParams();
        $routeName = 'home';
        //
        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            //
            if (array_key_exists('language', $postData)) {
                $languageService->switchClientLocale($postData['language']);
            }
            if (array_key_exists('route_name', $postData)) {
                $routeName = $postData['route_name'];
            }
            if (array_key_exists('route_params', $postData)) {
                $routeParamsSerialized = $postData['route_params'];
                $routeParams = unserialize($routeParamsSerialized);
            }
        }
        // @fixme: make redirect configurable
        $this->redirect()->toRoute($routeName, $routeParams);
    }
}
