<?php
class Frankwebdev_Crowdfund_IndexController extends Mage_Core_Controller_Front_Action{
    public function indexAction() {
    
    $crowdfundconfig =  Mage::getStoreConfig('crowdfundsettings');
    if($crowdfundconfig['config']['enable_module']){//enable disable module front end
	  
    $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Projects"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("crowdfund", array(
                "label" => $this->__("Projects"),
                "title" => $this->__("Projects")
		   ));

      $this->renderLayout(); 
	  
    }

    }

    
}