<?php
class Frankwebdev_Crowdfund_Block_Latestprojects  extends Mage_Core_Block_Abstract implements Mage_Widget_Block_Interface
{

    /**
     * Produces digg link html //this getData(count the projects);
     *
     * @return string
     */
    protected function _toHtml()
    {
       
    	$cnt = $this->getData('pcount');
    	Mage::log('crowdfund widget count :'.$cnt);
    	$block = $this->getLayout()->createBlock('core/template');
    	$block->setProjectCount($cnt);
    	$block->setTemplate('crowdfund/widget/latestprojects.phtml');
    	
    	return $block->_toHtml();
    }
}

