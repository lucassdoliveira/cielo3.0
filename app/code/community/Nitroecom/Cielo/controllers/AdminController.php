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

class Nitroecom_Cielo_AdminController extends Mage_Adminhtml_Controller_Action
{
    public function xmlcieloAction()
    {
        $this->loadLayout(false);

        $payment_id = Mage::app()->getRequest()->getParam('payment_id');
        if($payment_id == '' || $payment_id == null)
        {
            $this->getResponse()->setBody("O payment_id da transação não foi passado como parâmetro.");
            return;
        }

        try
        {
            $cielo = Mage::getModel('nitrocielo/cielo');
            $cielo->setEnvironment();
            $retorno = $cielo->getTransacao($payment_id);

            $this->getResponse()->setHeader('Content-Type','text/xml');
            $this->getResponse()->setBody($this->objectToXml($retorno->jsonSerialize()));

        } catch (Exception $e) {
            $this->getResponse()->setBody("Erro ao consultar pedido na Cielo");
        }
        
        return;
    }

    /**
    * Transformar o objeto retornado da Cielo em xml para melhor visualização
    * @param Cielo_API_Sale $result 
    * @param SimpleXMLElement|null $rootElement 
    * @param SimpleXMLElement|null $xml 
    * @return string
    */
    private function objectToXml($result, $rootElement = null, $xml = null)
    {
        $_xml = $xml; 
        if ($_xml === null)
            $_xml = new SimpleXMLElement($rootElement !== null ? $rootElement : '<root/>');
        
        $array = array();
        if(is_object($result) || is_array($result))
        {
            foreach ($result as $key => $v)
            {
                $k = $key;
                if(is_numeric($key))
                    $k = 'item';

                if (is_object($v))
                {
                    if(method_exists($v,'jsonSerialize'))
                        $this->objectToXml($v->jsonSerialize(), $k, $_xml->addChild($k));
                    else
                        $this->objectToXml(get_object_vars($v), $k, $_xml->addChild($k));
                }
                elseif(is_array($v))
                    $this->objectToXml($v, $k, $_xml->addChild($k));
                else
                    $_xml->addChild($k, $v);
            }
            
            return $_xml->asXML();

        }
        else
            return $result;
    }
}

?>
