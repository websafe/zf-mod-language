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

use WebsafeZfModLanguage\ServiceManager\LanguageServiceInterface;

trait LanguageServiceAwareTrait
{
    /**
     * @var LanguageServiceInterface
     */
    protected $languageService = null;
    /**
     * Set language service
     *
     * @param  LanguageServiceInterface $languageService
     * @return mixed
     */
    public function setLanguageService(
        LanguageServiceInterface $languageService
    ) {
        $this->languageService = $languageService;

        return $this;
    }
    /**
     * Get language service
     *
     * @return LanguageServiceInterface
     */
    public function getLanguageService()
    {
        return $this->languageService;
    }
}
