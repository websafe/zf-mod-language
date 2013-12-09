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

namespace WebsafeZfModLanguage;

$settings = array(
    /*
     * List of locales supported by the application.
     * 
     * The actual list is based on language files distributed with the
     * ZendSkeletonApplication.
     *
     * Default: array of 19 locales
     */
    'supported_locales' => array(
        'ar_JO',
        'ar_SY',
        'cs_CZ',
        'de_DE',
        'en_US',
        'es_ES',
        'fr_CA',
        'fr_FR',
        'it_IT',
        'ja_JP',
        'nb_NO',
        'nl_NL',
        'pl_PL',
        'pt_BR',
        'ru_RU',
        'sl_SI',
        'tr_TR',
        'zh_CN',
        'zh_TW',
    ),
    // -------------------------------------------------------------------------
    /*
     * Should the language/locale be detected in Accept-Language headers?
     *
     * Default: true
     */
    'detect_in_header'  => true,
    // -------------------------------------------------------------------------
    /*
     * Should the language/locale be detected in cookie?
     *
     * Default: true
     */
    'detect_in_cookie'  => true,
    /*
     * Name of the cookie, that may contain the current locale.
     *
     * Default: language
     */
    'cookie_name'       => 'language',
    // -------------------------------------------------------------------------
    /*
     * Should the language/locale be detected in session?
     *
     * Default: true
     */
    'detect_in_session' => true,
    /*
     * Name of the session container, that may contain the session variable,
     * that that may contain the current locale ;-)
     *
     * Default: language
     */
    'session_container' => 'language',
    /*
     * Name of the session variable, that may contain the current locale.
     *
     * Default: language
     */
    'session_variable'  => 'language',
    // -------------------------------------------------------------------------
    /*
     * Should the language/locale be detected in a query param?
     *
     * Default: true
     */
    'detect_in_query'   => true,
    /*
     * Name of the query param, that may contain the current locale.
     *
     * Default: language
     */
    'query_param'       => 'language',
    // -------------------------------------------------------------------------
    /*
     * Should the language/locale be detected in a route param?
     *
     * Default: true
     */
    'detect_in_route'   => true,
    /*
     * Name of the route param, that may contain the current locale.
     *
     * Default: language
     */
    'route_param'       => 'language',
    // -------------------------------------------------------------------------
    /*
     * The default locale - used when no locale matching the list of
     * supported_locales was found/detected.
     *
     * Default: en_US
     */
    'default_locale'    => 'en_US',
);

return array(
    //
    __NAMESPACE__     => $settings,
    // Configuration for the logger service, which is instantiated
    // by `Zend\Log\LoggerServiceFactory`:
    'log'             => array(
        'writers' => array(
            array(
                'name'     => 'Zend\Log\Writer\Null',
                'priority' => null,
                'options'  => array(),
            ),
        ),
    ),
    'router'       => array(
        'routes' => array(
            'websafe-language' => array(
                'type'          => 'Literal',
                'options'       => array(
                    'route' => '/language',
                ),
                'may_terminate' => false,
                'child_routes'  => array(
                    'switch' => array(
                        'type'          => 'Literal',
                        'options'       => array(
                            'route' => '/switch',
                        ),
                        'may_terminate' => false,
                        'child_routes'  => array(
                            'client-locale' => array(
                                'type'    => 'Literal',
                                'options' => array(
                                    'route'    => '/client-locale',
                                    'defaults' => array(
                                        'controller'
                                            => 'WebsafeZfModLanguageController',
                                        'action'     => 'switchClientLocale',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
