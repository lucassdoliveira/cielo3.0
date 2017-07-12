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

class Cielo_API_Sale implements \JsonSerializable
{
    private $merchantOrderId;
    private $customer;
    private $payment;

    public function __construct($merchantOrderId=null)
    {
        $this->setMerchantOrderId($merchantOrderId);
    }
        
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
    
    public function populate(\stdClass $data)
    {
        $dataProps = get_object_vars($data);
        
        if(isset($dataProps['Customer']))
        {
            $this->customer = new Cielo_API_Customer();
            $this->customer->populate($data->Customer);
        }
        
        if(isset($dataProps['Payment']))
        {
            $this->payment = new Cielo_API_Payment();
            $this->payment->populate($data->Payment);
        }
        
        if(isset($dataProps['MerchantOrderId']))
            $this->merchantOrderId = $data->MerchantOrderId;
    }

    public static function fromJson($json)
    {
        $object = json_decode($json);
        
        //Mage::log($object, null, 'nitrocielo.log');
        
        $sale = new Cielo_API_Sale();
        $sale->populate($object);
        
        return $sale;
    }
    
    public function customer($name)
    {
        $customer = new Cielo_API_Customer($name);
        $this->setCustomer($customer);
        
        return $customer;
    }
    
    public function payment($amount, $installments=1)
    {
        $payment = new Cielo_API_Payment(
            $amount,
            $installments
        );
        
        $this->setPayment($payment);
        
        return $payment;
    } 

    public function getMerchantOrderId()
    {
        return $this->merchantOrderId;
    }
    
    public function setMerchantOrderId($merchantOrderId)
    {
        $this->merchantOrderId = $merchantOrderId;
        return $this;
    }
    
    public function getCustomer()
    {
        return $this->customer;
    }
    
    public function setCustomer(Cielo_API_Customer $customer)
    {
        $this->customer = $customer;

        return $this;
    }

    public function getPayment()
    {
        return $this->payment;
    }

    public function setPayment(Cielo_API_Payment $payment)
    {
        $this->payment = $payment;
        return $this;
    }
}