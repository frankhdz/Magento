<?php
class Frankwebdev_Crowdfund_Adminhtml_CrowdfundimportController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
      /* Mage::log('IMPORT INDEX');
       $this->loadLayout();
	   $this->_title($this->__("Import Orders"));
	   //$this->_controller = "adminhtml_form";
       $this->_initAction();
	   $this->renderLayout();*/


      /* $this->_title($this->__("Crowdfund"));
        $this->_title($this->__("Crowdfundmodel"));*/
        $this->_title($this->__("Import Orders"));

        //$id   = $this->getRequest()->getParam("id");
       // $model  = Mage::getModel("crowdfund/crowdfundmodel")->load($id);

       /* $data = Mage::getSingleton("adminhtml/session")->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }*/

        //Mage::register("crowdfundmodel_data", $model);

        $this->loadLayout();
        $this->_setActiveMenu("crowdfund/orders");

        $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Import Orders"), Mage::helper("crowdfund")->__("Import Orders"));


        $this->_addContent($this->getLayout()->createBlock("crowdfund/adminhtml_form_importform"));

        $this->renderLayout();
	   
    }

    public function ajaxprojectproductsAction(){

        //retrieve ajax function
       // Mage::log($this->getRequest()->getPost());
        $projectid = $this->getRequest()->getParam('project_id');
        
       // Mage::log("AJAX CALL : ".$projectid);
        
        $collection = Mage::getModel('crowdfund/grid')->getCollection();
        $collection->addFieldToFilter('project_id',$projectid);
        $products = Mage::getModel('catalog/product');
        
        foreach($collection as $obj){
            $product_id = $obj->getData('product_id');
            $_product = $products->load($product_id);
            $val[$product_id] = $_product->getName() ;
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($val));


        return $val;
       
    }

   public function saveAction()
        {

            //save imported data here
            //we will create an order and save the data belongs to crowdfund tables here
            //we will save order id, customer id, how much was pleged, what products belong to this pledge
            $post_data=$this->getRequest()->getPost();

            //$code = Mage::getSingleton('adminhtml/config_data')->getStore();

            $store_id = Mage::app()->getStore()->getStoreId();
           
            Mage::log('SELECTED STORE ID : '.$store_id);

           if ($post_data['product_id']) {
                try{
                    
                    Mage::log('IMORT ORDERS : ');
                    Mage::Log($post_data);
                    
                    if(isset($_FILES['file']['name']) and (file_exists($_FILES['file']['tmp_name']))) {
                         Mage::log('PROCESS CSV IMPORT DATA');

                         $kickstarter_csv =  str_getcsv(file_get_contents($_FILES['file']['tmp_name']), "\n");//parse to array fro processing orders

                        

                    }

                    Mage::log(' --------------  check store ID : '.$post_data['storeid']);
                    if($post_data['storeid']!=0){
                    //create order
                    //Mage::log($kickstarter_csv);
                    Mage::helper('crowdfund')->admincreateorders($kickstarter_csv,$post_data['project_id'],$post_data['product_id'],$post_data['storeid']);
                    
                    Mage::log(Mage::getSingleton("adminhtml/session")->addSuccess('Orders were successfully imported.'));
                    $this->_redirectReferer();
                    }else{
                        Mage::log(Mage::getSingleton("adminhtml/session")->addError('Please select a store scope to import orders'));
                    $this->_redirectReferer();
                    }




                }
                catch (Exception $e){
                    Mage::log(Mage::getSingleton("adminhtml/session")->addError($e->getMessage()));
                    $this->_redirectReferer();
                }
            }

                // if ($post_data) {

                //     try {
                //         //check for file data
                        

                //         $model = Mage::getModel("crowdfund/orders")
                //         ->addData($post_data)
                //         ->setId($this->getRequest()->getParam("id"))
                //         ->save();

                //         Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Crowdfundmodel was successfully saved"));
                //         Mage::getSingleton("adminhtml/session")->setCrowdfundmodelData(false);

                //         if ($this->getRequest()->getParam("back")) {
                //             $this->_redirect("*/*/edit", array("id" => $model->getId()));
                //             return;
                //         }
                //         $this->_redirect("*/*/");
                //         return;
                //     } 
                //     catch (Exception $e) {
                //         Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                //         Mage::getSingleton("adminhtml/session")->setCrowdfundmodelData($this->getRequest()->getPost());
                //         $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
                //     return;
                //     }

                // }
               // $this->_redirect("*/*/");
        }

    
}