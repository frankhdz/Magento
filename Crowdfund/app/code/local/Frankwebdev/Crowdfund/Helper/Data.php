<?php
class Frankwebdev_Crowdfund_Helper_Data extends Mage_Core_Helper_Abstract
{
	protected $_product;

	public function resizeImage($imageName, $width=NULL, $height=NULL, $imagePath=NULL){      
	   
	    $imagePath = str_replace("/", DS, $imagePath);
	    $imagePathFull = Mage::getBaseDir('media') . DS . $imagePath . DS . $imageName;
	    // Mage::log($imagePathFull);
	    /*if($width == NULL && $height == NULL) {
	        $width = 100;
	        $height = 100;
	    }*/
	    $resizePath = $width . 'x' . $height;
	    $resizePathFull = Mage::getBaseDir('media') . DS . $imagePath . DS . $resizePath . DS . $imageName;
	             
	    if (file_exists($imagePathFull) && !file_exists($resizePathFull)) {
	        try{
	         $imageObj = new Varien_Image($imagePathFull);
	        $imageObj->constrainOnly(FALSE);
	        $imageObj->keepAspectRatio(TRUE);
	        $imageObj->resize($width,$height);
	        $imageObj->keepFrame(FALSE);
	        $imageObj->save($resizePathFull);
	    	}
	    	catch (Exception $e){
			Mage::log($e);
			}
	    }
	             
	    $imagePath=str_replace(DS, "/", $imagePath);
	    return Mage::getBaseUrl("media") . $imagePath . "/" . $resizePath . "/" . $imageName;
		
		
	}

	public function resizeImageCropProjectView($imageName, $width=NULL, $height=NULL, $imagePath=NULL,$cropbottom,$gt){      
	   
	    $imagePath = str_replace("/", DS, $imagePath);
	    $imagePathFull = Mage::getBaseDir('media') . DS . $imagePath . DS . $imageName;
	    // Mage::log($imagePathFull);
	    /*if($width == NULL && $height == NULL) {
	        $width = 100;
	        $height = 100;
	    }*/
	    $resizePath = $width . 'x' . $height;
	    $resizePathFull = Mage::getBaseDir('media') . DS . $imagePath . DS . $resizePath . DS . $imageName;
	             
	    if (file_exists($imagePathFull) && !file_exists($resizePathFull)) {
	        try{
	         $imageObj = new Varien_Image($imagePathFull);
	        $imageObj->constrainOnly(FALSE);
	        $imageObj->keepAspectRatio(TRUE);
	        $imageObj->resize($width,$height);


	        
	        $imageObj->keepFrame(FALSE);
	        $imageObj->save($resizePathFull);

	        //crop the imahe
			Mage::log("open:".$resizePathFull);
	        $cropimageObj = new Varien_Image($resizePathFull);

	        $oheight = $cropimageObj->getOriginalHeight();
	        Mage::log("OHEIGHT : ".$oheight);
	        if($oheight>=$gt){
	        	
				$cropimageObj->crop(0, 0, 0, $cropbottom);//$top=0, $left=0, $right=0, $bottom=0
				$cropimageObj->save($resizePathFull);
	        }
			
	    	

	    	}
	    	catch (Exception $e){
			Mage::log($e);
			}
	    }
	             
	    $imagePath=str_replace(DS, "/", $imagePath);
	    return Mage::getBaseUrl("media") . $imagePath . "/" . $resizePath . "/" . $imageName;
		
		
	}

	public function backerscount($id){
		Mage::log('BACKERS HELPER');
		$backers = Mage::getModel('crowdfund/orders')
		->getCollection();

		$backers->getSelect()->where('project_id = '.$id)->group('customer_email');
		
		//Mage::log($backers->getSelect()->__toString());


		//$backers->addFieldToFilter('project_id',$id);

		





		return count($backers);

	}
	public function pledgedamount($project_id){

		$pledges = Mage::getModel('crowdfund/customerproducts')
		->getCollection()

		//$pledges->getSelect()
		->addFieldToFilter('project_id',$project_id)
		//->addFieldToFilter('product_id','17')

		//->where('project_id = '.$project_id.' AND product_id=17')
		//->getColumnValues('price')
		;




		Mage::log('SUM TOTAL PLEDGES');
		//Mage::log((string)$pledges->getSelect());

		//Mage::log($pledges);
		$prices=array();
		foreach($pledges as $pledge){
			$prices[]=$pledge->getPrice();
		}

		if(count($prices)==0){
			$total=0;
		}else{
			$total = array_sum($prices);

		}


		



		return $total;
	}


	public function daystogocount($enddate){
		

		$now = time(); // or your date as well
	    $your_date = strtotime($enddate);
	    $datediff = $your_date - $now;
	    $count = floor($datediff/(60*60*24));

	    if($count<0){
	    	$count=0;
	    }	

	    return $count;


	}

