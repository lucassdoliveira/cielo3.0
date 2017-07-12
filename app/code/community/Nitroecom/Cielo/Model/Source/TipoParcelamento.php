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

class Nitroecom_Cielo_Model_Source_TipoParcelamento
{
	public function toOptionArray ()
	{
		$options = array();
        
		$options['loja']  		   = Mage::helper('adminhtml')->__('Parcelado Loja');
        $options['administradora'] = Mage::helper('adminhtml')->__('Parcelado Estabelecimento');

        return $options;
	}
}

?>
