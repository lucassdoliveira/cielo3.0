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

class Nitroecom_Cielo_Block_Adminhtml_Order_Invoice_Totals extends Mage_Adminhtml_Block_Sales_Order_Totals
{
    /**
     * Initialize order totals array
     *
     * @return Mage_Sales_Block_Order_Totals
     */
    protected function _initTotals()
    {
        parent::_initTotals();

        $source = $this->getSource();
        if($this->getSource()->getJuros()>0)
        {
            $this->addTotalBefore(new Varien_Object(array(
                    'code'       => 'juros',
                    'strong'     => true,
                    'value'      => $this->getSource()->getJuros(),
                    'base_value' => $this->getSource()->getBaseJuros(),
                    'label'      => Mage::getStoreConfig('payment/nitrocielo/texto_juros'),
                    'area'       => 'footer')), 'grand_total');
        }

        if($this->getSource()->getDesconto()<0)
        {
            $this->addTotalBefore(new Varien_Object(array(
                    'code'       => 'desconto',
                    'strong'     => true,
                    'value'      => $this->getSource()->getDesconto(),
                    'base_value' => $this->getSource()->getBaseDesconto(),
                    'label'      => Mage::getStoreConfig('payment/nitrocielo/texto_desconto_a_vista'),
                    'area'       => 'footer')), 'grand_total');
        }

        return $this;
    }
}

?>