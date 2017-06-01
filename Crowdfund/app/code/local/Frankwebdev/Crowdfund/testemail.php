<?php 
require_once '../../../../Mage.php';

umask(0);

Mage::app('default');

$email = Mage::getModel("crowdfund/cron");

// manually fire off the emails
$email->sendnotifications(null);

?>