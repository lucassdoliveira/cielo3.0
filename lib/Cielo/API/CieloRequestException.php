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

class Cielo_API_CieloRequestException extends \Exception
{
    private $cieloError;

    public function __construct($message, $code, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getCieloError()
    {
        return $this->cieloError;
    }

    public function setCieloError(Cielo_API_CieloError $cieloError)
    {
        $this->cieloError = $cieloError;
        return $this;
    }
}