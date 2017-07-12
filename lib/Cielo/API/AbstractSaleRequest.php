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

abstract class Cielo_API_AbstractSaleRequest
{
    protected $merchant;

    public function __construct(Cielo_Merchant $merchant)
    {
        $this->merchant = $merchant;
    }

    public abstract function execute($param);

    protected abstract function unserialize($json);
    
    protected function sendRequest($method, $url, Cielo_API_Sale $sale = null)
    {
        $headers = [
            'Accept: application/json',
            'Accept-Encoding: gzip',
            'User-Agent: CieloEcommerce/3.0 PHP SDK',
            'MerchantId: '.$this->merchant->getId(),
            'MerchantKey: '.$this->merchant->getKey(),
            'RequestId: '.uniqid()
        ];
        
        $curl = curl_init($url);
        
        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true); xxx Arrumei para poder testar xxx
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        switch ($method)
        {
            case 'GET':
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        }
        
        if ($sale !== null)
        {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($sale));
            
            $headers[] = 'Content-Type: application/json';
        } else {
            $headers[] = 'Content-Length: 0';
        }
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        $response = curl_exec($curl);
        
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        if (curl_errno($curl))
            throw new \RuntimeException('Curl error: '.curl_error($curl));
        
        curl_close($curl);
        
        return $this->readResponse($statusCode, $response);
    }
    
    protected function readResponse($statusCode, $responseBody)
    {
        $unserialized = null;
        
        switch ($statusCode)
        {
            case 200:
            case 201:
                $unserialized = $this->unserialize($responseBody);
                break;
            case 400:
                $exception = null;
                $response = json_decode($responseBody);
                
                foreach ($response as $error)
                {
                    $cieloError = new Cielo_API_CieloError($error->Message, $error->Code);
                    $exception  = new Cielo_API_CieloRequestException('Request Error', $statusCode, $exception);
                    $exception->setCieloError($cieloError);
                }
                
                throw $exception;
            case 404:
                throw new Cielo_API_CieloRequestException('Resource not found', 404, null);
            default:
                throw new Cielo_API_CieloRequestException('Unknown status', $statusCode);
        }
        
        return $unserialized;
    }
}