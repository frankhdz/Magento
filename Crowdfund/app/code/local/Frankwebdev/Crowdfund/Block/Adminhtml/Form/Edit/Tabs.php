<?php

class Frankwebdev_Crowdfund_Block_Adminhtml_Form_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('crowdsourse_tabs');
        $this->setDestElementId('edit_form');// this was causing the block to load in the same column
        $this->setTitle(Mage::helper('crowdfund')->__('New Project Information'));
    }


      protected function _prepareLayout()
    {
       

            Mage::log("Tabs php");
            $helper = Mage::helper('crowdfund');
           
            //NOTE: You must place the form inside the tab folder for the tab action to work
            $this->addTab('project_settings', array(
                'label'     => $helper->__('Project Settings'),
                'title'     => $helper->__('Project Settings'),
                'content'   => $this->getLayout()->createBlock('crowdfund/adminhtml_form_edit_tab_form')->toHtml(),
                'active'    => true
            ));
            $this->addTab('project_cms', array(
                'label'     => $helper->__('Content'),
                'title'     => $helper->__('Content'),
                'content'   => $this->getLayout()->createBlock('crowdfund/adminhtml_form_edit_tab_addcms')->toHtml(),
                'active'    => false
            ));
            $this->addTab('websites', array(
                    'label'     => Mage::helper('crowdfund')->__('Websites'),
                    'content'   => $this->getLayout()->createBlock('crowdfund/adminhtml_form_edit_tab_websites')->toHtml(),
                ));

            // this tab was used to test the product IDS. the proper method is with a magento grid to parse the product data
           /* $this->addTab('rewards', array(
                    'label'     => Mage::helper('crowdfund')->__('rewards'),
                    'content'   => $this->getLayout()->createBlock('crowdfund/adminhtml_form_edit_tab_rewards')->toHtml(),
                ));*/ 
            $this->addTab('rewardsgrid', array(
                'label'     => Mage::helper('crowdfund')->__('Rewards'),
                'url'       => $this->getUrl('*/*/productsmodelgrid', array('_current' => true)),
                'class'     => 'ajax',
            ));

        


        
        return parent::_beforeToHtml();
    }

}
