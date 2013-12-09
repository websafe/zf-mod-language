WebsafeZfModLanguage
================================================================================

A ZF2 module which takes care of detecting **a default** or **the requested** 
locale for Your application's translator.

The locale is computed/detected basing on:

 + Accept-Language,
 + cookie,
 + session,
 + query parameter,
 + route parameter.

Each detection method can be disabled/enabled via configuration.

Utilizes an Event Listener to hook the detection process into 
MvcEvent::EVENT_DISPATCH.

Comes with a `Controller` for handling locale switching by client.


Provides:

 + The language service [WebsafeZfModLanguageService] accessible 
   via ServiceManager.

 + A Controller for handling client requests (switching locale/language).
   
 + View Helpers for showing language selecting menus/dropdowns etc.


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

