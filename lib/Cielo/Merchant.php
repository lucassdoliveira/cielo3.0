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

class Cielo_Merchant
{
    private $id;
    private $key;
    
    public function __construct($id, $key)
    {
        $this->id = $id;
        $this->key = $key;
    }
    
    /**
     * Gets the merchant identification number
     *
     * @return the merchant identification number on Cielo
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Gets the merchant identification key
     *
     * @return the merchant identification key on Cielo
     */
    public function getKey()
    {
        return $this->key;
    }
}