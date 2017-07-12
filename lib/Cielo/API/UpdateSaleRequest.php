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
 * @package    Cielo_API
 * @copyright  Copyright (c) 2017 Nitroecom (https://www.nitroecom.com.br)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Lucas Oliveira - Nitro e-com <www.nitroecom.com.br>
 */

class Cielo_API_UpdateSaleRequest extends Cielo_API_AbstractSaleRequest
{
    private $environment;
    private $type;
    private $serviceTaxAmount;
    private $amount;

    public function __construct($type, Cielo_Merchant $merchant, Cielo_Environment $environment)
    {
        parent::__construct($merchant);
        
        $this->environment = $environment;
        $this->type        = $type;
    }

    public function execute($paymentId)
    {
        $url = $this->environment->getApiUrl().'1/sales/'.$paymentId.'/'.$this->type;
        $params = [];
        
        if ($this->amount != null)
            $params['amount'] = $this->amount;
        
        if ($this->serviceTaxAmount != null)
            $params['serviceTaxAmount'] = $this->serviceTaxAmount;
        
        $url .= '?'.http_build_query($params);
        
        return $this->sendRequest('PUT', $url);
    }

    protected function unserialize($json)
    {
        return Cielo_API_Payment::fromJson($json);
    }

    public function getServiceTaxAmount()
    {
        return $this->serviceTaxAmount;
    }

    public function setServiceTaxAmount($serviceTaxAmount)
    {
        $this->serviceTaxAmount = $serviceTaxAmount;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }
}