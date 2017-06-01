<?php 

class Frankwebdev_Crowdfund_Block_Adminhtml_Form_Edit_Tab_Addcms extends Mage_Adminhtml_Block_Widget_Form{

	protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

	protected function _prepareForm(){

		if(Mage::registry('project_data')!=""){
       		$values = Mage::registry('project_data')->getData();
	    }else{
	      $values = array("id"=>"","project_cms"=>"","project_image"=>"");

	    }
          
       

		//initialize form object
		$form = new Varien_Data_Form(); 
		//$form->setUseContainer(true);

		$this->setForm($form);
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

		if( $values['id']!=""){
			$fieldset->addField('id', 'hidden', array(
			      'name'               => 'id',
			      'value'              => $values['id'],
			));
		}

		$fieldset->addField('project_short_description', 'textarea', array(
				'label'     => $helper->__('Short Description (150 characters max)'),
				'class'     => 'required-entry',
				'required'  => true,
				'name'      => 'project_short_description',
				'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
				'wysiwyg' => true,
				'value'  => '',
				'value'   => $values["project_short_description"],
		));

		$fieldset->addField('project_cms', 'editor', array(
				'label'     => $helper->__('Content'),
				'class'     => 'required-entry',
				'required'  => true,
				'name'      => 'project_cms',
				'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
				'wysiwyg' => true,
				'value'  => '',
				'value'   => $values["project_cms"],
		));

		/*$fieldset->addField('projectimage', 'image', array(
		    'label'     =>  $helper->__('Thumbnail'),
		    'name'      => 'thumb',
		));*/
		
		
		$isimage = $values["project_image"];
		Mage::log("DE : ".$isimage);
		if($isimage!="Array"){
			Mage::log("NOT");
			$imageLocation = Mage::getBaseUrl('media')."projects".DS.$values["project_image"];
		}else{
			Mage::log("IS");
			$imageLocation = "";
		}
		Mage::log("FILE URI : ".$imageLocation);
		
		if($imageLocation!=""){
			Mage::log("SI");
			$fieldset->addField('project_image', 'image', array(
          'label'     => $helper->__('Upload Project Thumb'),
          'name'	=> 'project_image',
          
          'disabled' => false,
          //'readonly' => false,
          'after_element_html' => '<small>Image size should be 640x390 for best results</small>',
          'tabindex' => 1,
          'class'     => 'required-entry',

          'value'   => $imageLocation,
		  'required'  => true,

        ));

		}else{
			Mage::log("NI");
			$fieldset->addField('project_image', 'image', array(
	          'label'     => $helper->__('Upload Project Thumb'),
	          'name'	=> 'project_image',
	          
	          'disabled' => false,
	          //'readonly' => false,
	          //'after_element_html' => '<small>Comments</small>',
	          'tabindex' => 1,
	          'class'     => 'required-entry',
			  'required'  => true,

	        ));
		}

		


		

		

		return parent::_prepareForm();


	}
		
		
}