<?php

class Frankwebdev_Crowdfund_CustomerController extends Mage_Core_Controller_Front_Action{ 

	public function preDispatch(){
        parent::preDispatch();
 
        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
             
        // adding message in customer login page
        Mage::getSingleton('core/session')
                ->addSuccess(Mage::helper('mymodule')->__('Please sign in or create a new account'));
        }
    }

    public function viewAction(){                  
	    $crowdfundconfig =  Mage::getStoreConfig('crowdfundsettings');

        if($crowdfundconfig['config']['enable_module']){

        $this->loadLayout();       
	    $this->getLayout()->getBlock('head')->setTitle($this->__('Project Credits'));    
	    $this->renderLayout();
        }
    }

}