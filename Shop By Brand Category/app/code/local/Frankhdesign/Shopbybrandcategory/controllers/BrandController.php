<?php
class Frankhdesign_Shopbybrandcategory_BrandController extends Mage_Core_Controller_Front_Action{
     

/*----------------------------------*/
    public function IndexAction() {

      Mage::log('BRAND 2 CONTROLLER');
    
     $manufacturer_name = $this->getRequest()->getParam('manufacturer');

     $Category=Mage::getModel('catalog/category')->load($this->getRequest()->getParam('category'));
     $categoryName = $Category->getName();
    


    Mage::log($manufacturer_name);
    $this->loadLayout();
    $this->getLayout()->getBlock("head")->setTitle($this->__("Shop by Brand"));
    $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
    $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
       ));
     $breadcrumbs->addCrumb("Category", array(
                "label" => $categoryName,
                "title" => $this->__("Home Page"),
                "link"  => "/shopbybrandcategory/index/list/category/".$categoryName
       ));

    $breadcrumbs->addCrumb("brand", array(
                "label" => $this->__(ucwords($manufacturer_name)),
                "title" => $this->__(ucwords($manufacturer_name)),
                
       ));


    $layout = $this->getLayout();
      
      $block = $layout->getBlock('shopbybrandcategory_index');
      $block->setManufacturer($manufacturer_name); 
       $block->setCategoryname($categoryName); 
      //$block->assign(array('manufacturer'=>$manufacturer_name));
     // $block->setData('manufacturer',$manufacturer_name);

     // Mage::register('manufacturer', $manufacturer_name);


      $products = Mage::getModel('catalog/product')->getCollection();
      //$products->$products->addAttributeToSelect('*');
      $products->addAttributeToFilter('manufacturer',$manufacturer_name);

      //Mage::log($products);

     // $block->setProducts('products',$products);


      //Mage::log($products->getSelect());



      $this->renderLayout(); 


       //$pars = $this->getRequest()->getParams();
     



    }

   

     
}