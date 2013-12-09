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

namespace WebsafeZfModLanguage\EventManager;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\ListenerAggregateTrait;

/**
 * Listener hooks the locale detection process managed by the language service
 *  into the dispatch Event.
 */
class DetectLanguagesListener implements ListenerAggregateInterface
{

    use ListenerAggregateTrait;
    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH,
            array($this, 'onDispatch'),
            100
        );
    }
    /**
     * Callback for dispatch event. Forwards event to the language service.
     *
     * @param  \Zend\Mvc\MvcEvent $e
     * @return void
     */
    public function onDispatch(MvcEvent $e)
    {
        $sm      = $e->getApplication()->getServiceManager();
        $service = $sm->get('WebsafeZfModLanguageService');
        $service->onDispatch($e);
    }
}
