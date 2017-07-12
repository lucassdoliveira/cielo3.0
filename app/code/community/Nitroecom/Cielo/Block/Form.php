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

class Nitroecom_Cielo_Block_Form extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('Nitroecom_Cielo/form.phtml');

        $quote = Mage::getSingleton('checkout/cart')->getQuote();
        $quote->setJuros(0.0);
        $quote->setBaseJuros(0.0);
        $quote->setDesconto(0.0);
        $quote->setBaseDesconto(0.0);
        $quote->setTotalsCollectedFlag(false)->collectTotals();
        $quote->save();
    }

    # pega configurações do magento
    protected function _getConfig()
    {
        return Mage::getSingleton('payment/config');
    }

    /**
     * Retrieve credit card expire months
     *
     * @return array
     */
    public function getCcMonths()
    {
        $months = $this->getData('cc_months');
        if (is_null($months))
        {
            $months[0] = $this->__('Mês');
            $months    = array_merge($months, $this->_getConfig()->getMonths());

            $this->setData('cc_months', $months);
        }

        return $months;
    }    

    /**
     * Retrieve credit card expire years
     *
     * @return array
     */
    public function getCcYears()
    {
        $years = $this->getData('cc_years');
        if (is_null($years))
        {
            $years = $this->_getConfig()->getYears();
            $years = array(0=>$this->__('Ano'))+$years;

            $this->setData('cc_years', $years);
        }

        return $years;
    }

    public function getValorMinimoParcela()
    {
        return Mage::getStoreConfig('payment/nitrocielo/valor_minimo');
    }

    public function getParcelassemJuros()
    {
        $config     = Mage::getStoreConfig('payment/nitrocielo/max_parcelas_sem_juros');
        $regras     = unserialize($config);
        $valorTotal = mage::getBlockSingleton('nitrocielo/form')->getValorTotal();

        if($regras)
        {
            foreach($regras as $regra)
            {
                if($valorTotal >= $regra['parcela_de'] && $valorTotal <= $regra['parcela_ate'])
                    return $regra['value'];
            }
        }

        return Mage::getStoreConfig('payment/nitrocielo/parcelas_sem_juros');
    }

    public function getMaximoParcelas()
    {
        $config = Mage::getStoreConfig('payment/nitrocielo/max_parcelas');
        $regras = unserialize($config);        

        $valorTotal = mage::getBlockSingleton('nitrocielo/form')->getValorTotal();
        if($regras)
        {
            foreach($regras as $regra)
            {
                if($valorTotal>=$regra['parcela_de'] && $valorTotal<=$regra['parcela_ate'])
                    return $regra['value'];
            }
        }

        return Mage::getStoreConfig('payment/nitrocielo/num_max_parc');
    }

    public function getJurosParcela()
    {
        return Mage::getStoreConfig('payment/nitrocielo/juros_parcela');
    }

    public function getDescontoaVista()
    {
        return Mage::getStoreConfig('payment/nitrocielo/valor_desconto_avista');
    }

    public function getValorTotal()
    {
        $total = Mage::getSingleton('checkout/cart')->getQuote()
                                                    ->setTotalsCollectedFlag(false)
                                                    ->collectTotals()
                                                    ->getGrandTotal();
        if($total==''||$total<=0)
        {
            $total = Mage::getSingleton("adminhtml/session_quote")->getQuote()
                                                                  ->setTotalsCollectedFlag(false)
                                                                  ->collectTotals()
                                                                  ->getGrandTotal();
        }

        return $total;
    }

    protected function getBandeiras()
    {
        $bandeiras = Mage::getStoreConfig('payment/nitrocielo/bandeiras');        
        return explode(',',$bandeiras);
    }
}

?>