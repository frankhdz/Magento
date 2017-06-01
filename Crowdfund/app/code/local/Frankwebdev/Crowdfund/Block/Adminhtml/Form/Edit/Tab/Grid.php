<?php

class Frankwebdev_Crowdfund_Block_Adminhtml_Form_Edit_Tab_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				/*parent::__construct();
				$this->setId("crowdfundmodelGrid");
				
				$this->setSaveParametersInSession(true);*/
				 parent::__construct();
		        $this->setId('productsmodel_grid');
		        $this->setDefaultSort('entity_id');
		        //$this->setDefaultSort("id");
				$this->setDefaultDir("ASC");
		        $this->setUseAjax(true);
		        $this->setDefaultFilter(array('in_products'=>1));
		        //$this->setSaveParametersInSession(false);
		        //$this->setFilterVisibility(false);
		        /*if ($this->_getProduct()->getId()) {
		            $this->setDefaultFilter(array('in_products'=>1));
		        }
		        if ($this->isReadonly()) {
		            
		        }*/
		}
		
		
		protected function _addColumnFilterToCollection($column)
	    {
	        // Set custom filter for in product flag
	        Mage::log("_addColumnFilterToCollection FILTER VALUE : ".$column->getFilter()->getValue());
	       
	        if ($column->getId() == 'in_products') {
	           // Mage::log('TRUE IN PRODUCTS');
	            $productIds = $this->_getSelectedProducts();
	            // Mage::log($productIds);
	            // Mage::log('/TRUE IN PRODUCTS');
	            if (empty($productIds)) {
	                Mage::log('empty pids');
	                $productIds = 0;
	            }
	           
	            if ($column->getFilter()->getValue()) {
	                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
	            } else {
	                if($productIds) {
	                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
	                }
	            }
	        } else {

	            parent::_addColumnFilterToCollection($column);
	        }
	        return $this;
	    }
	    public function isReadonly()
	    {
	        return 0;
	    }

		protected function _prepareCollection()
		{
				/*$collection = Mage::getModel("crowdfund/crowdfundmodel")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();*/
				 Mage::log('PREPARE COLLECTION');
				 $store_id = 1;
				 $collection = Mage::getModel('catalog/product')
		            ->getCollection()
		            /*->setProduct($this->_getProduct())*/
		           ->addStoreFilter($store_id)
		           ->addAttributeToSelect('name')
		           ->addAttributeToSelect('entity_id')
		           ->addAttributeToSelect('price')
		           
		          ;

		           if ($this->isReadonly()) {
		            	$productIds = $this->_getSelectedProducts();
			            if (empty($productIds)) {
			                $productIds = array(0);
			            }
			            $collection->addFieldToFilter('entity_id', array('in'=>$productIds));
		        	}

		       /* $this->setCollection($collection);
		        return parent::_prepareCollection();*/

		           /* $productIds = $this->_getSelectedProducts();
		            Mage::log($productIds);
		            if (empty($productIds)) {
		                $productIds = array(0);
		            }*/
		          // $collection->addFieldToFilter('entity_id', array('in' => $productIds)); turned off this option but to get the full collection
		           // Mage::log($collection);
		        /*if ($this->isReadonly()) {
		            $productIds = $this->_getSelectedProducts();
		            if (empty($productIds)) {
		                $productIds = array(0);
		            }
		            $collection->addFieldToFilter('entity_id', array('in' => $productIds));
		        }*/
		        $tm_id = $this->getRequest()->getParam('id');
		        Mage::getResourceModel('crowdfund/grid')->addGridPosition($collection,$tm_id);

		        $this->setCollection($collection);
		        return parent::_prepareCollection();


		}
		protected function _prepareColumns()
		{
			$this->addColumn('in_products', array(
                'header_css_class'  => 'a-center',
                'type'              => 'checkbox',
                'name'              => 'in_products',
                'values'            => $this->_getSelectedProducts(),
                'align'             => 'center',
                'index'             => 'entity_id'
            ));

			$this->addColumn('entity_id', array(
            'header'    => Mage::helper('crowdfund')->__('ID'),
            'sortable'  => true,
            'width'     => 60,
            'index'     => 'entity_id'
	        ));
	        
	        $this->addColumn('name', array(
            'header'    => Mage::helper('crowdfund')->__('Name'),
            'sortable'  => true,
            'width'     => 60,
            'index'     => 'name'
	        ));

	       /* $this->addColumn('visibility', array(
	            'header'    => Mage::helper('crowdfund')->__('Visibility'),
	            'width'     => 90,
	            'index'     => 'visibility',
	            'type'      => 'options',
	            'options'   => Mage::getSingleton('catalog/product_visibility')->getOptionArray(),
	        ));*/

	        $this->addColumn('sku', array(
	            'header'    => Mage::helper('crowdfund')->__('SKU'),
	            'width'     => 80,
	            'index'     => 'sku'
	        ));
	        

	        $this->addColumn('price', array(
	            'header'        => Mage::helper('crowdfund')->__('Price'),
	            'type'          => 'currency',
	            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
	            'index'         => 'price'
	        ));

	        $this->addColumn('position', array(
            'header'            => Mage::helper('crowdfund')->__('Position'),
            'name'              => 'position',
            'type'              => 'number',
            'width'             => 60,
            'validate_class'    => 'validate-number',
            'index'             => 'position',
            'editable'          => true,
            'edit_only'         => false,
            
       		 ));

	       
	        //unset($this->column['massaction']);
        return parent::_prepareColumns();
		}

		/*public function getRowUrl($row)
		{
			   return '#';
		}*/

		public function getGridUrl()
    {
        return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/*/related', array('_current'=>true));
    }

		
		
		protected function _getSelectedProducts()
    {
        Mage::log('_getSelectedProducts');
       $products = array_keys($this->getSelectedRelatedProducts());
        
        Mage::log($products);
       return $products;
    }

    /**
     * Retrieve related products
     *
     * @return array
     */
   /* public function getSelectedRelatedProducts()
    {
        $products = array() ;


        foreach (explode(',', Mage::getSingleton('core/session')->getProjectIds()) as $product) {
            Mage::log('PID : '.$product);
            $products[$product] = $product;
        }
       
        return $products;
    }*/
    public function getSelectedRelatedProducts()
    {
        $tm_id = $this->getRequest()->getParam('id');
        if(!isset($tm_id)) {
			$tm_id = 0;
		}
       
        $collection = Mage::getModel('crowdfund/grid')->getCollection()->addFieldToFilter('project_id',$tm_id);
		$collection->addFieldToFilter('project_id',$tm_id);
        $products = array();
        foreach ($collection as $product) {
        	//++$count;
        	$pos = $product->getData('position');
            $products[$product->getData('product_id')] = array('position'=>$pos);
        }
        Mage::log('/CHECK');
        return $products;
    }
			

}