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

class Nitroecom_Cielo_Block_Info extends Mage_Payment_Block_Info
{
    /**
     * Init default template for block
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('Nitroecom_Cielo/info.phtml');
    }

    public function getTid()
    {
        return $this->getInfo()->getCcTransId();
    }

    public function getName()
    {
        return $this->getInfo()->getCcOwner();
    }

    public function getParcelas()
    {
        return $this->getInfo()->getAdditionalData();
    }

    public function getQuatroUltimosNumeros()
    {
        return $this->getInfo()->getCcLast4();
    }

    public function getXmlRetorno($val=null)
    {
        $retorno = $this->getInfo()->getAdditionalInformation();
        if($val)
            return isset($retorno[$val])?$retorno[$val]:null;
        
        return $retorno;
    }

    public function getValidade()
    {
        return $this->getInfo()->getCcExpMonth().'/'.$this->getInfo()->getCcExpYear();
    }

    public function getCcType()
    {
        return $this->getInfo()->getCcType();
    }

    public  function getEstornos()
    {
        return ($this->getInfo()->getAmountRefunded()>0);
    }

    public  function getUrlxml()
    {
        return Mage::helper("adminhtml")->getUrl('backendcielo/admin/xmlcielo', array('payment_id' => $this->getXmlRetorno('payment_id')));
    }
}

?>
