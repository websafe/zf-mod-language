WebsafeZfModLanguage
================================================================================

A ZF2 module which takes care of detecting **an optimal** and/or 
**the requested** locale for Your application's translator. The locale is 
computed/detected basing on values found in: Accept-Language header, cookie, 
session, query parameter and route parameter. Each detection method can be
disabled/enabled via configuration.

Provides:

 + The language service accessible via ServiceManager.
   See: [WebsafeZfModLanguageService] and [WebsafeZfModLanguageServiceFactory].

 + A Controller for handling client requests (switching locale/language).
   See: [WebsafeZfModLanguageController].
   
 + View Helpers for showing language selecting menus/dropdowns etc.



Using WebsafeZfModLanguage in Your application
--------------------------------------------------------------------------------

In project's root run:

~~~~ bash
vendor/bin/composer.phar require "websafe/zf-mod-language:*"
~~~~


In `config/application.config.php` add `WebsafeZfModLanguage`:

~~~~ php
    // ...
    'modules' => array(
        // ...
        'Application',
        'WebsafeZfModLanguage',
        // ...
    ),
~~~~


That's all. The module should work now - transparently. Just test it on the 
official [ZendSkeletonApplication] and try to modify browsers Accept-Language
headers.

But, there's already a basic view helper included, so after enabling the 
module in `config/application.config.php` You can try to add the following 
code somewhere in `layout.phtml`:

~~~~ php
<?php echo $this->languageSelect();?>
~~~~



Configuration
--------------------------------------------------------------------------------





How locale/language detection is handled.
--------------------------------------------------------------------------------

1. The module attaches the [DetectLanguagesListener] to the event manager.

1. The [DetectLanguagesListener] is now waiting for a dispatch event 
    (`MvcEvent::EVENT_DISPATCH`)...

  1. When the dispatch event occurs, [DetectLanguagesListener] forwards the
     event to the language service [WebsafeZfModLanguageService].

    1. The language service collects data for locale detection:

       + Retrieve languages requested by the client/browser via 
         [Accept-Language] headers. Add all results ordered by priority
         to the `detectedLanguages` array.

       + Retrieve locale stored in cookie (name of cookie is configurable).
         Prepend the retrieved locale to the front of `detectedLanguages`.

       + Retrieve locale stored in session (container name and session 
         variable name are configurable). 
         Prepend the retrieved locale to the front of `detectedLanguages`.

       + Retrieve locale provided in query parameter (parameter name  is
         configurable). 
         Prepend the retrieved locale to the front of `detectedLanguages`.

       + Retrieve locale provided in route parameter (parameter name is
         configurable). 
         Prepend the retrieved locale to the front of `detectedLanguages`.

    1. The language service iterates through `detectedLanguages` and stops
       iterating after the first detected locale that exists in 
       [supported_locales]. The matched locale is now accessible via 
       `$sm->get('WebsafeZfModLanguageService')->getCurrentLocale()`.

    1. The language service applies the current locale to the `translator`
       service.




[DetectLanguagesListener]: src/WebsafeZfModLanguage/EventManager/DetectLanguagesListener.php
[WebsafeZfModLanguageService]: src/WebsafeZfModLanguage/ServiceManager/WebsafeZfModLanguageService.php
[WebsafeZfModLanguageServiceFactory]: src/WebsafeZfModLanguage/ServiceManager/WebsafeZfModLanguageServiceFactory.php
[WebsafeZfModLanguageController]: src/WebsafeZfModLanguage/Controller/WebsafeZfModLanguageController.php
[ZendSkeletonApplication]: https://github.com/zendframework/ZendSkeletonApplication
