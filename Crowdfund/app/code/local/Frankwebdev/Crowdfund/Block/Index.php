<?php   
class Frankwebdev_Crowdfund_Block_Index extends Mage_Core_Block_Template{   

	public function  __construct(){
     parent::__construct();
     $this->setTemplate('crowdfund/index.phtml');
     // like $this->setTemplate('category/brands.phtml');
     $myCollection = Mage::getModel('crowdfund/crowdfundmodel')->getCollection();
     // like $myCollection = Mage::getModel('catalog/product')->getCollection();
     $this->setMyCollection($myCollection);
 	}

	public function  getPagerHtml(){
	     return $this->getChildHtml('pager');
	}

	public function  _prepareLayout(){
	    parent::_prepareLayout();
	    $pager = $this->getLayout()
	          ->createBlock('page/html_pager', 'projects.collection')
	          ->setCollection($this->getMyCollection());
	   $this->setChild('pager', $pager);
	   $this->getMyCollection()->load();
	   return $this;
	}



}