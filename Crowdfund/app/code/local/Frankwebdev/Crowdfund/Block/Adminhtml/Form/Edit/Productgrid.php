<?php 

class Frankwebdev_Crowdfund_Block_Adminhtml_Form_Edit_Productgrid extends Mage_Adminhtml_Block_Widget_Form{

	protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

	protected function _prepareForm(){

		Mage::log("PREPARE FORM");

		//initialize form object
		$form = new Varien_Data_Form(
				array(
						'id' => 'edit_form',
						'action' => $this->getUrl('*/*/newproject'),
						'method' => 'post',
				)
		);
		$helper = Mage::helper('crowdfund');
		//$form->setDataObject(Mage::getModel('catalog/product'));
		$fieldset = $form->addFieldset('display', array(
				'legend' => $helper->__('Crowdfund: Create New Project'),
				'class' => 'fieldset-wide'
		));

		//Add form fields
		$fieldset->addField('note', 'note', array(
          'text'     => $helper->__('<span style="color:#900;font-weight: bold;">Before creating a project be sure to first create a product to associate.<span>'),
        ));


		$fieldset->addField('project_name', 'text', array(
				'name' => 'project_name',
				'class'     => 'required-entry',
				'required'  => true,
				'label' => $helper->__('Project Name'),
		));
		
		$fieldset->addField('project_start', 'date', array(
          'label'     => $helper->__('Start Date'),
          'after_element_html' => '<small>Set the date the project is visible in the front end.</small>',
          'tabindex' => 1,
          'image' => $this->getSkinUrl('images/grid-cal.gif'),
          'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
          'class'     => 'required-entry',
		  'required'  => true,
        ));
        $fieldset->addField('project_end', 'date', array(
          'label'     => $helper->__('End Date'),
          'after_element_html' => '<small>Set Project End.</small>',
          'tabindex' => 1,
          'image' => $this->getSkinUrl('images/grid-cal.gif'),
          'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
          'class'     => 'required-entry',
		  'required'  => true,
        ));


		$fieldset->addField('project_cms', 'editor', array(
				'label'     => $helper->__('Content'),
				'class'     => 'required-entry',
				'required'  => true,
				'name'      => 'shortdescription',
				'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
				'wysiwyg' => true,
				'value'  => '',
		));

		$fieldset->addField('goal', 'text', array(
          'label'     => $helper->__('Goal'),
          'after_element_html' => '<small>Set the project\'s funding goal</small>',
          'tabindex' => 1,
          'image' => $this->getSkinUrl('images/grid-cal.gif'),
          'class'     => 'required-entry',
		  'required'  => true,
          
        ));


		

		$form->setUseContainer(true);

		$this->setForm($form);

		return parent::_prepareForm();


	}
		
		
}