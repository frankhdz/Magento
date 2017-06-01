<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Product Stores tab
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Frankwebdev_Crowdfund_Block_Adminhtml_Form_Edit_Tab_Rewards extends Mage_Adminhtml_Block_Widget_Form
{
    protected $_storeFromHtml;

    /**
     * Constructor
     */
    public function _prepareForm()
    {
        
        if(Mage::registry('project_data')!=""){
          $values = Mage::registry('project_data')->getData();

          Mage::log("PROJECT ID DATA : ");
          Mage::log($values['project_id']);
      }else{
        $values = array("project_id"=>"");

      }

        $form = new Varien_Data_Form(); 
        //$form->setUseContainer(true);
        
        $_websites = Mage::app()->getWebsites();
        foreach($_websites as $website){   
            //Mage::log($website->getId());
            // Mage::log($website->getName());

             $websitelist[$website->getId()] = $website->getName();

        }
        $this->setForm($form);
        $helper = Mage::helper('crowdfund');
        //$form->setDataObject(Mage::getModel('catalog/product'));
        $fieldset = $form->addFieldset('display', array(
                'legend' => $helper->__('Crowdfund: Rewards'),
                'class' => 'fieldset-wide'
        ));
        

       $fieldset->addField('product_id', 'text', array(
          'label'     => $helper->__('Reward Product ID(s)'),
          'name'    => 'product_id',
          'after_element_html' => '<small>Rewards Product ID(s)</small>',
          'tabindex' => 1,
          'value'    => $values['product_id'],
          
          
        ));

        $this->setForm($form);

        return parent::_prepareForm();


    }

   
}
