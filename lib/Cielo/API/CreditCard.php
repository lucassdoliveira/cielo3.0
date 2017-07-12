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

class Cielo_API_CreditCard implements \JsonSerializable
{
    private $cardNumber;
    private $holder;
    private $expirationDate;
    private $securityCode;
    private $saveCard = false;
    private $brand;
    private $cardToken;

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function populate(\stdClass $data)
    {
        $this->cardNumber     = isset($data->CardNumber)? $data->CardNumber: null;
        $this->holder         = isset($data->Holder)? $data->Holder: null;
        $this->expirationDate = isset($data->ExpirationDate)? $data->ExpirationDate: null;
        $this->securityCode   = isset($data->SecurityCode)? $data->SecurityCode: null;
        $this->saveCard       = isset($data->SaveCard)? !!$data->SaveCard: false;
        $this->brand          = isset($data->Brand)? $data->Brand: null;
        $this->cardToken      = isset($data->CardToken)? $data->CardToken: null;
    }

    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
        return $this;
    }

    public function getHolder()
    {
        return $this->holder;
    }

    public function setHolder($holder)
    {
        $this->holder = $holder;
        return $this;
    }

    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    public function getSecurityCode()
    {
        return $this->securityCode;
    }

    public function setSecurityCode($securityCode)
    {
        $this->securityCode = $securityCode;
        return $this;
    }

    public function getSaveCard()
    {
        return $this->saveCard;
    }

    public function setSaveCard($saveCard)
    {
        $this->saveCard = $saveCard;
        return $this;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;
        return $this;
    }

    public function getCardToken()
    {
        return $this->cardToken;
    }

    public function setCardToken($cardToken)
    {
        $this->cardToken = $cardToken;
        return $this;
    }
}