	public function addnewcreditrecord($email,$amount,$projectid,$orderid){
		$currentTimestamp = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));

		Mage::log('add new credit record');
		$currentTimestamp = Mage::getModel('core/date')->timestamp(time());

		$creditmodel = Mage::getModel('crowdfund/customercredit');
		$creditmodel->setCustomerEmail($email);
		$creditmodel->setProjectId($projectid);
		$creditmodel->setOrderId(0);
		$creditmodel->setCreditAmount(str_replace("$","",Mage::helper('core')->currency($amount, true, false)));
		$creditmodel->setDateCreated($currentTimestamp);
		$creditmodel->setDateModified($currentTimestamp);
		$creditmodel->save();
		//create project order history

		$credithistorymodel = Mage::getModel('crowdfund/customercredithistory');
		$credithistorymodel->setOrderId($orderid);
		$credithistorymodel->setCreditAmount($amount);
		$credithistorymodel->setDateCreated($currentTimestamp);
		$credithistorymodel->save();


	}

	public function updatecreditrecord($email,$amount,$projectid,$record_id,$orderid){
		Mage::log('update credit record');
		$currentTimestamp = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));

		$arr = array('credit_amount'=>$amount,"date_modified"=>$currentTimestamp);

		$creditmodel = Mage::getModel('crowdfund/customercredit')->load($record_id)->addData($arr);
		$creditmodel->setId($record_id)->save(); 

		$credithistorymodel = Mage::getModel('crowdfund/customercredithistory');
		$credithistorymodel->setOrderId($orderid);
		$credithistorymodel->setCreditAmount($amount);
		$credithistorymodel->setDateCreated($currentTimestamp);
		$credithistorymodel->save(); 


	}

	public function updatecreditrecordadmin($email,$amount,$projectid,$record_id,$orderid){
		Mage::log('update credit record admin');
		$currentTimestamp = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));

		//check for existing credit 
		$hascreditmodel = Mage::getModel('crowdfund/customercredit')->getCollection()
						  ->addFieldToFilter('customer_email',$email)
						  ->addFieldToFilter('project_id',$projectid);

		
		foreach($hascreditmodel as $item){
			Mage::log('CREDIT EXISTS ADD UP CREDIT');	
			$amount += $item->getData('credit_amount');
		}

		$arr = array('credit_amount'=>$amount,"date_modified"=>$currentTimestamp);

		$creditmodel = Mage::getModel('crowdfund/customercredit')->load($record_id)->addData($arr);
		$creditmodel->setId($record_id)->save(); 

		$credithistorymodel = Mage::getModel('crowdfund/customercredithistory');
		$credithistorymodel->setOrderId($orderid);
		$credithistorymodel->setCreditAmount($amount);
		$credithistorymodel->setDateCreated($currentTimestamp);
		$credithistorymodel->save(); 


	}

	public function fundingGoalPercent($id,$goal){
		
		
		//$sales = 

		//Mage::helper('core')->currency($project_goal, true, false)
		/*$percent['backers'] = strval(10);
		$percent['funding_goal'] = Mage::helper('core')->currency(strval(600), true, false);
		$percent['amount_funded'] = Mage::helper('core')->currency(strval(200), true, false);
		$percent['percentage'] = strval(50).'&#37;';*/
		$pledged = $this->pledgedamount($id);
		
		if($pledged==0){
			$percent = 0;

		}else{
			$project_goal = $goal;

			$percent = $pledged/$project_goal*100;
		}
		Mage::log("PCT : ".$percent);




		return $percent;
	}

	public function admincreateorders($orders,$project_id,$product_id,$store_id){

		$init = 0;
		//Mage::log(Mage::getModel('payment/config')->getActiveMethods());
		
		foreach($orders as $raworder){
			if($init!=0){
				$order = explode(',', $raworder);
				
				//Mage::app()->setCurrentStore($store_id);//switch to store front temporarily to simulate purchase
				$customername = $order[1];
				$email = $order[2];
				$shippingtype = $order[3];
				$pledgeamount = $order[4];
				$pledgecollected = $order[7];

				
				Mage::log('adminorder data : '.$customername."  ".$email."  ".$shippingtype."  ".$pledgeamount."  ".$pledgecollected." | projectid  ".$project_id."  ".$project_id."   ".$store_id);
				//format pledge price

				$pledgeamount = str_replace(" USD", "", $pledgeamount);
				$pledgeamount = str_replace("\$", "", $pledgeamount);


				$userpassword = $this->cfpasswordgenerator();

				Mage::getSingleton('core/session')->setPledgeprice($pledgeamount);
				//Mage::log($email);

				$customerid = $this->cfcreatecustomer($email,$userpassword,$store_id,$customername);//creates the customer and reutrns the id or simply returns the ID of an existing customer if one exists

				//exit();
				//log in the customer to create a their order
				//Mage::getSingleton('customer/session')->loginById($customerid);
				
				//$customer = Mage::getSingleton('customer/session')->getCustomer();
				$customer =Mage::getModel('customer/customer')->load($customerid);

				$transaction = Mage::getModel('core/resource_transaction');
				$storeId = $customer->getStoreId();
				$reservedOrderId = Mage::getSingleton('eav/config')->getEntityType('order')->fetchNewIncrementId($store_id);

				$order = Mage::getModel('sales/order')
						  ->setIncrementId($reservedOrderId)
						  ->setStoreId($storeId)
						  ->setQuoteId(0)
						  ->setGlobal_currency_code('USD')
						  ->setBase_currency_code('USD')
						  ->setStore_currency_code('USD')
						  ->setOrder_currency_code('USD');

				// set Customer data
				$order->setCustomer_email($customer->getEmail())
				  ->setCustomerFirstname($customer->getFirstname())
				  ->setCustomerLastname($customer->getLastname())
				  ->setCustomerGroupId($customer->getGroupId())
				  ->setCustomer_is_guest(0)
				  ->setCustomer($customer);

				 // set Billing Address
				$billing = $customer->getDefaultBillingAddress();
				

				$billingAddress = Mage::getModel('sales/order_address')
				  ->setStoreId($store_id)
				  ->setAddressType(Mage_Sales_Model_Quote_Address::TYPE_BILLING)
				  ->setCustomerId($customer->getId())
				  ->setCustomerAddressId($customer->getDefaultBilling())
				  ->setCustomer_address_id($billing->getEntityId())
				  ->setPrefix($billing->getPrefix())
				  ->setFirstname($billing->getFirstname())
				  ->setMiddlename($billing->getMiddlename())
				  ->setLastname($billing->getLastname())
				  ->setSuffix($billing->getSuffix())
				  ->setCompany($billing->getCompany())
				  ->setStreet($billing->getStreet())
				  ->setCity($billing->getCity())
				  ->setCountry_id($billing->getCountryId())
				  ->setRegion($billing->getRegion())
				  ->setRegion_id($billing->getRegionId())
				  ->setPostcode($billing->getPostcode())
				  ->setTelephone($billing->getTelephone())
				  ->setFax($billing->getFax());

				$order->setBillingAddress($billingAddress);

				$shipping = $customer->getDefaultShippingAddress();

				$shippingAddress = Mage::getModel('sales/order_address')
				  ->setStoreId($storeId)
				  ->setAddressType(Mage_Sales_Model_Quote_Address::TYPE_SHIPPING)
				  ->setCustomerId($customer->getId())
				  ->setCustomerAddressId($customer->getDefaultShipping())
				  ->setCustomer_address_id($shipping->getEntityId())
				  ->setPrefix($shipping->getPrefix())
				  ->setFirstname($shipping->getFirstname())
				  ->setMiddlename($shipping->getMiddlename())
				  ->setLastname($shipping->getLastname())
				  ->setSuffix($shipping->getSuffix())
				  ->setCompany($shipping->getCompany())
				  ->setStreet($shipping->getStreet())
				  ->setCity($shipping->getCity())
				  ->setCountry_id($shipping->getCountryId())
				  ->setRegion($shipping->getRegion())
				  ->setRegion_id($shipping->getRegionId())
				  ->setPostcode($shipping->getPostcode())
				  ->setTelephone($shipping->getTelephone())
				->setFax($shipping->getFax());

				$order->setShippingAddress($shippingAddress)
				  ->setShipping_method('flatrate_flatrate')
				  ->setShippingDescription('flatrate');

				$orderPayment = Mage::getModel('sales/order_payment')
				  ->setStoreId($storeId)
				  ->setCustomerPaymentId(0)
				  ->setMethod('checkmo')
				  ->setPo_number(' - ');
				$order->setPayment($orderPayment);



				$tmp_pledge = Mage::getModel('catalog/product')->loadByAttribute('sku','pledge-prd');
				$pledge_id = $tmp_pledge->getId();


				$pledge = Mage::getModel('catalog/product')->load($pledge_id);

				$reward = Mage::getModel('catalog/product')
				->load($product_id);

				//Mage::log($pledge);

				

				//exit();
				//$reward->setCrowdfundProjectid($project_id);

				//$rewardparent = $this->loadBundleParent($product_id);
				//$bundleditems = $this->loadBundle($product_id);

				//$rewardid = $rewardparent->getId();

				$subTotal = 0;
				$products = array(
				  $pledge_id => array(
				  'qty' => 1
				  ),
				  $product_id => array(
				  	'qty'=>1
				  	)
				  
				);

				Mage::log('PRODUCT IMPORTS LIST');
				Mage::log($products);

				foreach ($products as $productId=>$product) {
				  $_product = Mage::getModel('catalog/product')->load($productId);
				  
				  if($_product->getSku() == 'pledge-prd'){
					$rowTotal = $pledgeamount * $product['qty'];
					$orderItem = Mage::getModel('sales/order_item')
				    ->setStoreId($storeId)
				    ->setQuoteItemId(0)
					->setQuoteParentItemId(NULL)
				    ->setProductId($productId)
				    ->setProductType($_product->getTypeId())
				    ->setQtyBackordered(NULL)
				    ->setTotalQtyOrdered($product['qty'])
				    ->setQtyOrdered($product['qty'])
				    ->setName($_product->getName())
				    ->setSku($_product->getSku())
				    ->setPrice($pledgeamount)
				    ->setBasePrice($pledgeamount)
				    ->setOriginalPrice($pledgeamount)
				    ->setRowTotal($rowTotal)
				    ->setBaseRowTotal($rowTotal);
				  }else{
				  	Mage::log("GETTING BUNDLE OPTION");
				  	//Mage::log($product['bundle_option']);
				  	//get product options
				  	//$options = $_product->getProductOrderOptions();
			        //if (!$options) {
			            $bundleditems = $this->loadBundle($_product->getId());//$_product->getTypeInstance(true)->getOrderOptions($_product);
			           // $options = null;
			       // }
			        Mage::log('CF ADD BUNDLE OPTIONS');
			        Mage::log($bundleditems);
			        




				  	$rowTotal = $_product->getPrice() * 1;
				  	$orderItem = Mage::getModel('sales/order_item')
				  	->setStoreId($storeId)
				    ->setQuoteItemId(0)
				    ->setQuoteParentItemId(NULL)
				    ->setProductId($productId)
				    //->setParentItem(17)
				   // ->setProductOptions($bundleditems)
				    ->setProductType($_product->getTypeId())
				    ->setQtyBackordered(NULL)
				    ->setTotalQtyOrdered($product['qty'])
				    ->setQtyOrdered($product['qty'])
				    ->setName($_product->getName())
				    ->setSku($_product->getSku())
				    ->setPrice($_product->getPrice())
				    ->setBasePrice($_product->getPrice())
				    ->setOriginalPrice($_product->getPrice())
				    ->setRowTotal($rowTotal)
				    ->setBaseRowTotal($rowTotal);
				    //->setProductOptions($options);
				    //bundle children
				    foreach($bundleditems as $bitem){
				    	Mage::log('BITEM TO IMPORT');
				    	Mage::log($bitem);
				    	$bundledproduct = Mage::getModel('catalog/product')->load($bitem['id']);
					    
					    $borderItem = Mage::getModel('sales/order_item')
					  	->setStoreId($storeId)
					    ->setQuoteItemId(0)
					    ->setQuoteParentItemId(NULL)
					    ->setProductId($bitem['id'])
					    //->setParentItem($_product->getId())
					   // ->setProductOptions($product['bundle_option'])
					    ->setProductType($bundledproduct->getTypeId())
					    ->setQtyBackordered(NULL)
					    ->setTotalQtyOrdered($bitem['qty'])
					    ->setQtyOrdered($bitem['qty'])
					    ->setName($bundledproduct->getName())
					    ->setSku($bundledproduct->getSku())
					    ->setPrice($bundledproduct->getPrice())
					    ->setBasePrice($bundledproduct->getPrice())
					    ->setOriginalPrice($bundledproduct->getPrice())
					    ->setRowTotal($rowTotal)
					    ->setBaseRowTotal($rowTotal);

					    $order->addItem($borderItem);
					}

				  }
				  

				  // Mage::log(get_class_methods($orderItem)); 

				  $subTotal += $rowTotal;
				  $order->addItem($orderItem);
				}

				$order->setSubtotal($subTotal)
				  ->setBaseSubtotal($subTotal)
				  ->setGrandTotal($subTotal)
				  ->setBaseGrandTotal($subTotal);

				 Mage::log('ORDER ID CREATED----');
				$orders = Mage::getModel('sales/order')->getCollection()
			       ->setOrder('increment_id','DESC')
			       ->setPageSize(1)
			       ->getFirstItem();
			   
			    $lastorderid = $orders->getEntityId();
				
				 Mage::log($lastorderid);

				$transaction->addObject($order);
				$transaction->addCommitCallback(array($order, 'place'));
				$transaction->addCommitCallback(array($order, 'save'));
				$transaction->save();


				//save to reporting
				$pledgearray['pledgeid'] =$pledge_id;
				$pledgearray['pledgeprice'] = $pledgeamount;
				$this->cfsavetoreport($email,$project_id,$pledgearray,$bundleditems,$lastorderid,$store_id);










				


				////////end checkout/////////////////
			}
			$init++;
		}

		//Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


	}

	//add reporting from admin orders created

	public function cfsavetoreport($email,$project_id,$pledgearray,$products,$order_id,$store_id){

		//get the products from the order

		//set pledge
		$productsordered = Mage::getModel('crowdfund/customerproducts');
				$productsordered->setOrderId($order_id);
				$productsordered->setCustomerEmail($email);
				$productsordered->setProductId($pledgearray['pledgeid']);
				$productsordered->setPrice($pledgearray['pledgeprice']);
				$productsordered->setProjectId($project_id);
				$productsordered->setQty(1);	
				$productsordered->save();

		//save the selected bundle items (bundle children)
		foreach($products as $prod){
			$productsordered = Mage::getModel('crowdfund/customerproducts');
				$productsordered->setOrderId($order_id);
				$productsordered->setCustomerEmail($email);
				$productsordered->setProductId($prod['id']);
				$productsordered->setPrice($prod['price']);
				$productsordered->setQty($prod['qty']);
				$productsordered->setProjectId($project_id);	
				$productsordered->save();

		}

		//add order to crowdfund orders table
		$ordersmodel = Mage::getModel("crowdfund/orders");
		Mage::log('PROJECT ID OBSERVER : '. $project_id);
		$ordersmodel->setEntity($order_id);//this is the order ID 
		Mage::log('INSERT CUSTOMER EMAIL : '.$email);
		$customer_arr = array("customer_email"=>$email);

		$ordersmodel->addData($customer_arr);//the customer ID loaded by email above
		$ordersmodel->setProjectId($project_id);//the project ID
		$ordersmodel->setWebsiteId($store_id);//the store/website ID
		$ordersmodel->save();



		//add data to credit history and credit table
		//start credit model
		$creditmodel = Mage::getModel("crowdfund/customercredit")->getCollection();
		$credithistorymodel = Mage::getModel('crowdfund/customercredithistory')->getCollection();

		//date and date modified						
		
		//first let's check if we are updating or inserting new
		$creditmodel->addFieldToFilter('customer_email',$email);
		$credithistorymodel->addFieldToFilter('order_id',$order_id);

				
		$creditamount = $pledgearray['pledgeprice'];
		//$project_id = 1;
		if(count($creditmodel)>0){
			Mage::log("EMAIL EXISTS?");
			foreach($creditmodel as $item){
				Mage::log($item->getCustomerEmail());
				$id = $item->getId();
			}

			//let's do a new insertion
			$this->updatecreditrecordadmin($email,$creditamount,$project_id,$order_id);
			
		}else{
			//let's update the record
			Mage::log("!EMAIL EXISTS?");
			if(count($credithistorymodel)<1){
				//only run this one time per order to avoid duplicates
				$this->addnewcreditrecord($email,$creditamount,$project_id,$order_id);
			}
		}


	}




	//end reporting functions
	public function admincreateorderss($orders,$project_id,$product_id,$store_id){

		$init = 0;
		//Mage::log(Mage::getModel('payment/config')->getActiveMethods());
		
		foreach($orders as $raworder){
			if($init!=0){
				$order = explode(',', $raworder);
				
				Mage::app()->setCurrentStore($store_id);//switch to store front temporarily to simulate purchase
				$customername = $order[1];
				$email = $order[2];
				$shippingtype = $order[3];
				$pledgeamount = $order[4];
				$pledgecollected = $order[7];

				
				Mage::log('adminorder data : '.$customername."  ".$email."  ".$shippingtype."  ".$pledgeamount."  ".$pledgecollected." | projectid  ".$project_id."  ".$project_id."   ".$store_id);
				//format pledge price

				$pledgeamount = str_replace(" USD", "", $pledgeamount);
				$pledgeamount = str_replace("\$", "", $pledgeamount);


				$userpassword = $this->cfpasswordgenerator();

				Mage::getSingleton('core/session')->setPledgeprice($pledgeamount);
				//Mage::log($email);

				$customerid = $this->cfcreatecustomer($email,$userpassword,$store_id,$customername);//creates the customer and reutrns the id or simply returns the ID of an existing customer if one exists

				//exit();
				//log in the customer to create a their order
				Mage::getSingleton('customer/session')->loginById($customerid);
				
				$customer = Mage::getSingleton('customer/session')->getCustomer();
				$firstname = $customer->getFirstname();
				$lastname = $customer->getLastname();

				$_custom_address = array (
					'firstname' => $firstname,
					'lastname' => $lastname,
					'street' => array (
						'0' => 'Update address please',
						'1' => 'Update address please',
					),
				 
					'city' => 'New York',
					'region_id' => '43',
					'region' => 'NY',

					'postcode' => '10007',
					'country_id' => 'US', /* United States */
					'telephone' => '5555555555',
				);
				$customAddress = Mage::getModel('customer/address');
				//$customAddress = new Mage_Customer_Model_Address();
				$customAddress->setData($_custom_address)
							->setCustomerId($customer->getId())
							->setIsDefaultBilling('1')
							->setIsDefaultShipping('1')
							->setSaveInAddressBook('1');
				$customAddress->save();
				//create a quote
				Mage::getSingleton('checkout/session')->getQuote()->setBillingAddress(Mage::getSingleton('sales/quote_address')->importCustomerAddress($customAddress));

				//$pledge = Mage::getModel('catalog/product')->loadByAttribute('sku','pledge-prd');
				$tmp_pledge = Mage::getModel('catalog/product')->loadByAttribute('sku','pledge-prd');
				$pledge_id = $tmp_pledge->getId();


				$pledge = Mage::getModel('catalog/product')->load($pledge_id);

				$reward = Mage::getModel('catalog/product')
				->load($product_id);

				//Mage::log($pledge);

				

				//exit();
				//$reward->setCrowdfundProjectid($project_id);

				$rewardparent = $this->loadBundleParent($product_id);
				$bundleditems = $this->loadBundle($product_id);

				//exit();
				//add the project id attributes to rewards and pledge
				//remeber to get the bundle items
				Mage::log('WEBSITE IDS');
				Mage::log($pledge->getStoreId());
				
				
				//copy method from here http://w3facility.info/question/how-do-i-programmatically-import-orders-into-magento/


				$cart = Mage::getSingleton('checkout/cart');

				$cart->truncate();
				$cart->save();
				$cart->getItems()->clear()->save();
				



				//add the pledge product;
				Mage::log('ADDING PLEDGE');
				$cart->addProduct($pledge, array('qty' => 1));
				//exit();
				Mage::log('ADDING REWARD');
				$cart->addProduct($rewardparent,$bundleditems);
				//exit();

				$cart->save();

				//add project id to all items
				$cartitems = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
                foreach($cartitems as $item){
                 // Mage::log("DEBUG ATTR");
                  
                    Mage::log("ADDING PRODUCY ID ATTRIBUTE : ".$item->getProduct()->getId());
                    $_product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());  
                    $item->setCrowdfundProjectid($project_id);
                 
                }
               // Mage::getSingleton('checkout/session')->getQuote()->setMethod('checkmo');
                Mage::log('GET QUOTE PAYMENT');
                $payment = Mage::getSingleton('checkout/session')->getQuote()->getPayment();
                Mage::log('import data');
                $payment->importData(array('method'=>'checkmo'));
                 Mage::log('set payment');
                $payment->setMethod(array('method'=>'checkmo'));

              // Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
              //  exit();

				
				/////////checkout completion//////////

				//$storeId = Mage::app()->getStore()->getId();
	 		//	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
				 Mage::log('CHECK OUT ONE PAGE');
				$checkout = Mage::getSingleton('checkout/type_onepage');
				  Mage::log('init checkout');
				$checkout->initCheckout();
				 Mage::log('checkout method'); 
				$checkout->saveCheckoutMethod('register');
				 Mage::log('set shipping method'); 
				$checkout->saveShippingMethod('flatrate_flatrate');
				  Mage::log('set payment method');
				$checkout->savePayment(array('method'=>'checkmo'));
				
				
				try {
					$checkout->saveOrder();
				}
				catch (Exception $e) {
					//echo $ex->getMessage();
					Mage::getSingleton('core/session')->addError('Failed to create one or more orders. Please check log for details.');
				    Mage::log($e->getMessage(),null,"crowdfund_error.log");
				}			
				 
				/* Clear the cart */
				$cart->truncate();
				$cart->save();
				$cart->getItems()->clear()->save();		
				 
				/* Logout the customer you created */
				Mage::getSingleton('customer/session')->logout();


				////////end checkout/////////////////
			}
			$init++;
		}

		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


	}
	
	
	public function cfcreatecustomer($email,$password,$store_id,$customername){
		//does customer exist?
		Mage::log("cfcreatecustomer");
		Mage::log('CUSTOMER EMAIL : '.$email);
		$time = strtotime(date("H:i:s", Mage::getModel('core/date')->timestamp(time())));
		$customermodel = Mage::getModel('customer/customer')
		->setWebsiteId($store_id)
		->loadByEmail($email)
		;

		$found = 0;
		
		if($customermodel->getId()){
			$found = 1;
			$customerid = $customermodel->getId();
		}

		if($found>0){
			Mage::log('CUSTOMER FOUND HERE IS HIS ID : '.$customerid);
			$crowdfundconfig =  Mage::getStoreConfig('crowdfundsettings');
			if($crowdfundconfig['config']['send_import_order_email']=="Yes"){
				Mage::log('SENDING USER EMAIL: ACCOUNT CREATED');

				$notifymodel = Mage::getModel('crowdfund/customernotify');
				$notifymodel->setEmail($email);
				$notifymodel->setUserName($firstname." ".$lastname);
				$notifymodel->setTmpPass(Mage::getUrl('customer/account/forgotpassword'));
				$notifymodel->setTemplateId($crowdfundconfig['config']['new_order_email_template']);
				$notifymodel->setStoreId($store_id);
				$notifymodel->setNotified(0);
				$notifymodel->setDateCreated($time);
				$notifymodel->setDateModified($tme);
				$notifymodel->save();
				
				
			}

			
			return $customerid;
		}

		//Mage::log("CUSTOMER INSTANCE COUNT : ".$cnt);

		if($found < 1){
			Mage::log('NO CUSTOMER FOUND CREATING NEW CUSTOMER');
			//create a new customer
			//split customer name
			$customername = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $customername);
			$customerarray = explode(" ", $customername);


			Mage::log($customerarray);
			$customerarray_count = count($customerarray);
			if($customerarray_count>2){
				$firstname = $customerarray[0];
				$middlename = $customerarray[1];
				$lastname = $customerarray[2];
			}
			if($customerarray_count<3){
				$firstname = $customerarray[0];
				$middlename = " ";
				$lastname = $customerarray[1];
			}


			$customer = Mage::getModel("customer/customer");
			
			Mage::log("WEBSITE ID : ".$store_id);

			$customer
			->setWebsiteId($store_id)
            ->setStoreId($store_id)
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setEmail($email)
            ->setPassword($password);
			
			$customer->save();
			
			$newCustomer = Mage::getModel('customer/customer')
			->setWebsiteId($store_id)
			->loadByEmail($email);

			Mage::log("CUSTOMER ID CREATION : ".$newCustomer->getId());

			//set billing address
			$_custom_address = array(
			'firstname' => $firstname,
			'lastname' => $lastname,
			'street' => array(
				'0' => 'Update address'
				//'1' => 'Sample address part2',
			),
		 
			'city' => 'New York',
			'region_id' => '43',
			'region' => '',
			'postcode' => '10007',
			'country_id' => 'US',
			'telephone' => '555 555 5555',
			);

			$customAddress = Mage::getModel('customer/address');
			$customAddress->setData($_custom_address)
			->setCustomerId($newCustomer->getId())
			->setIsDefaultBilling('1')
			->setIsDefaultShipping('1')
			->setSaveInAddressBook('1');
			$customAddress->save();

			$crowdfundconfig =  Mage::getStoreConfig('crowdfundsettings');
			if($crowdfundconfig['config']['send_import_order_email']=="Yes"){
				Mage::log('SENDING USER EMAIL: ACCOUNT CREATED');

				$notifymodel = Mage::getModel('crowdfund/customernotify');
				$notifymodel->setEmail($email);
				$notifymodel->setUserName($firstname." ".$lastname);
				$notifymodel->setTmpPass(Mage::getUrl('customer/account/forgotpassword'));
				$notifymodel->setTemplateId($crowdfundconfig['config']['new_customer_email_template']);
				$notifymodel->setStoreId($store_id);
				$notifymodel->setNotified(0);
				$notifymodel->setDateCreated($time);
				$notifymodel->setDateModified($tme);
				$notifymodel->save();
				
				
			}



			return $newCustomer->getId();

            			
		}
	}

	public function cfpasswordgenerator(){
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    Mage::log("PASSWORD GEN : ".implode($pass));
	    return implode($pass); //turn the array into a string
	}

	public function getprojectname($id){
		$project = Mage::getModel('crowdfund/crowdfund')
		->getCollection()
		;//->
	}

	public function loadBundle($id){

        //Mage::log($id);
       
        //$pid = $bundled_product->getOptionsIds();
       
        /*
Array
(
    [info_buyRequest] => Array
        (
            [qty] => 1
            [bundle_option] => Array
                (
                    [9] => 12
                )

            [options] => Array
                (
                )

        )

)
*/

 		$bundled_product = Mage::getModel('catalog/product');
        $bundled_product->load($id);
       $selectionCollection = $bundled_product->getTypeInstance(true)->getSelectionsCollection(
          $bundled_product->getTypeInstance(true)->getOptionsIds($bundled_product), $bundled_product);

       $bundled_items = array();
       $bundleOptions = array();

       foreach($selectionCollection as $option) 
        {
          
        	//Mage::log(get_class_methods($option));
          $productId = $option->product_id;
          //Mage::log('option : '.$productId);

         // $bundled_items[$option->getOptionId()][] = $option->getSelectionId();
          //$params[$option->getId()] = array($bundled_items);
         // $bundlopt[$option->getOptionId()] = array('title'=>$option->getName(),'qty'=>$option->getQty(),'price'=>$option->getPrice());
          $price = $bundled_product->getPriceModel()->getSelectionFinalTotalPrice($bundled_product, $option, 0,
                            1
                        );
          $options = Mage::getModel('bundle/option')->getResourceCollection()
                                              ->setProductIdFilter($option->product_id)
                                              ->setPositionOrder(); 
          /*$bundleOptions[$option->getOptionId()] =  array(
          	'option_id' => $option->getId(),
            'label' => $option->getTitle(),
            'value' => array()
          );*/

          /*$bundleOptions[$option->getId()]['value'][] = array(
          					'id'	=> $option->getId(),
                            'title' => $option->getName(),
                            'qty'   => $option->getQty(),
                            'price' => Mage::app()->getStore()->convertPrice($price)
                        );*/
		$bundleOptions[$option->getId()] = array(
          					'id'	=> $option->getId(),
                            'title' => $option->getName(),
                            'qty'   => $option->getSelectionQty(),
                            'price' => Mage::app()->getStore()->convertPrice($price)
                        );


          $opt_id = $option->getOptionId();
          $label = $option->getLabel();
        }


        /*foreach ($selectionCollection as $selection) {
                
                	Mage::log('isSalable');
                    $selectionQty = $bundled_product->getCustomOption('selection_qty_' . $selection->getSelectionId());
                    Mage::log('Selection qty');
                    
                    if ($selectionQty) {
                    	Mage::log($selectionQty);
                        $price = $bundled_product->getPriceModel()->getSelectionFinalTotalPrice($bundled_product, $selection, 0,
                            $selectionQty->getValue()
                        );

                        $option = $options->getItemById($selection->getOptionId());
                        if (!isset($bundleOptions[$option->getId()])) {
                            Mage::log('Option Is Set');
                            $bundleOptions[$option->getId()] = array(
                                'option_id' => $option->getId(),
                                'label' => $option->getTitle(),
                                'value' => array()
                            );
                        }

                        $bundleOptions[$option->getId()]['value'][] = array(
                            'title' => $selection->getName(),
                            'qty'   => $selectionQty->getValue(),
                            'price' => Mage::app()->getStore()->convertPrice($price)
                        );

                    }
                
            }*/




            Mage::log($bundleOptions);
            $info_buyRequest = $bundleOptions;



       /*$info_buyRequest['info_buyRequest'] = array('qty'=>1,'bundle_option'=>$bundled_items);
       selectionCollection['bundle_options'] = array('option_id'=>$opt_id,'label'=>$label,'value'=>$bundlopt);*/


       /* foreach($selectionCollection as $option) 
        {
          $productId = $option->product_id;
          Mage::log('option : '.$productId);

          $bundled_items[$option->getOptionId()][] = $option->getSelectionId();
          $params[] = array('bundle_option' => $bundled_items,'qty' => 1,'product'=>$productId);

        }*/




        Mage::log('BUNDLE PARAMS');
        Mage::log($info_buyRequest);

      return $info_buyRequest;
    }
    public function loadBundleParent($id){

        //Mage::log($id);
        $bundled_product = Mage::getModel('catalog/product');
        $bundled_product->load($id);
      	
      	Mage::log('BUNDLE PRODUCT : ');
      	Mage::log($bundled_product->getSku());

      return $bundled_product;
    }
    

    public function cfgetOrderOptions($product = null)
    {
        $optionArr = array();//parent::getOrderOptions($product);

        $bundleOptions = array();

        $product = $product;//$this->getProduct($product);

        //if ($product->hasCustomOptions()) {
            /*$customOption = $product->getTypeInstance(true)->getOptionsIds($product);//->getCustomOption('bundle_option_ids');
            $optionIds = $customOption;//unserialize($customOption);
            $options = $this->cfgetOptionsByIds($optionIds, $product);
            //$customOption = $product->getCustomOption('bundle_selection_ids');
            $selectionIds = $customOption;
            $selections = $this->cfgetSelectionsByIds($selectionIds, $product);

            $bundled_product = Mage::getModel('catalog/product');
        	$bundled_product->load($id);*/
       		$selections = $product->getTypeInstance(true)->getSelectionsCollection(
          $product->getTypeInstance(true)->getOptionsIds($product), $product);


            foreach ($selections->getItems() as $selection) {
                if ($selection->isSalable()) {
                    $selectionQty = $product->getCustomOption('selection_qty_' . $selection->getSelectionId());
                    if ($selectionQty) {
                        $price = $product->getPriceModel()->getSelectionFinalTotalPrice($product, $selection, 0,
                            $selectionQty->getValue()
                        );

                        $option = $options->getItemById($selection->getOptionId());
                        /*if (!isset($bundleOptions[$option->getId()])) {
                            $bundleOptions[$option->getId()] = array(
                                'option_id' => $option->getId(),
                                'label' => $option->getTitle(),
                                'value' => array()
                            );
                        }*/

                        $bundleOptions[$option->getId()]['value'][] = array(
                            'id' => $selection->getId(),
                            'title' => $selection->getName(),
                            'qty'   => $selection->getQty(),
                            'price' => Mage::app()->getStore()->convertPrice($price)
                        );

                    }
                }
            }
        //}

        $optionArr = $bundleOptions;

        /**
         * Product Prices calculations save
         */
        if ($product->getPriceType()) {
            $optionArr['product_calculations'] = 0;//self::CALCULATE_PARENT;
        } else {
            $optionArr['product_calculations'] = 0;//self::CALCULATE_CHILD;
        }

        $optionArr['shipment_type'] = $product->getShipmentType();

        return $optionArr;
    }



}
	 