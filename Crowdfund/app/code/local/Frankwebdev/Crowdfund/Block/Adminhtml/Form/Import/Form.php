<?php

class Frankwebdev_Crowdfund_Block_Adminhtml_Form_Import_Form extends Mage_Adminhtml_Block_Widget_Form
{
  public function getProjectsList(){
    Mage::log('GET PROJECT LIST');
    $projects = array();
    
    $store = $this->getRequest()->getParam('store');

    if(isset($store)){
    $collection = Mage::getModel('crowdfund/crowdfundmodel')->getCollection();
    $collection->addFieldToFilter('store_id',$store);

       $projects[0] = "Select a project";
      foreach($collection as $obj){

        $project_id = $obj->getData('id');
        $project_name = $obj->getData('project_name');
        $projects[$project_id] = $project_name;
      }
    }else{
      $collection = Mage::getModel('crowdfund/crowdfundmodel')->getCollection();
    //$collection->addFieldToFilter('store_id',$store);

       $projects[0] = "Select a project";
      foreach($collection as $obj){

        $project_id = $obj->getData('id');
        $project_name = $obj->getData('project_name');
        $projects[$project_id] = $project_name;
      }
    }


    return $projects;
  }


	protected function _prepareForm()
	{
		Mage::Log('START IMPORT FORM');

    $store_id = "0";
    if($this->getRequest()->getParam('store') != null ){
      $store_id = $this->getRequest()->getParam('store');
    }
     
    

		$form = new Varien_Data_Form(
				array(
						'id' => 'edit_form',
						'action' => $this->getUrl('*/*/save'),
            'enctype' => 'multipart/form-data',
						'method' => 'post',
				)
		);

		//$form->setDataObject(Mage::getModel('crowdfund/crowdfundmodel'));

		$form->setUseContainer(true);
    //$this->setForm($form);


		//create form fields
		$helper = Mage::helper('crowdfund');

		$fieldset = $form->addFieldset('display', array(
				'legend' => $helper->__('Import project orders'),
				'class' => 'fieldset-wide'
		));
    Mage::log('STORE PARAM : '.$store_id);
    $fieldset->addField('storeid','hidden',array(
      'name' => 'storeid',
      'value'=> $store_id

      ));

		$fieldset->addField('file', 'file', array(
          'label'     => $helper->__('CSV Import'),
          //'value'  => 'Uplaod',
          'disabled' => false,
          //'readonly' => true,
          'required'  => true,
          'class'     => 'required-entry',
          'name' => 'file',
          'after_element_html' => '<small>Please double check format. Get the project ID and the product ID you wish to add</small>',
          'tabindex' => 1,
          
        ));
   

		$projects = $this->getProjectsList();


    $fieldset->addField('project_id', 'select', array(
          'label'     => Mage::helper('crowdfund')->__('Project ID'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'project_id',
          'class'     => 'required-entry',
          /*'onclick' => "",*/
         'onchange' => "loadAjaxImportProduct()",//load ajax
          'value'  => '1',
          'values' => $projects,//array('-1'=>'Please Select..','1' => 'Option1','2' => 'Option2', '3' => 'Option3'),
          'disabled' => false,
          'readonly' => false,
          'after_element_html' => '<small>Select Project</small>',
          /*'tabindex' => 1*/
        ));
    //this field needs to be populated via ajax
        $fieldset->addField('product_id', 'select', array(
          'label'     => Mage::helper('crowdfund')->__('Product ID'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'product_id',
          'class'     => 'required-entry',
          /*'onclick' => "",*/
         // 'onchange' => "",//load ajax
          'value'  => '1',
          'values' => array('-1'=>'Select a project to load'),
          'disabled' => false,
          'readonly' => false,
          'after_element_html' => '<small>Select a project first</small>',
          /*'tabindex' => 1*/
        ));



		/*$form->setUseContainer(true);*/

		$this->setForm($form);
		return parent::_prepareForm();

	}

}