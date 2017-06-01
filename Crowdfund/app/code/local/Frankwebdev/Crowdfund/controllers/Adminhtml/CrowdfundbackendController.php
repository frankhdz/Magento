<?php
class Frankwebdev_Crowdfund_Adminhtml_CrowdfundbackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Backend Page Title"));
	   //$this->_controller = "adminhtml_form";
	   $this->renderLayout();
	   
    }

    public function newprojectAction(){

    	Mage::log('ADDING NEW PROJECT');
    	Mage::getSingleton('adminhtml/session')->addSuccess("Project Created");
    	$this->_redirect('*/*');
    }
}