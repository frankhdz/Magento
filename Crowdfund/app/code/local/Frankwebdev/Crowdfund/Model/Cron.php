<?php
class Frankwebdev_Crowdfund_Model_Cron{	
	public function runautobill(){
		//do something
	}

	public function sendnotifications(){
		//send customer emails 
		//get the server time
		$time = strtotime(date("H:i:s", Mage::getModel('core/date')->timestamp(time())));
		

		$crowdfundconfig =  Mage::getStoreConfig('crowdfundsettings');
		//Mage::log($crowdfundconfig);
		$runtime = strtotime(str_replace(",", ":", (string)$crowdfundconfig['config']['send_email_time']));

		Mage::log("CURRENT TIME : ".$time);
		Mage::log("RUN AT THIS TIME : ".$runtime);

		$range = strtotime(date("H:i:s",strtotime('+20 minutes', $time) ));

		Mage::log($time.">".$runtime." | ".$runtime.'<'.$range);

		if($time>$runtime){
			Mage::log('TRUE');
		}else{
			Mage::log('FALSE');
		}
		if($runtime<$range){
			Mage::log('TRUE');
		}else{
			Mage::log('FALSE');
		}

		
		if(((int)$time>(int)$runtime)&&((int)$time<$range)){
			Mage::log('SENDING OUT EMAILS');



			$mailcollection = Mage::getModel('crowdfund/customernotify')->getCollection()
							->addFieldToFilter('notified',0)
			;

			foreach($mailcollection as $mail){
				$id=$mail->getData('id');
				$templateId = $mail->getData('template_id');
				$store_id = $mail->getData('store_id');
				$username = $mail->getData('user_name');
				$email = $mail->getData('email');
				$password = $mail->getData('tmp_pass');

				Mage::log(' SENT EMAIL TO : '.$username." at ".$email." | template : ".$templateId);

				$emailTemplate = Mage::getModel('core/email_template')->loadByCode($templateId);
				$vars = array('user_name' => $username, 'user_email' => $email, 'user_password' => $password);
				$emailTemplate->getProcessedTemplate($vars);

				$emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email', $store_id));
				//$emailTemplate->setTemplateSubject('Welcome to Greenbrier Games!');
				$emailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_general/name', $store_id));
				$emailTemplate->send($email,$username, $vars);

				$data = array('notified'=>1);
				$model = Mage::getModel("crowdfund/customernotify")->load($id);
				$model->addData($data);
				$model->setDateMOdified($time);
				$model->setId($id)->save();
				//$model->setId($this->getRequest()->getParam("id"))->delete();

			}




		}



	} 
}