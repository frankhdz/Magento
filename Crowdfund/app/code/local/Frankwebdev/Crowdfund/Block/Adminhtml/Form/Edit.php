<?php

class Frankwebdev_Crowdfund_Block_Adminhtml_Form_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct()
    {
        parent::__construct();
                  
        $this->_objectId = 'id';
        $this->_blockGroup = 'crowdfund';
        $this->_controller = 'adminhtml_form';
         
        $this->_updateButton('save', 'label', Mage::helper('crowdfund')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('crowdfund')->__('Delete'));
         
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
           function saveAndContinueEdit(){
                alert($('edit_form').action+'back/edit/');
                
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
        
        
            /**/$projectid = $this->getRequest()->getParam('id');
            $projecttable  = Mage::getModel('crowdfund/crowdfundmodel')->load($projectid);
            if ($projecttable->getId()) {
                     Mage::register('project_data', $projecttable);
                     //get Project product id's
                     $p_ids =  Mage::registry('project_data')->getData();
                     Mage::log('INIT SET REG : '.$p_ids['product_id']);
                     
                     //Mage::register('crowdfund_current_product', $p_ids['product_id']);
                     //Mage::getSingleton('core/session')->setProjectIds($p_ids['product_id']);
            }else{
                    Mage::register('project_data', "");

            }
        

    }

    
    public function getHeaderText()
    {
        return Mage::helper('crowdfund')->__('Greenbrier Crowdfund');
    }



   

}
