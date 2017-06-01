<?php
class Frankwebdev_Crowdfund_Model_Mysql4_Grid extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("crowdfund/grid", "id");
    }
    public function addGridPosition($collection,$project_id){
		$table2 = $this->getMainTable();
		
		$cond = $this->_getWriteAdapter()->quoteInto('e.entity_id = t2.product_id','');
		
		$collection->getSelect()->joinLeft(
			array('t2'=>$table2), 
			$cond,
			array('id'=>'t2.id')/*,
			array('project_id'=>'t2.project_id'),
			array('product_id'=>'t2.product_id')*/


			);
		$collection->getSelect()->group('e.entity_id');
		$collection
		->getSelect()
		//->reset()
		->columns(
		    array('position' => new Zend_Db_Expr(
		        "IFNULL(t2.position,0)")
		    	)
			
		   /* array('id'=>'t2.id'),
		    array('product_id'=>'t2.product_id'),
		    array('project_id'=>'t2.project_id'),
		    array('entity_id'=>'e.entity_id'),
		    array('entity_type_id'=>'e.entity_type_id'),
		    array('attribute_set_id'=>'e.attribute_set_id'),
		    array('type_id'=>'e.type_id'),
		    array('sku'=>'e.sku')
		    array('has_options'=>'e.has_options'),
		    array('required_options'=>'e.required_options'),
		    array('created_at'=>'e.created_at'),
		    array('updated_at'=>'e.updated_at')*/


		);
 		
 		Mage::log($collection->getSelect()->__toString());
	}

}