<?php   
class Frankhdesign_Shopbybrand_Block_Index extends Mage_Catalog_Block_Product_Abstract{   

	public function __construct()
    {
        Mage::log('INDEX BRAND CONSTRUCT BLOCK');
        parent::__construct();
        
      if($this->getRequest()->getParam('manufacturer') ==""){

        $collection = Mage::getResourceModel('catalog/product_collection')
        ->addAttributeToSelect('*')
        ->addAttributeToFilter('visibility', array('neq' => 1))
        ->addAttributeToFilter('status', 1)
        //->addAttributeToFilter('manufacturer_value', array('eq' => $this->getRequest()->getParam('manufacturer') ))
        ->setCurPage($this->getRequest()->getParam('p'))->setPageSize($this->getRequest()->getParam('limit'))
        ->load()
        ;


      }else{

        $man = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'manufacturer');

        
        $productModel = Mage::getModel('catalog/product');
        $attr = $productModel->getResource()->getAttribute("manufacturer");
        if ($attr->usesSource()) {
             $man_id = $attr->getSource()->getOptionId($this->getRequest()->getParam('manufacturer'));
        }




       $limit =  $this->getRequest()->getParam('limit');
       $page = $this->getRequest()->getParam('p');

        Mage::log('PAGE ::'.$page);
        Mage::log('LIMIT ::'.$limit);
        
        Mage::log('MAN ID ::'.$man_id);
         Mage::log('INDEX BRAND CONSTRUCT MANUFACTURER BLOCK');
        $collection = Mage::getResourceModel('catalog/product_collection')
      ->addAttributeToSelect('*')
      ->addAttributeToFilter('visibility', array('neq' => 1))
      ->addAttributeToFilter('status', 1)
      //->addAttributeToFilter('manufacturer_value')
      ->addAttributeToFilter('manufacturer', array('eq' => $man_id ))
      ->setCurPage($page)
      ->setPageSize($limit)
      ->load();

        //Mage::log((string) $collection->getSelect());
        //Mage::log($collection);
      }

        Mage::log('INDEX BRAND CONSTRUCT: Load Complete');




       // $collection = Mage::getModel('example/collection')->getCollection();
        $this->setCollection($collection);
    }
	
	protected function _prepareLayout()
    {
        Mage::log('INDEX BRAND PREPARE LAYOUT CALL');
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
        Mage::log('INDEX BRAND PAGER BLOCK CALL');
        return $this->getChildHtml('pager');
    }

    public function getToolbarBlock()
    {
        if ($blockName = $this->getToolbarBlockName()) {
            if ($block = $this->getLayout()->getBlock($blockName)) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, microtime());
        return $block;
    }



}