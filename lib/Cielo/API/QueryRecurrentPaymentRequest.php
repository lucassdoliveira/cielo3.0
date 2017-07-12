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

class Cielo_API_QueryRecurrentPaymentRequest extends Cielo_API_AbstractSaleRequest
{
    private $environment;

    public function __construct(Cielo_Merchant $merchant, Cielo_Environment $environment)
    {
        parent::__construct($merchant);

        $this->environment = $environment;
    }

    public function execute($recurrentPaymentId)
    {
        $url = $this->environment->getApiQueryURL().'1/RecurrentPayment/'.$recurrentPaymentId;

        return $this->sendRequest('GET', $url);
    }

    protected function unserialize($json)
    {
        return Cielo_API_RecurrentPayment::fromJson($json);
    }
}
