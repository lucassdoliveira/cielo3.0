<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @title      Cielo pagamento com cartão de crédito (Brazil)
 * @category   payment
 * @package    Nitroecom_Cielo
 * @copyright  Copyright (c) 2017 Nitroecom (https://www.nitroecom.com.br)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Lucas Oliveira - Nitro e-com <www.nitroecom.com.br>
 */

class Nitroecom_Cielo_Model_Source_Bandeiras
{
        public function toOptionArray ()
        {
                $options = array();
        
                $options[] = array('value' => 'Visa', 'label' => Mage::helper('adminhtml')->__('Visa'));
                $options[] = array('value' => 'Master', 'label' => Mage::helper('adminhtml')->__('Mastercard'));
                $options[] = array('value' => 'Diners', 'label' => Mage::helper('adminhtml')->__('Diners Club'));
                $options[] = array('value' => 'Discover', 'label' => Mage::helper('adminhtml')->__('Discover'));
                $options[] = array('value' => 'Elo', 'label' => Mage::helper('adminhtml')->__('Elo'));
                $options[] = array('value' => 'Amex', 'label' => Mage::helper('adminhtml')->__('Amex'));
                $options[] = array('value' => 'JCB', 'label' => Mage::helper('adminhtml')->__('JCB'));
                $options[] = array('value' => 'Aura', 'label' => Mage::helper('adminhtml')->__('Aura'));
        
                return $options;
        }
}

?>