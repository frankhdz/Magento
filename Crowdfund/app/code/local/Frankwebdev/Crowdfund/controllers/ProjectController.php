<?php
class Frankwebdev_Crowdfund_ProjectController extends Mage_Core_Controller_Front_Action{
   







    public function indexAction() {
    Mage::log('PROJECT INDEX'); 
    $crowdfundconfig =  Mage::getStoreConfig('crowdfundsettings'); 
	  if($crowdfundconfig['config']['enable_module']){
    
    $this->getRequest()->getParams();
    $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Project"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));
      $breadcrumbs->addCrumb("projects", array(
                "label" => $this->__("Projects"),
                "title" => $this->__("Projects"),
                "link"  => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).Mage::app()->getRequest()->getModuleName()
       ));

      $breadcrumbs->addCrumb("project", array(
                "label" => $this->__("Project"),
                "title" => $this->__("Project")
		   ));

      $this->renderLayout();

      } 
	  
    }









    public function supportAction() {
    
    $crowdfundconfig =  Mage::getStoreConfig('crowdfundsettings');
    if($crowdfundconfig['config']['enable_module']){
      //module is enabled

    $this->getRequest()->getParams();
    $this->loadLayout();   
    $this->getLayout()->getBlock("head")->setTitle($this->__("Support this project"));
          $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
       ));
      $breadcrumbs->addCrumb("projects", array(
                "label" => $this->__("Projects"),
                "title" => $this->__("Projects"),
                "link"  => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).Mage::app()->getRequest()->getModuleName()
       ));

      $breadcrumbs->addCrumb("project", array(
                "label" => $this->__("Support"),
                "title" => $this->__("Support")
       ));

      // check if the action matches this project if not redirect to the correct project based on the pledge-prd sku
      $_project_id = $this->getRequest()->getParam('id');
     
      $_items = Mage::getSingleton('checkout/session')->getQuote()->getAllVisibleItems();
      foreach ($_items as $item) {
          $_incartsku = $item->getSku();
          if ($_incartsku== "pledge-prd"){
            //

            Mage::log("pledge ID : ".$item->getId());
            
           /* $bundled_product = Mage::getModel('catalog/product');
            $bundled_product->load($item->getId());*/

            $_sproduct = Mage::getModel('catalog/product')->load($item->getProduct()->getId());


            $project_edit_id = $_sproduct->getResource()
              ->getAttribute('crowdfund_projectid')
              ->getFrontend()
              ->getValue($_sproduct);

              Mage::log($project_edit_id);
            if($project_edit_id != $_project_id){
              //redirect to the correct page for edit;
              $redir = Mage::getUrl('Projects/project/support')."id/".$project_edit_id;
              Mage::app()->getFrontController()->getResponse()->setRedirect($redir);
             /* exit();
              break; */

            }

            //

          }
      }

      




      $this->renderLayout();
      
      }//end module enable

    
    }

    




    public function loadBundleParent($id){

        //Mage::log($id);
        $bundled_product = Mage::getModel('catalog/product');
        $bundled_product->load($id);
        
        //$pid = $bundled_product->getOptionsIds();
       

      

        //Mage::log($params);

      return $bundled_product;
    }












    public function loadBundle($id){

        //Mage::log($id);
        $bundled_product = Mage::getModel('catalog/product');
        $bundled_product->load($id);
        //$pid = $bundled_product->getOptionsIds();
       

       $selectionCollection = $bundled_product->getTypeInstance(true)->getSelectionsCollection(
          $bundled_product->getTypeInstance(true)->getOptionsIds($bundled_product), $bundled_product
        );

       $bundled_items = array();
        foreach($selectionCollection as $option) 
        {
          $productId = $option->product_id;
          $bundled_items[$option->getOptionId()][] = $option->getSelectionId();
          $params = array('bundle_option' => $bundled_items,'qty' => 1,'product'=>$productId);

        }

        Mage::log($params);
        if(empty($params)){
          $paras = array();
        }

      return $params;
    }














    //edit project in cart
    public function editprojectincartAction(){

      $crowdfundconfig =  Mage::getStoreConfig('crowdfundsettings');
      if($crowdfundconfig['config']['enable_module']){//enable module

      //remove current pledge from cart and set a new one with the user's new data
      //get the post params
      $post_data=$this->getRequest()->getPost();

      $newPrice = str_replace("\$", "", $pledge_amount);

      //save the price in a session for the observer
      /*Mage::getSingleton('core/session')->setPledgeprice($newPrice);
      Mage::getSingleton('core/session')->setPledgename("My Pledge - ".$projectName);*/
      //let us first remove the current pledge and associated products

      //remove any product with the the is addon or is reward 
      $quote= Mage::getSingleton('checkout/session')->getQuote();
      $cartHelper = Mage::helper('checkout/cart');
      //get all items in the cart
      $items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();

      foreach($items as $item){
        $_product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());

        $is_reward = $_product
          ->getResource()
          ->getAttribute('crowdfund_isreward')
          ->getFrontend()
           ->getValue($_product);
        $is_addon  = $_product
          ->getResource()
          ->getAttribute('crowdfund_isaddon')
          ->getFrontend()
          ->getValue($_product);

        $product_type = $_product->getTypeId();     
        if ($item->getSku()== "pledge-prd"){
            //remove pledge product
          $cartHelper->getCart()->removeItem($item->getItemId())->save(); 
        }
          
        // if this is a reward?
        if($is_reward=="Yes" || $is_addon=="Yes" ){
          //check i f the pledge sku with the same project id is present
          if($foundpledge==0){
            //remove this item from the cart
            Mage::log('Pledge not in cart remove this product');
             $cartHelper->getCart()->removeItem($item->getItemId())->save(); 
          }
        }

      }//end for remove loop;

      //add the products again
      foreach($post_data as $param=>$val){
                
                switch($param){
                  case "p_amount":
                    $pledge_amount = $val;
                  break;
                  case "pid":
                    $selected_reward_product_id = $val;
                  break;

                }
              }

              
              $projectmodel = Mage::getModel('crowdfund/crowdfundmodel')->getCollection()->addFieldToFilter('id',$post_data['pledgeproject_id']);

              foreach($projectmodel as $project){
                  $projectName = $project->getData('project_name');
              }
              

              $pledge_product_id = Mage::getModel("catalog/product")->getIdBySku('pledge-prd');
              
              //check if the pledge is already in the cart if is redirect back with error message;
              $productId = $pledge_product_id;
              $quote = Mage::getSingleton('checkout/session')->getQuote();
              if ($quote->hasProductId($productId)) {
                  // Product is not in the shopping cart so 
                  // go head and show the popup.

                  Mage::getSingleton('core/session')->addNotice('You can only add one pledge per session. Please remove the pledge from your cart or complete the checkout process with this pledge');
                  //Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl(“customer/account/”));
                  $redirect_url = $this->_getRefererUrl();

              }else{


                $_product = Mage::getModel('catalog/product');
                $_product->load($pledge_product_id);
                $pledge_product_id_original_price = $_product->getPrice();
                
                $newPrice = str_replace("\$", "", $pledge_amount);

                //save the price in a session for the observer
                Mage::getSingleton('core/session')->setPledgeprice($newPrice);
                Mage::getSingleton('core/session')->setProjectId($post_data['pledgeproject_id']);
                Mage::getSingleton('core/session')->setPledgename("My Pledge - ".$projectName);


                Mage::log("PROJECT ID IN ADD CONTROLLER : " .Mage::getSingleton('core/session')->getProjectId());

               $product = Mage::getModel('catalog/product');
               $product->load($pledge_product_id);
              // $product->setIsSuperMode(true);
               $product->setCrowdfundProjectid($post_data['pledgeproject_id']);
                              

               Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
               //$product->save();
               Mage::app()->setCurrentStore(Mage_Core_Model_App::DISTRO_STORE_ID);
                 
                


                $cart = Mage::getSingleton('checkout/cart');
                $cart->addProduct($product, array('qty' => 1));
               
/**/  
                //add reward if one is selected;
                Mage::log("SELECTED PRODUCT ID : ".$selected_reward_product_id);
                
                if( $selected_reward_product_id!="no_reward"){
                  //add the selected reward product to the cart;
                    Mage::log("F PRODUCT : ");
                    Mage::log($product->getSku());
                   

                    //check reward product
                    $rewardproduct = Mage::getModel('catalog/product')->load($selected_reward_product_id);

                    Mage::log('ptype : '.$rewardproduct->getTypeId());
                    Mage::log('SKU! : '.$rewardproduct->getSku());

                    //$fproduct = Mage::getModel('catalog/product');
                     //$fproduct = $product->load($selected_reward_product_id);
                    // Mage::log());
                     //
                    if($rewardproduct->getTypeId()==='bundle'){

                      $bundleparent = $this->loadBundleParent($selected_reward_product_id);
                      $bundle_options = $this->loadBundle($selected_reward_product_id);

                      $cart->addProduct($bundleparent, $bundle_options);
                    }

                      //$cart->addProduct($product, array('qty' => 1));// if we need to add other product types this is where we do it
                    if($rewardproduct->getTypeId()==='virtual' || $rewardproduct->getTypeId()==="simple"){
                      Mage::log("Let's add this simple or virutal to the cart");
                      if($rewardproduct->getSku()!=='pledge-prd'){
                        Mage::log("! : ".$product->getSku());
                        $cart->addProduct($rewardproduct,array('qty'=>1));
                      }
                    }
                   
                }
                //are there any addons selected?
                if(isset($post_data['addonid']) || is_array($post_data['addonid'])){

                  //parse addon data
                  foreach($post_data['addonid'] as $addonid){
                    $addonproduct=Mage::getModel('catalog/product')->load($addonid);

                    //use the observer to add project id and special pricing calculations. use the pledge as a credit system
                    $cart->addProduct($addonproduct,array('qty'=>1));

                  }


                }
               


                $cart->save();



                $redirect_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK)."checkout/onepage/";
              }


      //end adding products

      //$redirect_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK)."checkout/onepage/";
      $this->_redirectUrl($redirect_url); 
      $this->setFlag('', self::FLAG_NO_DISPATCH, true);  
      return $this;

      }//end enable module
    }
















    //start addons
    public function addonsAction(){
      
      Mage::getSingleton('core/session', array('name' => 'frontend'));
      $sessionCustomer = Mage::getSingleton("customer/session");

      if(!$sessionCustomer->isLoggedIn()) {
        
        $this->_redirect(Mage::getBaseUrl());
        
      } 


      //$post_data=$this->getRequest()->getPost();
      $crowdfundconfig =  Mage::getStoreConfig('crowdfundsettings');
      if($crowdfundconfig['config']['enable_module']){
      
        $this->getRequest()->getParams();
        $this->loadLayout();   
        $this->getLayout()->getBlock("head")->setTitle($this->__("Select Add Ons"));
            $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
        $breadcrumbs->addCrumb("home", array(
                  "label" => $this->__("Home Page"),
                  "title" => $this->__("Home Page"),
                  "link"  => Mage::getBaseUrl()
         ));
        $breadcrumbs->addCrumb("projects", array(
                  "label" => $this->__("Projects"),
                  "title" => $this->__("Projects"),
                  "link"  => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).Mage::app()->getRequest()->getModuleName()
         ));

       /* $breadcrumbs->addCrumb("project", array(
                  "label" => $this->__("Support"),
                  "title" => $this->__("Support")
                  "link"  => Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).Mage::app()->getRequest()->getModuleName()
         ));*/
         $breadcrumbs->addCrumb("project", array(
                  "label" => $this->__("Addons"),
                  "title" => $this->__("Addons")
         ));

        // check if the action matches this project if not redirect to the correct project based on the pledge-prd sku
        $_project_id = $this->getRequest()->getParam('id');
       
        $_items = Mage::getSingleton('checkout/session')->getQuote()->getAllVisibleItems();
        foreach ($_items as $item) {
            $_incartsku = $item->getSku();
            if ($_incartsku== "pledge-prd"){
              //

              Mage::log("pledge ID : ".$item->getId());
              
             /* $bundled_product = Mage::getModel('catalog/product');
              $bundled_product->load($item->getId());*/

              $_sproduct = Mage::getModel('catalog/product')->load($item->getProduct()->getId());


              $project_edit_id = $_sproduct->getResource()
                ->getAttribute('crowdfund_projectid')
                ->getFrontend()
                ->getValue($_sproduct);

                Mage::log($project_edit_id);
              if($project_edit_id != $_project_id){
                //redirect to the correct page for edit;
                $redir = Mage::getUrl('Projects/project/support')."id/".$project_edit_id;
                Mage::app()->getFrontController()->getResponse()->setRedirect($redir);
               /* exit();
                break; */

              }

              //

            }
        }
        $this->renderLayout(); 
        //end addons
      }//end is module enabled
    }















    public function addaddonstocartAction(){
      $crowdfundconfig =  Mage::getStoreConfig('crowdfundsettings');
     // $quote = Mage::getSingleton('checkout/session')->getQuote();
      $cartHelper = Mage::helper('checkout/cart');
      $cart = Mage::getSingleton('checkout/cart');
      $crowdfund_items = array();
       $update = 0;
      if($crowdfundconfig['config']['enable_module']){

        //get the post data
        $post_data=$this->getRequest()->getPost();
        $addons = array();
        Mage::log($post_data);

        //clear the cart with any previous project data
        foreach($post_data as $post=>$val){
          
          Mage::log($post);
          //Mage::log(strpos($post,'addon_qty_'));
          if (strpos($post,'addon_qty_') !== false) {
            $exp =preg_split('/\D/', $post,NULL, PREG_SPLIT_NO_EMPTY);
           
            $addons[$exp[0]] = $val;
            
          }
        }

        //remove previous items and re add them
        $cartitems = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
        Mage::log($addons);

        foreach($cartitems as $item){
          //$_product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());
          Mage::log("c item ; ".$item->getProduct()->getId());
          
          foreach($addons as $addon=>$qty){
            Mage::log($addon." ".$item->getProduct()->getId());
            if($item->getProduct()->getId()== $addon){
             // $_item = $quote->getItemByProduct($_product)->getId();
             Mage::log('MATCH UPDATE IT');
              if($qty>0){
                $item->setQty($qty);
                unset($addons[$addon]); 
              }

              if($qty<=0){
                $cartHelper->getCart()->removeItem($item->getItemId())->save();
                unset($addons[$addon]);  
              }


              $update = 0;
            }
          }
            
        
      }
      

      //end for update loop;

      //add the product it does not exist in the cart
      if(!$update){
        Mage::getSingleton('core/session')->setProjectId($post_data['pledgeproject_id']);
        
        
        foreach($addons as $addon => $qty){
           Mage::log("XXC ID : ".$addon." = ".$qty);
           $crowdfund_items[] = $addon; 
          
          $product = Mage::getModel('catalog/product');
          $product->load($addon);
            // $product->setIsSuperMode(true);
            //$product->setCrowdfundProjectid($post_data['pledgeproject_id'])->save();


           $cart->addProduct($product, array('qty' => $qty));
            



        }

        
      }

      }//end check is module front end enabled
      
      $cart->save();

      $cartitems = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
      foreach($cartitems as $item){
        Mage::log("DEBUG ATTR");
          if(in_array($item->getProduct()->getId(), $crowdfund_items)){
            Mage::log("ADDING PROJECT ID TO QUOTE ITEM : ".$item->getProduct()->getId());
            $_product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());  
            $item->setCrowdfundProjectid($post_data['pledgeproject_id']);
          }
      }
      //redirect to cart
      $redirect_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK)."checkout/cart/";
      $this->_redirectUrl($redirect_url); 
      $this->setFlag('', self::FLAG_NO_DISPATCH, true);  
      return $this;
    }



















    public function addprojecttocartAction(){

        $crowdfundconfig =  Mage::getStoreConfig('crowdfundsettings');
        if($crowdfundconfig['config']['enable_module']){

        //get the post params
        $post_data=$this->getRequest()->getPost();
        Mage::log('<ADD TO CART POST>');
        Mage::log($post_data);
        //if ($post_data) {
        Mage::log('</ADD TO CART POST>');
                  

              foreach($post_data as $param=>$val){
                
                switch($param){
                  case "p_amount":
                    $pledge_amount = $val;
                  break;
                  case "pid":
                    $selected_reward_product_id = $val;
                  break;

                }
              }
              //get the quantity
              $addons = array();
              foreach($post_data as $post=>$val){
          
                Mage::log($post);
                //Mage::log(strpos($post,'addon_qty_'));
                if (strpos($post,'addon_qty_') !== false) {
                  $exp =preg_split('/\D/', $post,NULL, PREG_SPLIT_NO_EMPTY);
                 
                  $addons[$exp[0]] = $val;
                  
                }
              }

              $crowdfund_items = array();
              $projectmodel = Mage::getModel('crowdfund/crowdfundmodel')->getCollection()->addFieldToFilter('id',$post_data['pledgeproject_id']);

              foreach($projectmodel as $project){
                  $projectName = $project->getData('project_name');
              }
              

              $pledge_product_id = Mage::getModel("catalog/product")->getIdBySku('pledge-prd');
              
              //check if the pledge is already in the cart if is redirect back with error message;
              $productId = $pledge_product_id;
              $quote = Mage::getSingleton('checkout/session')->getQuote();
              if ($quote->hasProductId($productId)) {
                  // Product is not in the shopping cart so 
                  // go head and show the popup.

                  Mage::getSingleton('core/session')->addNotice('You can only add one pledge per session. Please remove the pledge from your cart or complete the checkout process with this pledge');
                  //Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl(“customer/account/”));
                  $redirect_url = $this->_getRefererUrl();

              }else{


                $_product = Mage::getModel('catalog/product');
                $_product->load($pledge_product_id);
                $pledge_product_id_original_price = $_product->getPrice();
                
                $newPrice = str_replace("\$", "", $pledge_amount);

                //save the price in a session for the observer
                Mage::getSingleton('core/session')->setPledgeprice($newPrice);
                Mage::getSingleton('core/session')->setProjectId($post_data['pledgeproject_id']);
                Mage::getSingleton('core/session')->setPledgename("My Pledge - ".$projectName);


               // Mage::log("PROJECT ID IN ADD CONTROLLER : " .Mage::getSingleton('core/session')->getProjectId());

               $product = Mage::getModel('catalog/product');
               $product->load($pledge_product_id);

               $crowdfund_items[] = $pledge_product_id;
              // $product->setIsSuperMode(true);
               $product->setCrowdfundProjectid($post_data['pledgeproject_id']);
                              

               Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
               //$product->save();
               Mage::app()->setCurrentStore(Mage_Core_Model_App::DISTRO_STORE_ID);
                 
                


                $cart = Mage::getSingleton('checkout/cart');
                $cart->addProduct($product, array('qty' => 1));
               
/**/  
                //add reward if one is selected;
                Mage::log("SELECTED PRODUCT ID : ".$selected_reward_product_id);
                
                if( $selected_reward_product_id!="no_reward"){
                  //add the selected reward product to the cart;
                  //  Mage::log("F PRODUCT : ");
                  //  Mage::log($product->getSku());
                   

                    //check reward product
                    $rewardproduct = Mage::getModel('catalog/product')->load($selected_reward_product_id);

                  //  Mage::log('ptype : '.$rewardproduct->getTypeId());
                  //  Mage::log('SKU! : '.$rewardproduct->getSku());

                    //$fproduct = Mage::getModel('catalog/product');
                     //$fproduct = $product->load($selected_reward_product_id);
                    // Mage::log());
                     //
                    if($rewardproduct->getTypeId()==='bundle'){

                      $bundleparent = $this->loadBundleParent($selected_reward_product_id);
                      $bundle_options = $this->loadBundle($selected_reward_product_id);
                      $crowdfund_items[] = $selected_reward_product_id;
                      $cart->addProduct($bundleparent, $bundle_options);
                    }

                      //$cart->addProduct($product, array('qty' => 1));// if we need to add other product types this is where we do it
                    if($rewardproduct->getTypeId()==='virtual' || $rewardproduct->getTypeId()==="simple"){
                      Mage::log("Let's add this simple or virutal to the cart");
                      if($rewardproduct->getSku()!=='pledge-prd'){
                        Mage::log("! : ".$product->getSku());
                         $crowdfund_items[] = $rewardproduct->getId();
                        $cart->addProduct($rewardproduct,array('qty'=>1));
                      }
                    }
                   
                }
                //are there any addons selected?
                Mage::log("ADDNIG TO CART INIT : ");
                Mage::log($addons);
                Mage::log($post_data['addonid']);
                if(isset($post_data['addonid']) || is_array($post_data['addonid'])){

                  //parse addon data
                  foreach($post_data['addonid'] as $addonid){
                    
                    $_qty = 1;
                    $addonproduct=Mage::getModel('catalog/product')->load($addonid);
                    $crowdfund_items[] = $addonid;
                    //get the quantity
                    foreach($addons as $addon=>$qty){
                      
                      if($addonid == $addon){
                        $_qty = $qty;
                      }

                    }

                    //use the observer to add project id and special pricing calculations. use the pledge as a credit system
                    $cart->addProduct($addonproduct,array('qty'=>$_qty));

                  }


                }
               


                $cart->save();

                //add project id data to crowdfund items;
                $cartitems = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
                foreach($cartitems as $item){
                 // Mage::log("DEBUG ATTR");
                  if(in_array($item->getProduct()->getId(),$crowdfund_items)){
                    Mage::log("ADDING PRODUCY ID ATTRIBUTE : ".$item->getProduct()->getId());
                    $_product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());  
                    $item->setCrowdfundProjectid($post_data['pledgeproject_id']);
                  }
                }





                $redirect_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK)."checkout/onepage/";
              }
             
             Mage::log($pledge_amount." ".$selected_reward_product_id);


      //after adding the products redirect to the cart checkout/onepage/
     // $this->_redirectUrl(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK)."checkout/cart/");  
      $this->_redirectUrl($redirect_url); 
      $this->setFlag('', self::FLAG_NO_DISPATCH, true);  
      return $this;
    }

    }//end enable module

   
}