<?php

class Frankwebdev_Crowdfund_Model_Observer
{
	
	public function __construct()
    {
    }
    public function paymentMethodIsActive($observer){
    	/*$instance = $observer->getMethodInstance();
        $result = $observer->getResult();

       




        if ($instance->getCode() == "kickstarter") {
            if(!Mage::app()->getStore()->isAdmin()){
                $result->isAvailable = true;
            }
            else{
                $result->isAvailable = true;
            }
        }*/
        /*Mage::log('HIDE CHECKMO');
        $instance = $observer->getMethodInstance();
        Mage::log($instance->getCode());
        $result = $observer->getResult();

        if ($instance->getCode() == "checkmo") {
            if (Mage::app()->getStore()->isAdmin()) {
            	Mage::log('IS ADMIN SHOW CHECKMO');
                $result->isAvailable = true;
            } else {
            	Mage::log('IS NOT ADMIN SHOW CHECKMO');
                $result->isAvailable = false;
            }
        }*/

    }
	public function setpledgefinalprice($observer){
		

		  $event = $observer->getEvent();
	       $product = $event->getProduct();
	      Mage::log($product->getSku());
	    if($product->getSku()=="pledge-prd"){
	      	Mage::log("I SAW IT!");
	      	$product->setFinalPrice(Mage::getSingleton('core/session')->getPledgeprice());
	      	$product->setName(Mage::getSingleton('core/session')->getPledgename());
	      	Mage::log($product->getCrowdfundProjectid());
	      	Mage::log(Mage::getSingleton('core/session')->getProjectId());
	      	//add attribute values 
		}
		//check if the reward belongs to this project -  append name from project
		


	 // // ADDD LOGIC HERE to get price added by customer

	 //    $product->setFinalPrice($specialPrice); // set the product final price
	    
	   // return $this;

	}

	public function savetoreport($observer){

		Mage::log('SAVE TO REPORT OBSERVER');
		$event = $observer->getEvent();

		//$products = $event->get
		$order = new Mage_Sales_Model_Order();
	    $incrementId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
	    $order->loadByIncrementId($incrementId);
		$items = $order->getAllVisibleItems();
	    

	    $order_id = $order->getId();//use this id to add to the
	    $customer_email = $order->getCustomerEmail(); 
	   
	   Mage::Log("CUSTOMER EMAIL : ".$customer_email);


		

		//$customer_id = $customer->getId();
		//get the store id
		$store_id=$order->getStoreId();;



	    Mage::log($order_id);
	   //Mage::Log($items);
	    foreach($items as $item){
	    	Mage::log($item->getSku());
	    	$_product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());
	    	
	    	//get product id
	    	$is_crowdfund = $_product
		    				->getResource()
						 	->getAttribute('crowdfund_isreward')
						 	->getFrontend()
						 	->getValue($_product);
	    	
	    		//add to the crowdfun products ordered table
			$is_addon  = $_product
		    				->getResource()
						 	->getAttribute('crowdfund_isaddon')
						 	->getFrontend()
						 	->getValue($_product);

			
			
			



			if($item->getSku() == "pledge-prd"){ //if it is the pledge product add it tp th reporting table

				
			    //save order data
			    $project_id = Mage::getSingleton('core/session')->getProjectId();
				$ordersmodel = Mage::getModel("crowdfund/orders");


				Mage::log('PROJECT ID OBSERVER : '. $project_id);
			    $ordersmodel->setEntity($order_id);//this is the order ID 
			    Mage::log('INSERT CUSTOMER EMAIL : '.$customer_email);
			    $customer_arr = array("customer_email"=>$customer_email);

			    $ordersmodel->addData($customer_arr);//the customer ID loaded by email above
			    
			    Mage::log($_product->getFinalPrice());
			    $ordersmodel->setProjectId($project_id);//the project ID
			    $ordersmodel->setWebsiteId($store_id);//the store/website ID
				$ordersmodel->save();

				//save product data
				$productsordered = Mage::getModel('crowdfund/customerproducts');
				$productsordered->setOrderId($order_id);
				$productsordered->setCustomerEmail($customer_email);
				$productsordered->setProductId($_product->getId());
				$productsordered->setPrice($_product->getFinalPrice());
				$productsordered->setProjectId($project_id);	
				$productsordered->save();

				//save "credit info to table"
				/*$creditmodel = Mage::getModel("crowdfund/customercredit");
				//date and date modified						
				$currentTimestamp = Mage::getModel('core/date')->timestamp(time());
				//first let's check if we are updating or inserting new
				$cc_cusotmer = $creditmodel->addFieldToFilter('customer_email','frank@frankhdesign.com');*/


			}

			if($is_addon=="Yes"){

				$productsordered = Mage::getModel('crowdfund/customerproducts');
				$productsordered->setOrderId($order_id);
				$productsordered->setCustomerEmail($customer_email);
				$productsordered->setProductId($_product->getId());
				$productsordered->setPrice($_product->getFinalPrice());
				$productsordered->setProjectId( $project_id);		
				$productsordered->save();
			}


			if($is_crowdfund=="Yes"){
				// add it to the reporting table
				//save product data
				$productsordered = Mage::getModel('crowdfund/customerproducts');
				$productsordered->setOrderId($order_id);
				$productsordered->setCustomerEmail($customer_email);
				$productsordered->setProductId($_product->getId());
				$productsordered->setPrice($_product->getFinalPrice());
				$productsordered->setProjectId( $project_id);		
				$productsordered->save();

				//get bundle children
				
						 //get the product's children
				$children = $_product->getTypeInstance(true)->getChildrenIds($_product->getId(), false);
							 			//Mage::log($children);
				if($_product->getTypeId()==='bundle'){

				
					foreach($children as $child=>$val):
						

						Mage::log('PRODUCT IS BUNDLE AT CHECK OUT : '.$val);
						$id_val = array_values($val);
						//Mage::log($id_val[0]);
						//get child description
						$child_product = Mage::getModel('catalog/product')->load($id_val);
						$child_product->getName();

						$productchildrensordered = Mage::getModel('crowdfund/customerproducts');
						
						$options = Mage::getModel('bundle/option')->getResourceCollection()
                                              ->setProductIdFilter($child_product->getId())
                                              ->setPositionOrder(); 


						$productchildrensordered->setOrderId($order_id);
						$productchildrensordered->setCustomerEmail($customer_email);
						$productchildrensordered->setProductId($child_product->getId());
						$productchildrensordered->setPrice($child_product->getFinalPrice());
						$productchildrensordered->setQty($options->getSelectionQty());
						$productchildrensordered->setProjectId( $project_id);			
						$productchildrensordered->save();

					endforeach;
				}


			}
	    	Mage::Log("ATTR : ".$is_crowdfund."  -  ".$is_addon."   -  ".$customer_email." - ".$project_id);

	    	//check if the 
		}

