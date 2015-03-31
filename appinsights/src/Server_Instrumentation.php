<?php

namespace ApplicationInsights\Concrete;

defined('C5_EXECUTE') or die(_("Access Denied."));

class Server_Instrumentation
{
    private $_telemetryClient;
    private $_title;
    private $_startTime;
	
    public function __construct($_instrumentationKey,$_title)
    {
        $this->_startTime = $this->getMicrotime();
        $this->_telemetryClient = new \ApplicationInsights\Telemetry_Client();
        $this->_telemetryClient->getContext()->setInstrumentationKey($_instrumentationKey);
		$this->_title = $_title;		
        set_exception_handler(array($this, 'exceptionHandler'));
    }
    
    function endRequest()
    {
		$url = $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		$requestName = $this->getRequestName();
		$duration = ($this->getMicrotime() - $this->_startTime) * 1000;
		$this->_telemetryClient->trackRequest($requestName, $url, $this->_startTime, $duration);
		// Flush all telemetry items
		$this->_telemetryClient->flush(); 
    }

    function getRequestName()
    {
		return $this->_title;
    }

    function getMicrotime()
    {
        list($useg, $seg) = explode(" ", microtime());
        return ((float)$useg + (float)$seg);
    }
    
    
    function exceptionHandler(\Exception $exception)
    {
        if ($exception != NULL)
        {
            $this->_telemetryClient->trackException($exception);
            $this->_telemetryClient->flush();
        }
    }
}


