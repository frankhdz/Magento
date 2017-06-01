<?php   
class Frankhdesign_Shopbybrandcategory_Block_Brand extends Mage_Catalog_Block_Product_Abstract{   

	public function __construct()
    {
        Mage::log('BRAND CONSTRUCT START INIT BLOCK');
        parent::__construct();
        
      $collection = Mage::getResourceModel('catalog/product_collection')
      ->addAttributeToSelect('*')
      ->addAttributeToFilter('visibility', array('neq' => 1))
      ->addAttributeToFilter('status', 1)
      ->addAttributeToFilter('manufacturer_value', array('like' => $this->getRequest()->getParam('manufacturer') ))
      ->addAttributeToFilter('category_id', array('like' => $this->getRequest()->getParam('category') ))
/*      ->addAttributeToFilter('category_id', array('like' => $this->getRequest()->getParam('categorytwo') ))
      ->addAttributeToFilter('category_id', array('like' => $this->getRequest()->getParam('categorythree') ))
      ->addAttributeToFilter('category_id', array('like' => $this->getRequest()->getParam('categoryfour') ))*/
      ->setCurPage(1)
      ->setPageSize(20)
      ->load();
       




       // $collection = Mage::getModel('example/collection')->getCollection();
        $this->setCollection($collection);
    }
	
	protected function _prepareLayout()
    {
        Mage::log('BRAND PREPARE LAYOUT CALL');
        parent::_prepareLayout();
         
        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setAvailableLimit(array(5=>5,10=>10,20=>20,'all'=>'all'));
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }

	public function getPagerHtml()
    {
        Mage::log('BRAND PAGER BLOCK CALL');
        return $this->getChildHtml('pager');
    }

     public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }


}