		//add order id to table
	    

		//add data to credit history and credit table
		//start credit model
		$creditmodel = Mage::getModel("crowdfund/customercredit")->getCollection();
		$credithistorymodel = Mage::getModel('crowdfund/customercredithistory')->getCollection();

		//date and date modified						
		
		//first let's check if we are updating or inserting new
		$creditmodel->addFieldToFilter('customer_email',$customer_email);
		$credithistorymodel->addFieldToFilter('order_id',$order_id);

				
		$creditamount = Mage::getSingleton('checkout/session')->getCustomerCredit();
		//$project_id = 1;
		if(count($creditmodel)>0){
			Mage::log("EMAIL EXISTS?");
			foreach($creditmodel as $item){
				Mage::log($item->getCustomerEmail());
				$id = $item->getId();
			}

			//let's do a new insertion
			Mage::helper('crowdfund')->updatecreditrecord($customer_email,$creditamount,$project_id,$order_id);
			
		}else{
			//let's update the record
			Mage::log("!EMAIL EXISTS?");
			if(count($credithistorymodel)<1){
				//only run this one time per order to avoid duplicates
				Mage::helper('crowdfund')->addnewcreditrecord($customer_email,$creditamount,$project_id,$order_id);
			}
		}


		//end credit model

		//Mage::log(get_class_methods($event));
		/*$product = $event->getProduct();*/
		//get the crowdfund attributes



	}
	private function getIsCrowdFundReward($sku){

		$product = Mage::getModel('catalog/product');
		$id = Mage::getModel('catalog/product')->getResource()->getIdBySku($sku);
		//;
		$product->load($id);
		Mage::log($product);
		
		/*$isreward = $product
					->getResource()
					->getAttribute('crowdfund_isreward')
					->getFrontend()
					->getValue($product);
*/

		/*if($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE){
			
		}*/
		//Mage::log("CART PRODUCT IDS : ".$product);


	}




















	public function checkforpledge(){
		//remove pledge bundles if the pledge is missing
		Mage::log("checkforpledge");

		$quote= Mage::getSingleton('checkout/session')->getQuote();
		//get all items in the cart
		$items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
		$cartHelper = Mage::helper('checkout/cart');
		$cartItems = $quote->getAllVisibleItems();

		$bundleed_product_model = Mage::getModel('bundle/product_type');

		//check if user already pledged and is simply returning to buy add ons if true do not remove pledge
		$no_addons = 0;
		 $session = Mage::getSingleton('customer/session');
		 $customer_data = Mage::getSingleton('customer/session')->getCustomer();
		if($session->isLoggedIn()){
			Mage::log("USER IS LOGGED IN. CHECK IF THEY CAN PURCHASE THIS ADDON");
			Mage::log($customer_data->getEmail());
			//get the project id from the addon
			foreach($items as $item){
				Mage::log('ITEM OBS');
				$p_id = Mage::getModel('catalog/product')->load($item->getProduct()->getId())->getCrowdfundProjectid();
				
				Mage::log("p_id : ".$p_id);

			}

			$crowdfundprojectmodel = Mage::getModel('crowdfund/customercredit')->getCollection()
			->addFieldToFilter('customer_email',$customer_data->getEmail())
			->addFieldToFilter('project_id',1);
			;

			$count_projects = count($crowdfundprojectmodel);
			if($count_projects>0){
				$no_addons = 1;
			}


		}
		

		//end returning user

		//$bundleedParentIds = $bundleed_product_model->getParentIdsByChild($_product->getId());
		if($no_addons==0){
			$foundpledge = 0;
			foreach ($items as $item) {
				Mage::log($item->getSku());
				if ($item->getSku()== "pledge-prd"){
					$foundpledge = 1;
					break;/**/
				}
			}


			foreach ($cartItems as $item) {
			    $_product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());
			   /* $product_cart_id = $item->getId();
			    $product_sku = $item->getSku();*/
			    $product_type = $_product->getTypeId();
			    
			    $pid = $_product
			    				->getResource()
							 	->getAttribute('crowdfund_projectid')
							 	->getFrontend()
							 	->getValue($_product);

					 Mage::log('PID : '.$pid);


			    //Mage::log('OPT : ');
			   
			    Mage::log($product_type);
			    if($product_type==="bundle" || $product_type==="simple"||$product_type==="virtual"){
					
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
					
					 // if this is a reward?
					if($is_reward=="Yes" || $is_addon=="Yes" ){
						//check i f the pledge sku with the same project id is present
						if($foundpledge==0){
							//remove this item from the cart
							Mage::log('Pledge not in cart remove this product');
							 $cartHelper->getCart()->removeItem($item->getItemId())->save(); 
						}


						//end product check

					}



			    }
			}//end check to delete addons
			    

			    //Mage::log($product_sku);
			    //Mage::log($this->getIsCrowdFundReward($product_sku));

			}

		Mage::log('END CHECK FOR PLEDGE');


	}















	public function isallowedtoback(){
		Mage::log('is allowed to back',null,"crowdfund.log");
		//if the cannot back the project multiple times redirect to message page
		 $crowdfundconfig =  Mage::getStoreConfig('crowdfundsettings');
		 
		 if($crowdfundconfig['config']['can_back_multiple']==0){//from system config
		 	//get the user:
		 	$user = Mage::getSingleton('customer/session')->getCustomer();
		 	
		 	Mage::log('USER ID : '.$user->getEmail() ,null,"crowdfund.log");
		 	//check if this project has already been backed
		 	$crowdfundbackedcollection = Mage::getModel('crowdfund/orders')->getCollection();
		 	$crowdfundbackedcollection->addFieldToFilter('customer_email', $user->getEmail());

		 	
		 	$backedcount = count($crowdfundbackedcollection);
		 	Mage::log('Backed Amount : '.$backedcount,null,"crowdfund.log");
		if($backedcount>0){



				Mage::log('remove pledges',null,"crowdfund.log");
				 	//remove the pledge product from the cart at log in
				$quote= Mage::getSingleton('checkout/session')->getQuote();
					//get all items in the cart
				$items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
				$cartHelper = Mage::helper('checkout/cart');
				$cartItems = $quote->getAllVisibleItems();


				$foundpledge = 0;
				foreach ($items as $item) {
					Mage::log($item->getSku(),null,"crowdfund.log");
					if ($item->getSku()== "pledge-prd"){
						$foundpledge = 1;

						//break;/**/
					}
				}


				foreach ($cartItems as $item) {
					Mage::log('LOG IN/ FOR EACH ',null,"crowdfund.log");
				    $_product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());
				   /* $product_cart_id = $item->getId();
				    $product_sku = $item->getSku();*/
				    $product_type = $_product->getTypeId();
				    
				    $pid = $_product
				    				->getResource()
								 	->getAttribute('crowdfund_projectid')
								 	->getFrontend()
								 	->getValue($_product);
					$isreward = $_product
				    				->getResource()
								 	->getAttribute('crowdfund_isreward')
								 	->getFrontend()
								 	->getValue($_product);

					Mage::log('LOGIN IS REWARD???? : '.$isreward." ".$_product->getSku());
					//remove pledge from cart
					Mage::log('LOG IN/ '.$_product->getSku(),null,"crowdfund.log");
					if($_product->getSku()=="pledge-prd" || $isreward=="Yes" ){
						Mage::log('LOG IN FOUND PLEDGE ',null,"crowdfund.log");
						Mage::log('ITEM ID : '.$item->getItemId(),null,'crowdfund.log');
						 $cartHelper->getCart()->removeItem($item->getItemId())->save(); 
					}
					//remove any rewards related to this product if logging in with a project in the cart



				}



				Mage::log('ABOUT TO CHECK IN ITEMS IN CART',null,"crowdfund.log");
				foreach ($cartItems as $item) {
				    $_product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());
				   /* $product_cart_id = $item->getId();
				    $product_sku = $item->getSku();*/
				   

					//remove pledge from cart
					Mage::log('IN CART : '.$_product->getSku(),null,"crowdfund.log");
					


				}




				if($foundpledge == 1){
					//redirect to account page and add message
					//Mage::app()->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('error' => -1, 'message' => $this->__('You have already backed this project'))));
					Mage::getSingleton('checkout/session')->addSuccess('You have already backed this project. BUt you can still purchase add ons!');


					/*$this->_redirectUrl(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK)."customer/account/"); 
				    $this->setFlag('', self::FLAG_NO_DISPATCH, true);*/  
				   
				   Mage::app()->getResponse()
					   ->setRedirect(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK)."customer/account/")
					   ->sendResponse();

				}


			}

		}//end system config 


	}



















	public function applycredit($observer){
		//Mage::log('APPLY PLEDGE AS CREDIT OBSERVER',null,"crowdfund.log");
		//add up the total prices of the add ons, the difference is the extra amount the customer pays
		$quote = $observer->getEvent()->getQuote();
		$total=$quote->getBaseSubtotal();
		$quoteid=$quote->getId();
		//get the pledge amount;
		$items = $quote->getAllItems();
		$discountAmount = 0;
		$credit = 0;
		$addon_prices = array();
		foreach($items as $item){
			if($item->getSku()=="pledge-prd"){
				$pledge_price = $item->getPrice();

			}
			

			$_product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());
			$is_addon  = $_product
		    				->getResource()
						 	->getAttribute('crowdfund_isaddon')
						 	->getFrontend()
						 	->getValue($_product);
			if($is_addon=="Yes"){
				//Mage::log('addonprice : '.($_product->getPrice())*$item->getQty());
				Mage::log("IS ADDON YES >");
				$addon_prices[] = $_product->getPrice()*$item->getQty();
				$project_id = $_product->getCrowdfundProjectid();
				;
				/*$_product
						 	 	->getResource()
						 	 	->getAttribute('crowdfund_projectid')
						 	 	->getFrontend()
						 	 	->getValue($_product);*/
				
				Mage::log("FROM CART PROJECT ID : ".$project_id);
			}
		}
	//	Mage::log("PLEDGE PRICE : ".$pledge_price,null,"crowdfund.log");
	//	Mage::log('Start discount calculation',null,"crowdfund.log");
		$addons = array_sum($addon_prices);

		if(isset($project_id)){
			if(!isset($pledge_price)){
				Mage::log("BLANK PLEDGE PRICE RETRIEVE CREDIT");
				//get the customer email is logged in
				$user = Mage::getSingleton('customer/session')->getCustomer();


				
				//lets get the credit amount if this is a return purchase
				$creditmodel = Mage::getModel('crowdfund/customercredit')->getCollection()
				->addFieldToFilter('customer_email',$user->getEmail())
				->addFieldToFilter('project_id',$project_id)
				;

				foreach($creditmodel as $credit){
					$pledge_price = $credit->getData('credit_amount');
				}

			}
			
			if($pledge_price>$addons){//remainder is a credit/discount
				Mage::log('PLEDGE IS HIGHER');
				$credit = $pledge_price-$addons;
				$discountAmount = $addons;

			}
			if($pledge_price<$addons){//remainder is a credit/discount
		//		Mage::log('PLEDGE IS HIGHER',null,"crowdfund.log");
				$credit = 0;
				$discountAmount = $pledge_price;

			}
		}

	//	Mage::log("DISCOUNT AMOUNT : ".$discountAmount,null,"crowdfund.log");
	//	Mage::log("CREDIT AMOUNT : ".$credit,null,"crowdfund.log");
		//set credit as a session variable to insert in credit table for later use
		 Mage::getSingleton('checkout/session')->setCustomerCredit($credit);

		//apply discount
		



		if($quoteid) {
       
       
      
        if($discountAmount>0) {
          $total=$quote->getBaseSubtotal();
          $quote->setSubtotal(0);
          $quote->setBaseSubtotal(0);

          $quote->setSubtotalWithDiscount(0);
          $quote->setBaseSubtotalWithDiscount(0);

          $quote->setGrandTotal(0);
          $quote->setBaseGrandTotal(0);
  
    
          $canAddItems = $quote->isVirtual()? ('billing') : ('shipping'); 
          foreach ($quote->getAllAddresses() as $address) {
    
            $address->setSubtotal(0);
            $address->setBaseSubtotal(0);

            $address->setGrandTotal(0);
            $address->setBaseGrandTotal(0);

            $address->collectTotals();

            $quote->setSubtotal((float) $quote->getSubtotal() + $address->getSubtotal());
            $quote->setBaseSubtotal((float) $quote->getBaseSubtotal() + $address->getBaseSubtotal());

            $quote->setSubtotalWithDiscount(
                (float) $quote->getSubtotalWithDiscount() + $address->getSubtotalWithDiscount()
            );
            $quote->setBaseSubtotalWithDiscount(
                (float) $quote->getBaseSubtotalWithDiscount() + $address->getBaseSubtotalWithDiscount()
            );

            $quote->setGrandTotal((float) $quote->getGrandTotal() + $address->getGrandTotal());
            $quote->setBaseGrandTotal((float) $quote->getBaseGrandTotal() + $address->getBaseGrandTotal());
 
            $quote ->save(); 
 
            $quote->setGrandTotal($quote->getBaseSubtotal()-$discountAmount)
            ->setBaseGrandTotal($quote->getBaseSubtotal()-$discountAmount)
            ->setSubtotalWithDiscount($quote->getBaseSubtotal()-$discountAmount)
            ->setBaseSubtotalWithDiscount($quote->getBaseSubtotal()-$discountAmount)
            ->save(); 
      
    
            if($address->getAddressType()==$canAddItems) {
            //echo $address->setDiscountAmount; exit;
             $address->setSubtotalWithDiscount((float) $address->getSubtotalWithDiscount()-$discountAmount);
             $address->setGrandTotal((float) $address->getGrandTotal()-$discountAmount);
             $address->setBaseSubtotalWithDiscount((float) $address->getBaseSubtotalWithDiscount()-$discountAmount);
             $address->setBaseGrandTotal((float) $address->getBaseGrandTotal()-$discountAmount);
             if($address->getDiscountDescription()){
             $address->setDiscountAmount(-($address->getDiscountAmount()-$discountAmount));
             $address->setDiscountDescription($address->getDiscountDescription().', Addon Credit[Not a discount]');
             $address->setBaseDiscountAmount(-($address->getBaseDiscountAmount()-$discountAmount));
             }else {
             $address->setDiscountAmount(-($discountAmount));
             $address->setDiscountDescription('Addon Credit[Not a discount]');
             $address->setBaseDiscountAmount(-($discountAmount));
             }
              $address->save();
              }//end: if
          } //end: foreach
   //echo $quote->getGrandTotal();
  
          foreach($quote->getAllItems() as $item){
                 //We apply discount amount based on the ratio between the GrandTotal and the RowTotal
			$_addonproduct = Mage::getModel('catalog/product')->load($item->getProduct()->getId());
            $is_addon  = $_addonproduct
		    			->getResource()
					 	->getAttribute('crowdfund_isaddon')
					 	->getFrontend()
					 	->getValue($_addonproduct);

			if($is_addon=="Yes"){
				Mage::log('DISC',null,"crowdfund.log");
                 $rat=$item->getPriceInclTax()/$total;
                 $ratdisc=$discountAmount*$rat;
                 $item->setDiscountAmount(($item->getDiscountAmount()+$ratdisc) * $item->getQty());
                 $item->setBaseDiscountAmount(($item->getBaseDiscountAmount()+$ratdisc) * $item->getQty())->save();
            }
                
        }
            
                
        }
            
    }






    	




		
                //end apply discount


	}

}