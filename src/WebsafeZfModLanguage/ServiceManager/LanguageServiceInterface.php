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

namespace WebsafeZfModLanguage\ServiceManager;

/**
 * Language service interface
 */
interface LanguageServiceInterface
{
    /**
     *
     */
    public function switchClientLocale($locale);
}
