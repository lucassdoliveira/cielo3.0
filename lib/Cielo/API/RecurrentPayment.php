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

class Cielo_API_RecurrentPayment implements \JsonSerializable
{
    const INTERVAL_MONTHLY    = 'Monthly';
    const INTERVAL_BIMONTHLY  = 'Bimonthly';
    const INTERVAL_QUARTERLY  = 'Quarterly';
    const INTERVAL_SEMIANNUAL = 'SemiAnnual';
    const INTERVAL_ANNUAL     = 'Annual';
 
    private $authorizeNow;
    private $recurrentPaymentId;
    private $nextRecurrency;
    private $startDate;
    private $endDate;
    private $interval;
    private $amount;
    private $country;
    private $createDate;
    private $currency;
    private $currentRecurrencyTry;
    private $provider;
    private $recurrencyDay;
    private $successfulRecurrences;
    private $links;
    private $recurrentTransactions;
    private $reasonCode;
    private $reasonMessage;
    private $status;
    
    public function __construct($authorizeNow = true)
    {
        $this->setAuthorizeNow($authorizeNow);
    }
    
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
        
    public function populate(\stdClass $data)
    {
        $this->authorizeNow         = isset($data->AuthorizeNow)? !!$data->AuthorizeNow: false;
        $this->recurrentPaymentId   = isset($data->RecurrentPaymentId)? $data->RecurrentPaymentId: null;
        $this->nextRecurrency       = isset($data->NextRecurrency)? $data->NextRecurrency: null;
        $this->startDate            = isset($data->StartDate)? $data->StartDate: null;
        $this->endDate              = isset($data->EndDate)? $data->EndDate: null;
        $this->interval             = isset($data->Interval)? $data->Interval: null;
        $this->amount               = isset($data->Amount)? $data->Amount: null;
        $this->country              = isset($data->Country)? $data->Country: null;
        $this->createDate           = isset($data->CreateDate)? $data->CreateDate: null;
        $this->currency             = isset($data->Currency)? $data->Currency: null;
        $this->currentRecurrencyTry = isset($data->CurrentRecurrencyTry)? $data->CurrentRecurrencyTry: null;
        $this->provider             = isset($data->Provider)? $data->Provider: null;
        $this->recurrencyDay        = isset($data->RecurrencyDay)? $data->RecurrencyDay: null;
        $this->successfulRecurrences= isset($data->SuccessfulRecurrences)? $data->SuccessfulRecurrences: null;
        $this->links                = isset($data->Interval)? $data->Interval: [];
        $this->recurrentTransactions= isset($data->Interval)? $data->Interval: [];
        $this->reasonCode           = isset($data->ReasonCode)? $data->ReasonCode: null;
        $this->reasonMessage        = isset($data->ReasonMessage)? $data->ReasonMessage: null;
        $this->status               = isset($data->Status)? $data->Status: null;
    }
    
    public static function fromJson($json)
    {
        $object = json_decode($json);

        $recurrentPayment = new Cielo_API_RecurrentPayment();

        if (isset($object->RecurrentPayment))
            $recurrentPayment->populate($object->RecurrentPayment);

        return $recurrentPayment;
    }
    
    public function getRecurrentPaymentId()
    {
        return $this->recurrentPaymentId;
    }

    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    public function getReasonMessage()
    {
        return $this->reasonMessage;
    }

    public function gerNextRecurrency()
    {
        return $this->nextRecurrency;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getCreateDate()
    {
        return $this->createDate;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getCurrentRecurrencyTry()
    {
        return $this->currentRecurrencyTry;
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function getRecurrencyDay()
    {
        return $this->recurrencyDay;
    }

    public function getSuccessfulRecurrences()
    {
        return $this->successfulRecurrences;
    }
    
    public function getAuthorizeNow()
    {
        return $this->authorizeNow;
    }

    public function setAuthorizeNow($authorizeNow)
    {
        $this->authorizeNow = $authorizeNow;
        return $this;
    }
    
    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getInterval()
    {
        return $this->interval;
    }

    public function setInterval($interval)
    {
        $this->interval = $interval;
        return $this;
    }
}
