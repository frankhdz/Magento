<?php
class Frankhdesign_Shopbybrand_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Shop by Brand"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("shop by brand", array(
                "label" => $this->__("Shop by Brand"),
                "title" => $this->__("Shop by Brand")
		   ));

      $this->renderLayout(); 
	  
    }

     public function BrandAction() {
      
    $this->loadLayout();   
    $this->getLayout()->getBlock("head")->setTitle($this->__("Shop by Brand"));
          $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
       ));

      $breadcrumbs->addCrumb("shop by brand", array(
                "label" => $this->__("Shop by Brand"),
                "title" => $this->__("Shop by Brand"),
                 "link"  => Mage::getBaseUrl().'shopbybrand'
       ));
      $breadcrumbs->addCrumb("shop by brand", array(
                "label" => $this->__("Shop by Brand"),
                "title" => $this->__("Shop by Brand")
       ));

      $this->renderLayout(); 
    
    }
}