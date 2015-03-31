<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

require_once 'vendor/autoload.php';
require_once 'vendor/microsoft/application-insights/vendor/autoload.php';

class AppInsightsPackage extends Package{
    
    protected $pkgHandle = 'appinsights';
	protected $appVersionRequired = '5.6.0';
	protected $pkgVersion = '1.0';
	private $_instrumentationKey;
	public function getPackageDescription() {
		return t("Integrates a Concrete5 site with Microsoft Application Insights");
	}
	
	public function getPackageName() {
		return t("Application Insights");
	}
    
	public function on_start() {
		Events::extend('on_before_render', 'AppInsightsPackage', 'on_before_render', 'packages/'.$this->pkgHandle.'/Controller.php');
	}
	public function on_before_render() {	
		$page = Page::getCurrentPage();
		if (!$page->isEditMode())
		{
			$db = Loader::db();
			// Get the Instrumentation Key From the DataBase
			if (($_instrumentationKey = $db->getOne('select IK from btAppInsights where bID = ?', array(1)))!=null)
			{
				// Enables client-side instrumentation
				$clientInstrumentation = new ApplicationInsights\Concrete\Client_Instrumentation();
				$clientInstrumentation->addPrefix($_instrumentationKey,$page->getCollectionName());
				
				// Enables server-side instrumentation
				$serverInstrumentation = new ApplicationInsights\Concrete\Server_Instrumentation($_instrumentationKey,$page->getCollectionName());
				register_shutdown_function(array($serverInstrumentation, 'endRequest'));
			}
		}
	}
	
	public function install() {
		$pkg = parent::install();
		$pkg = Package::getByHandle($this->pkgHandle);
		$co = new Config();
		$pkg = Package::getByHandle($this->pkgHandle);
		
		$co->setPackageObject($pkg);
			$sp = SinglePage::add('/dashboard/'.$this->pkgHandle.'/', $pkg);
			$sp->update(array('cName'=>t("Application Insights"), 'cDescription'=>t("Set up the Instrumentation Key.")));
			
			$sp = SinglePage::add('/dashboard/'.$this->pkgHandle.'/ik/', $pkg);
			$sp->update(array('cName'=>t("Instrumentation Key")));

	}
	
	public function uninstall() {
			parent::uninstall();
		$db = Loader::db();
		//remove Instrumentation Key if exist
		$db->Execute('DROP TABLE IF EXISTS btAppInsights');
	}
}
