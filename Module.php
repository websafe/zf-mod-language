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

namespace WebsafeZfModLanguage;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;
use WebsafeZfModLanguage\EventManager\DetectLanguagesListener;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ServiceManager\ServiceManager;
use WebsafeZfModLanguage\ServiceManager\LanguageServiceAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/**
 *
 */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface,
    ViewHelperProviderInterface, ServiceProviderInterface,
    ControllerProviderInterface
{
    /**
     *
     * @param \Zend\Mvc\MvcEvent $e
     * @link
     */
    public function onBootstrap(MvcEvent $e)
    {
        //
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        //
        $eventManager->attach(new DetectLanguagesListener());
    }
    /**
     * {@inheritDoc}
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'WebsafeZfModLanguageLogger' => 'Zend\Log\LoggerServiceFactory',
                'WebsafeZfModLanguageService'
                    => 'WebsafeZfModLanguage\ServiceManager'
                     . '\WebsafeZfModLanguageServiceFactory',
            ),
        );
    }
    /**
     * {@inheritDoc}
     */
    public function getControllerConfig()
    {
        return array(
            'invokables'   => array(
                'WebsafeZfModLanguageController'
                    => 'WebsafeZfModLanguage\Controller'
                     . '\WebsafeZfModLanguageController',
            ),
            'initializers' => array(
                function($instance, ServiceLocatorInterface $serviceLocator) {
                    $sm = $serviceLocator->getServiceLocator();
                    // LanguageServiceAwareInterface
                    if ($instance instanceof LanguageServiceAwareInterface) {
                        $service = $sm->get('WebsafeZfModLanguageService');
                        $instance->setLanguageService($service);
                    }
                    // LoggerAwareInterface
                    if ($instance instanceof LoggerAwareInterface) {
                        if ($sm->has('WebsafeZfModLanguageLogger')) {
                            $logger = $sm->get('WebsafeZfModLanguageLogger');
                        }
                        $instance->setLogger($logger);
                    }
                }
            ),
        );
    }
    /**
     * {@inheritDoc}
     */
    public function getViewHelperConfig()
    {
        return array(
            'invokables'   => array(
                'languageSelect'
                    => 'WebsafeZfModLanguage\View\Helper\LanguageSelect',
            ),
            'initializers' => array(
                function($instance, ServiceLocatorInterface $serviceLocator) {
                    $sm = $serviceLocator->getServiceLocator();
                    if ($instance instanceof LanguageServiceAwareInterface) {
                        $service = $sm->get('WebsafeZfModLanguageService');
                        $instance->setLanguageService($service);
                    }
                }
            ),
        );
    }
    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
