<?php

class Frankwebdev_Crowdfund_Block_Adminhtml_Form_Importform extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct()
    {
        parent::__construct();
                  
        //$this->_objectId = 'id';
        $this->_blockGroup = 'crowdfund';
        $this->_controller = 'adminhtml_form';
        $this->_mode = 'import';
        //this is basically this path:
        // Crowdfund/Adminhtml/Form/Import   the form name is implied and it will automatically look for a file named Form.php
         
        $this->_updateButton('save', 'label', Mage::helper('crowdfund')->__('Import'));
        /*$this->_updateButton('delete', 'label', Mage::helper('crowdfund')->__('Delete'));*/
         
         $getAjaxURL = $this->getUrl('crowdfund/adminhtml_crowdfundimport/ajaxprojectproducts');
        $this->_formScripts[] = '
            

       function loadAjaxImportProduct(){
           
            pid = document.getElementById("project_id").value;
            
            //this is the loading animation magic
            var r = {options:{loadArea:\'\'}};
            varienLoaderHandler.handler.onCreate(r);
            
            $j(document).ready(function(){
                
               
                $j.ajax({
                  url: "'.$getAjaxURL.'project_id/'.'"+pid,
                  type: "get",
                  contentType: "application/json; charset=utf-8",
                  dataType: "json",
                  success: function(data){
                        $j("#product_id").get(0).options.length = 0;
                        $j("#product_id").get(0).options[0] = new Option("Select Product", "-1");   
 
                        $j.each(data, function(index, item) {
                           $j("#product_id").get(0).options[$j("#product_id").get(0).options.length] = new Option(item, index);
                        });
                        
                        //stop the loader when data is complete
                        varienLoaderHandler.handler.onComplete();
                       


                  }
                });
                
          });
           
        }

        




        ';
        

    }

    
    public function getHeaderText()
    {
        return Mage::helper('crowdfund')->__('Crowdfund Import Orders ');
    }



   

}
