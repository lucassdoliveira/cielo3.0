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

class Nitroecom_Cielo_Model_Cielo extends Mage_Core_Model_Abstract
{
    public $ambiente     = 'homologacao';
    public $merchant_id  = 'daaec334-ac85-4c63-9564-f4a31be7b19b';
    public $merchant_key = 'LTFYLLACRDYCIKMHTXBRVKGQYGDHOOVTCKGZUKRK';

    public $environment  = null;

    public function __construct()
    {
        # Seta variaveis de ambiente para integração CIELO
        $merchant_id = Mage::getStoreConfig('payment/nitrocielo/merchant_id');
        if($merchant_id)
            $this->merchant_id = $merchant_id;

        $merchant_key = Mage::getStoreConfig('payment/nitrocielo/merchant_key');
        if($merchant_key)
            $this->merchant_key = $merchant_key;

        $this->ambiente = Mage::getStoreConfig('payment/nitrocielo/ambiente');
    }

    public function setEnvironment()
    {
        # Configure o ambiente (Produção / Teste)
        if($this->ambiente=='homologacao')
            $this->environment = Cielo_Environment::sandbox();
        elseif($this->ambiente=='producao')
            $this->environment = Cielo_Environment::production();
    }

    public function setAutorizacao($sale)
    {
        try
        {
            # Configure seu merchant
            $merchant = new Cielo_Merchant(
                $this->getMerchantId(),
                $this->getMerchantKey()
            );

            # Configure o SDK com seu merchant e o ambiente apropriado para criar a venda
            $retorno = new Cielo_API_CieloEcommerce(
                $merchant,
                $this->environment
            );

            return $retorno->createSale($sale);
        }
        catch(Cielo_API_CieloRequestException $e)
        {
            // Em caso de erros de integração, podemos tratar o erro aqui.
            // os códigos de erro estão todos disponíveis no manual de integração.
            $error = $e->getCieloError();

            if(Mage::getStoreConfig('payment/apelidocielo/debug'))
                Mage::log('Cartão: '.$error->getCode().' '.$error->getMessage(), null, 'nitrocielo.log');

            //throw new Exception($error->getCode().' - '.$error->getMessage());
            Mage::throwException(Mage::helper('payment')->__($error->getCode().' - '.$error->getMessage()));
        }
    }

    public function setCaptura($paymentId, $valor)
    {
        try
        {
            # Configure seu merchant
            $merchant = new Cielo_Merchant(
                $this->getMerchantId(),
                $this->getMerchantKey()
            );

            # Com o ID do pagamento, podemos fazer sua captura, se ela não tiver sido capturada ainda
            $retorno = new Cielo_API_CieloEcommerce(
                $merchant,
                $this->environment
            );

            return $retorno->captureSale($paymentId, $valor, 0);
        }
        catch(Cielo_API_CieloRequestException $e)
        {
            // Em caso de erros de integração, podemos tratar o erro aqui.
            // os códigos de erro estão todos disponíveis no manual de integração.
            $error = $e->getCieloError();

            if(Mage::getStoreConfig('payment/apelidocielo/debug'))
                Mage::log('Cartão: '.$error->getCode().' '.$error->getMessage(), null, 'nitrocielo.log');

            Mage::throwException(Mage::helper('payment')->__($error->getCode().' - '.$error->getMessage()));
        }
    }

    public function setCancelamento($paymentId, $valor)
    {
        try
        {
            # Configure seu merchant
            $merchant = new Cielo_Merchant(
                $this->getMerchantId(),
                $this->getMerchantKey()
            );

            # Chama a função de cancelamento do pagamento dopedido
            $retorno = new Cielo_API_CieloEcommerce(
                $merchant,
                $this->environment
            );

            return $retorno->cancelSale($paymentId, $valor);
        }
        catch(Cielo_API_CieloRequestException $e)
        {
            // Em caso de erros de integração, podemos tratar o erro aqui.
            // os códigos de erro estão todos disponíveis no manual de integração.
            $error = $e->getCieloError();

            if(Mage::getStoreConfig('payment/apelidocielo/debug'))
                Mage::log('Cartão: '.$error->getCode().' '.$error->getMessage(), null, 'nitrocielo.log');

            Mage::throwException(Mage::helper('payment')->__($error->getCode().' - '.$error->getMessage()));
        }
    }

    public function getMerchantId()
    {
        return $this->merchant_id;
    }

    public function getMerchantKey()
    {
        return $this->merchant_key;
    }

    public function setLog($msg)
    {
        //Mage::log($msg,null, 'nitroecom_cielo.log');
    }
}