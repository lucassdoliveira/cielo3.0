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

class Nitroecom_Cielo_Model_Source_NumMaxParc
{
	public function toOptionArray ()
	{
		$options = array();
        
                $options['1'] = Mage::helper('adminhtml')->__('1x - Desativado');
                $options['2'] = Mage::helper('adminhtml')->__('2x');
                $options['3'] = Mage::helper('adminhtml')->__('3x');
                $options['4'] = Mage::helper('adminhtml')->__('4x');
                $options['5'] = Mage::helper('adminhtml')->__('5x');
                $options['6'] = Mage::helper('adminhtml')->__('6x');
                $options['7'] = Mage::helper('adminhtml')->__('7x');
                $options['8'] = Mage::helper('adminhtml')->__('8x');
                $options['9'] = Mage::helper('adminhtml')->__('9x');
                $options['10'] = Mage::helper('adminhtml')->__('10x');
                $options['11'] = Mage::helper('adminhtml')->__('11x');
                $options['12'] = Mage::helper('adminhtml')->__('12x');
                $options['13'] = Mage::helper('adminhtml')->__('13x');
                $options['14'] = Mage::helper('adminhtml')->__('14x');
                $options['15'] = Mage::helper('adminhtml')->__('15x');
                $options['16'] = Mage::helper('adminhtml')->__('16x');
                $options['17'] = Mage::helper('adminhtml')->__('17x');
                $options['18'] = Mage::helper('adminhtml')->__('18x');
    
        
		return $options;
	}
}
