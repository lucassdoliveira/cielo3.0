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

class Cielo_API_Customer implements \JsonSerializable
{
    private $name;
    private $email;
    private $birthDate;
    private $identity;
    private $identityType;
    private $address;
    private $deliveryAddress;

    public function __construct($name = null)
    {
        $this->setName($name);
    }
    
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function populate(\stdClass $data)
    {
        $this->name         = isset($data->Name) ? $data->Name : null;
        $this->email        = isset($data->Email) ? $data->Email : null;
        $this->birthDate    = isset($data->Birthdate) ? $data->Birthdate : null;
        $this->identity     = isset($data->Identity) ? $data->Identity : null;
        $this->identityType = isset($data->IdentityType) ? $data->IdentityType : null;
        
        if (isset($data->Address))
        {
            $this->address = new Cielo_API_Address();
            $this->address->populate($data->Address);
        }
        
        if (isset($data->DeliveryAddress))
        {
            $this->deliveryAddress = new Cielo_API_Address();
            $this->deliveryAddress->populate($data->DeliveryAddress);
        }
    }

    public function address()
    {
        $address = new Cielo_API_Address();
        $this->setAddress($address);
        
        return $address;
    }

    public function deliveryAddress()
    {
        $address = new Cielo_API_Address();
        $this->setDeliveryAddress($address);
        
        return $address;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getBirthDate()
    {
        return $this->birthDate;
    }

    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getIdentity()
    {
        return $this->identity;
    }

    public function setIdentity($identity)
    {
        $this->identity = $identity;
        return $this;
    }

    public function getIdentityType()
    {
        return $this->identityType;
    }

    public function setIdentityType($identityType)
    {
        $this->identityType = $identityType;
        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress($deliveryAddress)
    {
        $this->deliveryAddress = $deliveryAddress;
        return $this;
    }
}