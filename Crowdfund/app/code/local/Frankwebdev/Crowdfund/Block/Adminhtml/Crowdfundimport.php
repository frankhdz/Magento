<?php  

class Frankwebdev_Crowdfund_Block_Adminhtml_Crowdfundimport extends Mage_Adminhtml_Block_Template {

	protected function _prepareLayout(){
		parent::_prepareLayout();
	   /* if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
	        $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
	    }*/
	}

	

}