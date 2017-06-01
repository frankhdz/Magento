<?php 

class Frankwebdev_Crowdfund_Block_Adminhtml_Form_Edit_Form extends Mage_Adminhtml_Block_Widget_Form{

    

	protected function _prepareForm(){
		
		
		//$model = Mage::registry('crowdfund');
			

		$form = new Varien_Data_Form(
				array(
						'id' => 'edit_form',
						'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
						'method' => 'post',
                        'enctype' => 'multipart/form-data'

				)
		);

		



		//leave this blank add the form fields in the tabs folder files	

		$form->setUseContainer(true);

		$this->setForm($form);


		
		/*$values = Mage::registry('project_data')->getData();
		$form->setValues($values);*/

		return parent::_prepareForm();


	}
		
		
}