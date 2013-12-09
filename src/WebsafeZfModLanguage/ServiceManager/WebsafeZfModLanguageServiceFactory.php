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

namespace WebsafeZfModLanguage\ServiceManager;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use WebsafeZfModLanguage\ServiceManager\WebsafeZfModLanguageService;

class WebsafeZfModLanguageServiceFactory implements FactoryInterface
{
    /**
     *
     * @param  ServiceLocatorInterface     $serviceLocator
     * @return WebsafeZfModLanguageService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new WebsafeZfModLanguageService();
        $service->setServiceLocator($serviceLocator);

        return $service;
    }
}
