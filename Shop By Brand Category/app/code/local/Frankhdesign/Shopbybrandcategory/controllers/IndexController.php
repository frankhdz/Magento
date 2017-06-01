<?php
class Frankhdesign_Shopbybrandcategory_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
    



	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Shop by Brand"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

    $breadcrumbs->addCrumb("brand", array(
                "label" => $this->__($this->getRequest()->getParam('category')),
                "title" => $this->__("Shop Category by Brand")
       ));


      $layout = $this->getLayout();
      
      $block = $layout->getBlock('shopbybrandcategory_index');
      $block->setCategoryname($this->getRequest()->getParam('category')); 

      Mage::log('GET CATNAMMME : '.$block->getCategoryname());

      $this->renderLayout(); 

	  
    }
    public function ListAction() {
    

    //bread crumb links


    $this->loadLayout();   
    $this->getLayout()->getBlock("head")->setTitle($this->__("Shop by Brand"));
          $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
       ));
   

    $breadcrumbs->addCrumb("brand", array(
                "label" => $this->__($this->getRequest()->getParam('category')),
                "title" => $this->__("Shop Category by Brand")
       ));


      $layout = $this->getLayout();
      
      $block = $layout->getBlock('shopbybrandcategory_index');
      $block->setCategoryname($this->getRequest()->getParam('category')); 

      Mage::log('GET CATNAMMME : '.$block->getCategoryname());

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
                "label" => $this->__("Shop Category by Brand"),
                "title" => $this->__("Shop by Brand"),
                 "link"  => Mage::getBaseUrl().'shopbybrand'
       ));
      $breadcrumbs->addCrumb("shop by brand", array(
                "label" => $this->__("Shop Category by Brand X"),
                "title" => $this->__("Shop Category by Brand Y")
       ));

      $this->renderLayout(); 
    
    }
}