<?php

class Frankwebdev_Crowdfund_Block_Adminhtml_Crowdfundmodel_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("productsmodelGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("crowdfund/crowdfundmodel")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("crowdfund")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "id",
				));
				$this->addColumn("website", array(
				"header" => Mage::helper("crowdfund")->__("Store"),
				"align" =>"right",
				"width" => "50px",
			    //"type" => "text",
				"index" => "store_id",
				'type' => 'store',
				'store_view'=> true,
				'display_deleted' => false,
				));

				$this->addColumn("Project Name", array(
				"header" => Mage::helper("crowdfund")->__("project_name"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "text",
				"index" => "project_name",
				));
				//display this column as currency
				$this->addColumn("Goal", array(
				"header" => Mage::helper("crowdfund")->__("goal"),
				"align" =>"right",
				"width" => "50px",
				'type'  => 'currency',
			   'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
				"index" => "goal",
				));
				//add action column
				Mage::log('ENT ID');
				
				$link= Mage::helper('adminhtml')->getUrl('*/*/edit/') .'id/$entity_id';
				$this->addColumn('action_edit', array(
			        'header'   => $this->helper('crowdfund')->__('Action'),
			        'width'    => 15,
			        'sortable' => false,
			        'filter'   => false,
			        'type'     => 'action',
			        'getter'    => 'getId',
			        'actions'  => array(
			            array(
			                //'url'     => $link,
			                'url'     => array('base'=> '*/*/edit'),
			                'caption' => $this->helper('crowdfund')->__('Edit'),
			                'field' => 'id'
			            ),
			        )
			    ));


                
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return '#';
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_crowdfundmodel', array(
					 'label'=> Mage::helper('crowdfund')->__('Delete Project'),
					 'url'  => $this->getUrl('*/adminhtml_crowdfundmodel/massRemove'),
					 'confirm' => Mage::helper('crowdfund')->__('Are you sure?')
				));

			return $this;
		}
			

}