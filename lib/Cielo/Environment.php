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

final class Cielo_Environment
{
    private $api;
    private $apiQuery;

    private function __construct($api, $apiQuery)
    {
        $this->api      = $api;
        $this->apiQuery = $apiQuery;
    }

    public static function sandbox()
    {
        $vpar_api 	   = 'https://apisandbox.cieloecommerce.cielo.com.br/';
        $vpar_apiQuery = 'https://apiquerysandbox.cieloecommerce.cielo.com.br/';

        return new Cielo_Environment($vpar_api, $vpar_apiQuery);
    }

    public static function production()
    {
        $vpar_api  	   = 'https://api.cieloecommerce.cielo.com.br/';
        $vpar_apiQuery = 'https://apiquery.cieloecommerce.cielo.com.br/';
        
        return new Cielo_Environment($vpar_api, $vpar_apiQuery);
    }
    
    public function getApiUrl()
    {
        return $this->api;
    }
    
    public function getApiQueryURL()
    {
        return $this->apiQuery;
    }
}