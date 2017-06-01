<?php
class Frankhdesign_Shopbybrand_BrandController extends Mage_Core_Controller_Front_Action{
     

/*----------------------------------*/
    public function IndexAction() {
    
     $manufacturer_name = $this->getRequest()->getParam('manufacturer');

    


    Mage::log($manufacturer_name);
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
                 "link"  => Mage::getBaseUrl().'/shopbybrand/'
       ));
    $breadcrumbs->addCrumb("brand", array(
                "label" => $this->__(ucwords($manufacturer_name)),
                "title" => $this->__(ucwords($manufacturer_name)),
                
       ));


    $layout = $this->getLayout();
      
      $block = $layout->getBlock('shopbybrand_index');
      $block->setManufacturer($manufacturer_name); 
     // $block->setData('manufacturer',$manufacturer_name);


      $products = Mage::getModel('catalog/product')->getCollection();
      //$products->$products->addAttributeToSelect('*');
      $products->addAttributeToFilter('manufacturer',$manufacturer_name);

      $block->setProducts('products',$products);


      //Mage::log($products->getSelect());



      $this->renderLayout(); 


       //$pars = $this->getRequest()->getParams();
     



    }

   

     
}