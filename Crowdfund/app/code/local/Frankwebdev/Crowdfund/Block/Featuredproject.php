<?php
class Frankwebdev_Crowdfund_Block_Featuredproject  extends Mage_Core_Block_Abstract implements Mage_Widget_Block_Interface
{

    /**
     * Produces digg link html //this getData(count the projects);
     *
     * @return string
     */
    protected function _toHtml()
    {
       
    	$project_id = $this->getData('projectid');
    	Mage::log('crowdfund widget project id :'.$project_id);
    	$block = $this->getLayout()->createBlock('core/template');
    	$block->setProjectId($project_id);
    	$block->setTemplate('crowdfund/widget/featuredproject.phtml');
    	
    	return $block->_toHtml();
    }
}

