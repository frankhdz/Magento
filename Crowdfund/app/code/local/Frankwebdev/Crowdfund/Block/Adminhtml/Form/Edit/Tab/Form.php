<?php 

class Frankwebdev_Crowdfund_Block_Adminhtml_Form_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form{

	/*protected function _prepareLayout()
    {
        Mage::log("FORM PREPARE LAYOUT");
        parent::_prepareLayout();
        $helper = Mage::helper('crowdfund');
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        
    }*/
    
  protected function _prepareForm(){

		if(Mage::registry('project_data')!=""){
       $values = Mage::registry('project_data')->getData();
    }else{
      $values = array("project_name"=>"","project_start"=>"","project_end"=>"","project_goal"=>"","is_disabled"=>0);

    }
    Mage::log("PREPARE FORM X");

		//initialize form object
		$form = new Varien_Data_Form();
		$helper = Mage::helper('crowdfund');
		//$form->setDataObject(Mage::getModel('catalog/product'));
		$fieldset = $form->addFieldset('display', array(
				'legend' => $helper->__('Crowdfund: Create New Project'),
				'class' => 'fieldset-wide'
		));

		//Add form fields
    Mage::log("GOAL ? ");
    Mage::log($values['goal']);


		$fieldset->addField('note', 'note', array(
          'text'     => $helper->__('<span style="color:#900;font-weight: bold;">Before creating a project be sure to first create a product to associate.<span>'),
        ));
    if($values['is_disabled'] == 1){
      $isdisabledval = 1;
      $ischecked = true;
    }else{
      $isdisabledval = 0;
      $ischecked = false;
    }
    $fieldset->addField('is_disabled', 'checkbox', array(
          'label'     => $helper->__('Disabled'),
          'name'      => 'is_disabled',
          'checked' => $ischecked,
          //'onclick' => "",
          //'onchange' => "",
          'value'  => $isdisabledval,
          //'disabled' => false,
          'after_element_html' => '<small>Disable the project</small>',
          //'tabindex' => 1
        ));

		$fieldset->addField('project_name', 'text', array(
				'name' => 'project_name',
				'class'     => 'required-entry',
				'required'  => true,
				'label' => $helper->__('Project Name'),
        'value' => $values['project_name'],
		));
		
		$fieldset->addField('project_start', 'date', array(
          'label'     => $helper->__('Start Date'),
          'name'	=> 'project_start',
          'after_element_html' => '<small>Set the date the project is visible in the front end.</small>',
          'tabindex' => 1,
          'image' => $this->getSkinUrl('images/grid-cal.gif'),
          'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
          'class'     => 'required-entry',
		      'required'  => true,
          'value' => $values['project_start'],
        ));
        $fieldset->addField('project_end', 'date', array(
          'label'     => $helper->__('End Date'),
          'name'	=> 'project_end',
          'after_element_html' => '<small>Set Project End.</small>',
          'tabindex' => 1,
          'image' => $this->getSkinUrl('images/grid-cal.gif'),
          'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
          'class'     => 'required-entry',
		      'required'  => true,
          'value' => $values['project_end'],
        ));



		$fieldset->addField('goal', 'text', array(
          'label'     => $helper->__('Goal'),
          'name'	=> 'goal',
          'type' => 'currency',
          'after_element_html' => '<small>Set the project\'s funding goal</small>',
          'tabindex' => 1,
          'class'     => 'required-entry',
		      'required'  => true,
          'value' => $values['goal'],
          
        ));

		


		

		//$form->setUseContainer(true);

		$this->setForm($form);

		return parent::_prepareForm();


	}
		
		
}