<?php
$installer = $this;
$installer->startSetup();

//set up our custom category attribute
$installer->removeAttribute('catalog_category', 'is_crowdfund');
$attribute  = array(
    'type' => 'int',
    'label'=> 'Crowdfund Project',
    'input' => 'select',
    'option' => array(
      'values' => array(
          0 => 'No',
          1 => 'Yes',
        ),
    ),
    'default' => 0,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible' => true,
    'required' => false,
    'user_defined' => true,
    'default' => 0,
    'group' => "General Information"
);
$installer->addAttribute('catalog_category', 'is_crowdfund', $attribute);
//DROP TABLE IF EXISTS {$this->getTable('crowdfund')};
//DROP TABLE IF EXISTS {$this->getTable('crowdfund_products')};

/*DROP TABLE IF EXISTS {$this->getTable('crowdfund')};
DROP TABLE IF EXISTS {$this->getTable('crowdfund_products')};
DROP TABLE IF EXISTS {$this->getTable('crowdfund_customers')};
DROP TABLE IF EXISTS {$this->getTable('crowdfund_customers_products')};
DROP TABLE IF EXISTS {$this->getTable('crowdfund_customers_orders')};*/
//CREATE TABLE IF NOT EXISTS
$sql=<<<SQLTEXT



CREATE TABLE IF NOT EXISTS `crowdfund` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `project_id` INT NOT NULL ,
  `project_name` VARCHAR(100) NULL ,
  `project_cms` TEXT NULL ,
  `project_image` VARCHAR(150) NULL,
  `project_start` DATETIME NULL ,
  `project_end` DATETIME NULL ,
  `goal` DECIMAL NULL ,
  `store_id` INT NULL ,
  `product_id` VARCHAR( 1000 ) NULL  ,
  PRIMARY KEY (`id`) );


CREATE TABLE IF NOT EXISTS `crowdfund_products` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `project_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `position` INT NOT NULL,
  PRIMARY KEY (`id`)
) ;

CREATE TABLE IF NOT EXISTS `crowdfund_customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entity_id` int NOT NULL,
  `project_id` INT NOT NULL,
  `position` int NOT NULL,
  PRIMARY KEY (`id`)
) ;

CREATE TABLE IF NOT EXISTS `crowdfund_customers_products`(
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `product_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ;

CREATE TABLE IF NOT EXISTS `crowdfund_customers_orders`(
  `id` int NOT NULL AUTO_INCREMENT,
  `entity` int NOT NULL,
  `customer_id` int NOT NULL,
  `project_id` int NOT NULL,
  `website_id` int NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE  TABLE IF NOT EXISTS `crowdfund_notify` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(45) NULL ,
  `tmp_pass` VARCHAR(45) NULL ,
  `notified` INT NULL ,
  PRIMARY KEY (`id`) );



CREATE TABLE IF NOT EXISTS `crowdfund_customer_credit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_email` varchar(40) NOT NULL,
  `project_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `credit_amount` float NOT NULL,
  `date_created` date NOT NULL,
  `date_modified` date NOT NULL,
  PRIMARY KEY (`id`)
) 



CREATE TABLE IF NOT EXISTS `crowdfund_order_credit_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `credit_amount` float NOT NULL,
  `date_created` date NOT NULL,
  PRIMARY KEY (`id`)
) 





		
SQLTEXT;

$installer->run($sql);


//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 
