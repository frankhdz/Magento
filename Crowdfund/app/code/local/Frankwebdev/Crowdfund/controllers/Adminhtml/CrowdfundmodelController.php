<?php

class Frankwebdev_Crowdfund_Adminhtml_CrowdfundmodelController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()
				->_setActiveMenu("crowdfund/crowdfundmodel")
				->_addBreadcrumb(Mage::helper("adminhtml")
				->__("Crowdfundmodel  Manager"),Mage::helper("adminhtml")->__("Crowdfundmodel Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    Mage::log('INDEX ACTION');
			    $this->_title($this->__("Crowdfund"));
			    $this->_title($this->__("Manager Crowdfundmodel"));

				$this->_initAction();
				$this->renderLayout();
		}
		
		public function productsmodelgridAction()
    	{
        Mage::log('productsmodelgridAction ACTION');
        //$this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('productsmodel.grid')
            ->setProducts($this->getRequest()->getPost('products', null));
        $this->renderLayout();
    	}
		
		
		public function relatedAction()
	    {
	        Mage::log('relatedAction ACTION');
	       //Mage::log($this->getRequest()->getPost());
	        $this->loadLayout();
	        $this->getLayout()->getBlock('productsmodel.grid')
	            ->setProducts($this->getRequest()->getPost('products', null));
	        $this->renderLayout();
	    }

		
		public function editAction()
		{			    
			    $this->_title($this->__("Crowdfund"));
				$this->_title($this->__("Crowdfundmodel"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				Mage::log("ITEM ID : ".$id);
				$model = Mage::getModel("crowdfund/crowdfundmodel")->load($id);
				if ($model->getId()) {
					Mage::register("crowdfundmodel_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("crowdfund/crowdfundmodel");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Crowdfundmodel Manager"), Mage::helper("adminhtml")->__("Crowdfundmodel Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Crowdfundmodel Description"), Mage::helper("adminhtml")->__("Crowdfundmodel Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("crowdfund/adminhtml_form_edit"))->_addLeft($this->getLayout()->createBlock("crowdfund/adminhtml_form_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("crowdfund")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Crowdfund"));
		$this->_title($this->__("Crowdfundmodel"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("crowdfund/crowdfundmodel")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("crowdfundmodel_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("crowdfund/crowdfundmodel");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Crowdfundmodel Manager"), Mage::helper("adminhtml")->__("Crowdfundmodel Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Crowdfundmodel Description"), Mage::helper("adminhtml")->__("Crowdfundmodel Description"));


		$this->_addContent($this->getLayout()->createBlock("crowdfund/adminhtml_form_edit"))->_addLeft($this->getLayout()->createBlock("crowdfund/adminhtml_form_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();

			//delete old image if set to true;
 						if(isset($post_data['project_image'])){
	 						foreach($post_data['project_image'] as $img=>$val){

	 							Mage::log($img);
	 							Mage::log($val);
	 							if($img=='value'){
	 								//get the value to delte
	 								if($img=='value'){
	 									$img = str_replace(Mage::getBaseUrl('media')."projects".DS,"",Mage::getBaseDir('media').DS."projects".DS.$val);
	 									

	 									Mage::log('DEL IMG : '.$img);
	 									unlink($img);
	 								}
	 							}

	 						}
 						}

 						/*if($post_data['project_image'][0]==1){
 							Mage::log('PASS DELETE CHECK');
 							//check if there is an image to delete;
 							if($post_data['project_image']['value']!="" || isset($post_data['project_image']['value'])){
 								
 							}

 						}*/

			Mage::log($post_data);

				if (isset($_FILES['project_image']['name']) && $_FILES['project_image']['name'] != '') {
				    Mage::log('IM NAM: ');
				    Mage::log($_FILES['project_image']['name']);
				    try {  
				        $uploader = new Varien_File_Uploader('project_image');

    					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything

 						$uploader->setAllowRenameFiles(false);

 						$uploader->setFilesDispersion(false);

  						$path = Mage::getBaseDir('media').DS. "projects" ;

 						$uploader->save($path, $_FILES['project_image']['name']);

 						$post_data['project_image'] = $_FILES['project_image']['name'];

 						
				         
				    } catch (Exception $e) {
				         
				    }
				}else{

					//get old value
					$image_val = $post_data['project_image']['value'];
					$post_data['project_image'] = $str = trim(substr($image_val, strrpos($image_val, '/') + 1));

				}

				

				if ($post_data) {

					try {

						
						
						//convert ids to product_id;
						//$post_data['product_id'] = $post_data['ids']; 

						$model = Mage::getModel("crowdfund/crowdfundmodel")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Crowdfundmodel was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setCrowdfundmodelData(false);

						Mage::log("LINKS ? ");
						Mage::log($post_data['links']);

						if(isset($post_data['links'])){
		                    $products = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post_data['links']['products']); //Save the array to your database
		                    Mage::log("RETRIEVE PRODUCTS : ");
		                    
		                    $collection = Mage::getModel('crowdfund/grid')->getCollection();
							Mage::log("SAVE PROJECT ID ? ".$model->getId());

							$collection->addFieldToFilter('project_id',$model->getId());
							foreach($collection as $obj){
								$obj->delete();
							}
							foreach($products as $key => $value){
								$model2 = Mage::getModel('crowdfund/grid');
								$model2->setProjectId($model->getId());
								$model2->setProductId($key);
								$model2->setPosition($value['position']);
								$model2->save();
							}
		                    /*$pid_str = '';//store the product i'ds in array
		                    foreach($products as $product=>$pos){
									Mage::log( $product);
									$pid_str .= $product.',';
		                    }*/

		                    //$post_data['product_id'] = rtrim($pid_str, ",");
		                }
						if ($this->getRequest()->getParam("back")) {
							if ($this->getRequest()->getParam("back")) {
							Mage::log('FOUND TH$E BACK VAR!!');
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}

						
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setCrowdfundmodelData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				//$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("crowdfund/crowdfundmodel");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("crowdfund/crowdfundmodel");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'crowdfundmodel.csv';
			$grid       = $this->getLayout()->createBlock('crowdfund/adminhtml_crowdfundmodel_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'crowdfundmodel.xml';
			$grid       = $this->getLayout()->createBlock('crowdfund/adminhtml_crowdfundmodel_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
