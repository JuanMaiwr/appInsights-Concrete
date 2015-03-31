<?php

namespace ApplicationInsights\Concrete;

defined('C5_EXECUTE') or die(_("Access Denied."));

/**
 * Does client-side instrumentation using the Javascript SDK for Application Insights
 * @copyright   Copyright 2015. All rights re-served.
 * @license     No information.
 */
class Client_Instrumentation
{
   /**
	* Add prefix 
	*
	* @param       string  $_instrumentationkey  
    *
	* @param       string  $_title  
    */
   function addPrefix($_instrumentationkey,$_title) {
       $rawSnippet = '<script type="text/javascript">
            var appInsights=window.appInsights||function(config){
                function s(config){t[config]=function(){var i=arguments;t.queue.push(function(){t[config].apply(t,i)})}}var t={config:config},r=document,f=window,e="script",o=r.createElement(e),i,u;for(o.src=config.url||"//az416426.vo.msecnd.net/scripts/a/ai.0.js",r.getElementsByTagName(e)[0].parentNode.appendChild(o),t.cookie=r.cookie,t.queue=[],i=["Event","Exception","Metric","PageView","Trace"];i.length;)s("track"+i.pop());return config.disableExceptionTracking||(i="onerror",s("_"+i),u=f[i],f[i]=function(config,r,f,e,o){var s=u&&u(config,r,f,e,o);return s!==!0&&t["_"+i](config,r,f,e,o),s}),t
            }({
                instrumentationKey:"INSTRUMENTATION_KEY"
            });
    
            window.appInsights=appInsights;
            appInsights.trackPageView("PAGE_NAME", PAGE_URL);
        </script>';
       
        $patterns = array();
        $replacements = array();
       
        $patterns[0] = '/INSTRUMENTATION_KEY/';
        $patterns[1] = '/PAGE_NAME/';
        $patterns[2] = '/PAGE_URL/';
        // Sets Instrumentation Key
        $replacements[0] = $_instrumentationkey;
        
        // Sets the page title
        $replacements[1] = $_title;
       
        // Validate if displaying home page

        if($_title == 'Home')
        {
            $replacements[2] = 'window.location.origin';
        }
        else
        {
            $replacements[2] = 'window.location.origin + "/'.rawurlencode($_title).'"';
        }
        
        echo preg_replace($patterns, $replacements, $rawSnippet);
    }
}
