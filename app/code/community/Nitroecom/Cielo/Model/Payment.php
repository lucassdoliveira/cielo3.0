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

class Nitroecom_Cielo_Model_Payment extends Mage_Payment_Model_Method_Abstract
{
    protected $_code          = 'nitrocielo';
    protected $_formBlockType = 'nitrocielo/form';
    protected $_infoBlockType = 'nitrocielo/info';

    //Is this payment method a gateway (online auth/charge) ?
    //=======================================================
    protected $_isGateway               = true;

    //Can authorize online?
    //=====================
    protected $_canAuthorize            = true;

    // Can capture funds online?
    //==========================
    protected $_canCapture              = true;

    //Can capture partial amounts online?
    //===================================
    protected $_canCapturePartial       = true;
    protected $_canCancelInvoice        =  true;

    //Can refund online?
    //==================
    protected $_canRefundInvoicePartial     = true; //isso só funciona no magento EE
    protected $_canRefund                   = true; //o estorno online somente está disponível no magento EE, porém ainda é possivel fazer o estorno para controle no admin

    //Can void transactions online?
    //=============================
    protected $_canVoid                 = true;     //cancelar a transação antes de capturar

    //Can use this payment method in administration panel?
    //====================================================
    protected $_canUseInternal          = true;

    // Can show this payment method as an option on checkout payment page?
    //====================================================================
    protected $_canUseCheckout          = true;

    // Is this payment method suitable for multi-shipping checkout?
    //=============================================================
    protected $_canUseForMultishipping  = true;

    //Can save credit card information for future processing?
    //========================================================
    protected $_canSaveCc               = false;
    protected $_isInitializeNeeded      = false;
    protected $_canReviewPayment        = true; // changed PJS to true

    /**=====================================
     * Assign data to info model instance
     *
     * @param   mixed $data
     * @return  Mage_Payment_Model_Info
     ======================================*/
    public function assignData($data)
    {
        if (!($data instanceof Varien_Object))
            $data = new Varien_Object($data);
        
        $info = $this->getInfoInstance();

        # zera os juros para evitar erros
        $info->getQuote()->setJuros(0.0);
        $info->getQuote()->setBaseJuros(0.0);
        $info->getQuote()->getShippingAddress()->setJuros(0.0);
        $info->getQuote()->getShippingAddress()->setBaseJuros(0.0);
        $info->getQuote()->setTotalsCollectedFlag(false)->collectTotals();

        # zera o valor do desconto para evitar erros
        $info->getQuote()->setDesconto(0.0);
        $info->getQuote()->setBaseDesconto(0.0);
        $info->getQuote()->getShippingAddress()->setDesconto(0.0);
        $info->getQuote()->getShippingAddress()->setBaseDesconto(0.0);
        $info->getQuote()->setTotalsCollectedFlag(false)->collectTotals();

        # Atualiza as informações do Cartão de Crédito
        $info->setCcType($data->getBandeiraCielo())
             ->setAdditionalData($data->getParcelasCielo())
             ->setCcOwner($data->getPortadorCielo())
             ->setCcLast4(substr($data->getNumeroCartaoCielo(), -4))
             ->setCcExpMonth($data->getExpiracaoMesCielo())
             ->setCcExpYear($data->getExpiracaoAnoCielo())
             ->setCcCid($info->encrypt($data->getCodigoSegurancaCielo()))
             ->setCcNumber($info->encrypt(str_replace(' ', '',$data->getNumeroCartaoCielo())));

        $parcela    = $data->getParcelasCielo();
        $valorTotal = $info->getQuote()->getGrandTotal();       

        # Verifica se tem juros e aplica no carrinho. Se o retorno do getJurosAmount for maior que 0, aplica no quote.
        $valorJuros    = Mage::helper('nitrocielo')->getJurosAmount($parcela,$valorTotal);
        $valorDesconto = -(Mage::helper('nitrocielo')->getDiscountAmount($parcela,$valorTotal));

        # CASO TENHA JÚROS APLICA NO PEDIDO
        if($valorJuros>0)
        {
            $info->getQuote()->setJuros($valorJuros);
            $info->getQuote()->setBaseJuros($valorJuros);
            $info->getQuote()->getShippingAddress()->setJuros($valorJuros);
            $info->getQuote()->getShippingAddress()->setBaseJuros($valorJuros);
            $info->getQuote()->setTotalsCollectedFlag(false)->collectTotals();
        } 
        else
        {
            $info->getQuote()->setJuros(0.0);
            $info->getQuote()->setBaseJuros(0.0);
            $info->getQuote()->getShippingAddress()->setJuros(0.0);
            $info->getQuote()->getShippingAddress()->setBaseJuros(0.0);
            $info->getQuote()->setTotalsCollectedFlag(false)->collectTotals();
        }

        # CASO TENHA DESCONTO APLICA NO PEDIDO
        if($valorDesconto < 0)
        {
            $info->getQuote()->setDesconto($valorDesconto);
            $info->getQuote()->setBaseDesconto($valorDesconto);
            $info->getQuote()->getShippingAddress()->setDesconto($valorDesconto);
            $info->getQuote()->getShippingAddress()->setBaseDesconto($valorDesconto);
            $info->getQuote()->setTotalsCollectedFlag(false)->collectTotals();
        }
        else
        {
            $info->getQuote()->setDesconto(0.0);
            $info->getQuote()->setBaseDesconto(0.0);
            $info->getQuote()->getShippingAddress()->setDesconto(0.0);
            $info->getQuote()->getShippingAddress()->setBaseDesconto(0.0);
            $info->getQuote()->setTotalsCollectedFlag(false)->collectTotals();
        }

        # SALVA O PEDIDO
        $info->getQuote()->save();

        return $this;
    }

    public function validate()
    {
        $info     = $this->getInfoInstance();
        $ccNumber = Mage::helper('core')->decrypt($info->getCcNumber());
        $bandeira = $info->getCcType();
        $validado = false;

        if(!Mage::helper('nitrocielo')->validateCcNum($ccNumber))
            Mage::throwException(Mage::helper('payment')->__('O número do cartão digitado não é válido.'));
        else
        {
            # VALIDA AS BANDEIRAS SELECIONADAS
            switch ($bandeira)
            {
                case 'Visa':
                    $ccTypeRegExp = '/^4[0-9]{12}(?:[0-9]{3})?/';
                    $validado     = preg_match($ccTypeRegExp, $ccNumber);
                    break;

                case 'Master':
                    $ccTypeRegExp = '/^5[^078][0-9]{14}/';
                    $validado     = preg_match($ccTypeRegExp, $ccNumber);
                    if(!$validado)
                    {
                        $ccTypeRegExp = '/(222[1-8][0-9]{2}|2229[0-8][0-9]|22299[0-9]|22[3-9][0-9]{3}|2[3-6][0-9]{4}|27[01][0-9]{3}|2720[0-8][0-9]|27209[0-9])[0-9]{10}/';
                        $validado     = preg_match($ccTypeRegExp, $ccNumber);
                    }

                    break;

                case 'Amex':
                    $ccTypeRegExp = '/^3[47][0-9]{13}/';
                    $validado     = preg_match($ccTypeRegExp, $ccNumber);
                    break;

                case 'Discover':
                    $ccTypeRegExp = '/^6(?:011|5[0-9]{2})[0-9]{12}/';
                    $validado     = preg_match($ccTypeRegExp, $ccNumber);
                    break;

                case 'JCB':
                    $ccTypeRegExp = '/^(?:2131|1800|35\d{3})\d{11}/';
                    $validado     = preg_match($ccTypeRegExp, $ccNumber);
                    break;

                case 'Diners':
                    $ccTypeRegExp = '/^3(?:0[0-5]|[68][0-9])[0-9]{11}/';
                    $validado     = preg_match($ccTypeRegExp, $ccNumber);
                    break;

                case 'Elo':
                    $ccTypeRegExp = '/^(40117[8-9]|431274|438935|451416|457393|45763[1-2]|506(699|7[0-6][0-9]|77[0-8])|509\d{3}|504175|627780|636297|636368|65003[1-3]|6500(3[5-9]|4[0-9]|5[0-1])|6504(0[5-9]|[1-3][0-9])|650(4[8-9][0-9]|5[0-2][0-9]|53[0-8])|6505(4[1-9]|[5-8][0-9]|9[0-8])|6507(0[0-9]|1[0-8])|65072[0-7]|6509(0[1-9]|1[0-9]|20)|6516(5[2-9]|[6-7][0-9])|6550([0-1][0-9]|2[1-9]|[3-4][0-9]|5[0-8]))/';

                    $validado = preg_match($ccTypeRegExp, $ccNumber);
                    break;

                case 'Aura':
                    $ccTypeRegExp = '/^5078[0-9]{15}$/';
                    $validado     = preg_match($ccTypeRegExp, $ccNumber);
                    break;
                
                case 'Hipercard':
                    $ccTypeRegExp = '/^(606282\d{10}(\d{3})?)|(3841\d{15})$/';
                    $validado     = preg_match($ccTypeRegExp, $ccNumber);
                    break;

                case 'Hiper':
                    $ccTypeRegExp = '/^(38|60)\d{11}(?:\d{3})?(?:\d{3})?$/';
                    $validado     = preg_match($ccTypeRegExp, $ccNumber);
                    break;
                    
                default:
                    # code...
                    break;
            }
        }

        if(Mage::getStoreConfig('payment/nitrocielo/debug'))
            Mage::log('Numero Cartão: '.$ccNumber.' # Bandeira: '.$bandeira.' # Validado: '.$validado, null, 'nitrocielo.log');

        if(!$validado)
            Mage::throwException(Mage::helper('payment')->__('O número do cartão digitado não é válido'));

        return $this;
    }

    public function order(Varien_Object $payment, $amount)
    {
        return $this;
    }

    public function authorize(Varien_Object $payment, $amount)
    {
        if($amount<=0)
            Mage::throwException(Mage::helper('payment')->__('O valor para autorização deve ser maior que zero'));
        else
        {
            $debug = Mage::getStoreConfig('payment/nitrocielo/debug');
            $info  = $this->getInfoInstance();

            # define as variáveisCcType
            $increment_id  = $payment->getOrder()->getIncrementId();
            $dadosCliente  = Mage::getSingleton('customer/session')->getCustomer();
            $dadosEndereco = Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress();
            $dadosEntrega  = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress();

            $_read = Mage::getSingleton('core/resource')->getConnection('core_read');
            $region_endereco = $_read->fetchRow('SELECT * FROM '.Mage::getConfig()->getTablePrefix().'directory_country_region WHERE default_name = "'.$dadosEndereco->getRegion().'"');

            $cod_entrega = $cod_endereco = null;

            if(isset($region_endereco['code']) && $region_endereco['code'] != '' ){
                $cod_endereco = $region_endereco['code'];
            }


            $region_entrega = $_read->fetchRow('SELECT * FROM '.Mage::getConfig()->getTablePrefix().'directory_country_region WHERE default_name = "'.$dadosEntrega->getRegion().'"');
            
            if(isset($region_entrega['code']) && $region_entrega['code'] != '' ){
                $cod_entrega = $region_entrega['code'];
            }

            $data_nasc = '';
            if($dadosCliente->getDob())
                $data_nasc = $this->formatDob($dadosCliente->getDob());

            $vl_total = number_format($amount,2,'','');

            $currency = Mage::app()->getStore()->getCurrentCurrencyCode();

            $bandeira = $info->getCcType();
            $numcart  = $info->decrypt($info->getCcNumber());
            $titular  = $info->getCcOwner();
            $codseg   = $info->decrypt($info->getCcCid());
            $validade = str_pad($info->getCcExpMonth(),2,'0',STR_PAD_LEFT).'/'.$info->getCcExpYear();
            $parcelas = $info->getAdditionalData();

            if($debug)
            {
                # verifica o ID da sessão
                if(!Mage::getSingleton('checkout/session')->getQuoteId())
                    $quoteId = Mage::getSingleton('adminhtml/session_quote')->getQuoteId();
                else
                    $quoteId = Mage::getSingleton('checkout/session')->getQuoteId();

                $emaildigitado      = $dadosCliente->getEmail();
                $nomedigitado       = $dadosCliente->getFirstname() . ' ' . $dadosCliente->getLastname();
                
                $enderecodigitadoa  = array($dadosEndereco->getStreet(1),
                                            $dadosEndereco->getStreet(2),
                                            $dadosEndereco->getStreet(3),
                                            $dadosEndereco->getStreet(4));

                $cidadedigitado     = $dadosEndereco->getCity();
                $estadodigitado     = $dadosEndereco->getRegion();
                $ufdigitado         = $cod_endereco;
                $telefonedigitado   = $dadosCliente->getTelephone();
                $cepdigitado        = $dadosEndereco->getPostcode();
                $countryId          = $dadosEndereco->getCountryId();

                if(Mage::getStoreConfig('payment/nitrocielo/debug')){
                    # Esse log só funciona se a opção Ativar log em Developer > Log no admin estiver marcada
                    Mage::log(  "===========   Dados do pagamento sendo enviados para autorizacao   ==========
                                Id do pedido:               $increment_id
                                Bandeira do cartao:         $bandeira
                                Portador do cartao:         $titular
                                Valor do pagamento:         $vl_total
                                Quantidade de parcelas:     $parcelas
                                Id da quote:                $quoteId

                                -------------------------------------------------------------------------------
                                DADOS PREENCHIDOS PELO CLIENTE NO CHECKOUT

                                e-mail:                     $emaildigitado
                                Nome:                       $nomedigitado
                                Endereco:                   $enderecodigitadoa[0]
                                                            $enderecodigitadoa[1]
                                                            $enderecodigitadoa[2]
                                                            $enderecodigitadoa[3]
                                Cidade:                     $cidadedigitado                            
                                Estado:                     $estadodigitado
                                Uf:                         $ufdigitado
                                Telefone:                   $telefonedigitado
                                CEP:                        $cepdigitado" ,null, 'nitrocielo.log');
                }
            }
            # DADOS DO PEDIDO
            $sale = new Cielo_API_Sale($increment_id);
            
            # DADOS DO CLIENTE
            $customer = $sale->customer($dadosCliente->getFirstname().' '.$dadosCliente->getLastname())
                                ->setEmail($dadosCliente->getEmail())
                                ->setBirthDate($data_nasc);
            
            # DADOS DE ENDEREÇO
            $customer->address()->setStreet($dadosEndereco->getStreet(1))
                                    ->setNumber($dadosEndereco->getStreet(2))
                                    ->setComplement($dadosEndereco->getStreet(3))
                                    ->setZipCode(Zend_Filter::filterStatic($dadosEndereco->getPostcode(), 'Digits'))
                                    ->setCity($dadosEndereco->getCity())
                                    ->setState($cod_endereco)
                                    ->setCountry($countryId);
            
            # DADOS DE ENTREGA
            if(!$dadosEntrega)
                $dadosEntrega = $dadosEndereco;
            
            $customer->deliveryAddress()->setStreet($dadosEntrega->getStreet(1))
                                            ->setNumber($dadosEntrega->getStreet(2))
                                            ->setComplement($dadosEntrega->getStreet(3))
                                            ->setZipCode(Zend_Filter::filterStatic($dadosEntrega->getPostcode(), 'Digits'))
                                            ->setCity($dadosEntrega->getCity())
                                            ->setState($cod_entrega)
                                            ->setCountry($countryId);
            
            # CRIA INSTANCIA DO PAGAMENTO
            $pagamento = $sale->payment($vl_total, $parcelas);

            # ATRIBUI VALORES DE PAGAMENTO
            $pagamento->setType(Cielo_API_Payment::PAYMENTTYPE_CREDITCARD)
                        ->setInterest('ByMerchant')
                        ->setCurrency($currency)
                        ->setCapture(Mage::getStoreConfig('payment/nitrocielo/payment_action')=='authorize_capture')
                        ->setAuthenticate(false)
                        ->creditCard($codseg, $bandeira)
                        ->setExpirationDate($validade)
                        ->setCardNumber($numcart)
                        ->setHolder($titular);
            
            # ENVIA AS INFORMAÇÕES PARA INTEGRAÇÃO CIELO
            $cielo = Mage::getModel('nitrocielo/cielo');
            $cielo->setEnvironment();
            
            $retorno = $cielo->setAutorizacao($sale);
           
            # Recebe os dados de pagamento da CIELO
            $pagamento = $retorno->getPayment();
            
            if(!$pagamento->getPaymentId() || !$pagamento->getTid())
            {
                # Grava no log do módulo
                if(Mage::getStoreConfig('payment/nitrocielo/debug')){
                    Mage::log( array('payment_id' => '',
                              'code'    => '9999',
                              'message' => 'Nenhum retorno recebido da API CIELO 3.0',
                              'tid'     => ''),null, 'nitrocielo.log');
                }

                Mage::throwException(Mage::helper('payment')->__('Problemas no pagamento via Cartão de Crédito, Você ainda pode selecionar uma outra forma de pagamento'));
            }
            
            # VALIDA A AUTORIZAÇÃO/CAPTURA DA TRANSAÇÃO
            if($pagamento->getStatus()!= 1 && $pagamento->getStatus()!= 2 && $pagamento->getStatus()!= 12)
            {
                # Grava no log do módulo
                if(Mage::getStoreConfig('payment/nitrocielo/debug')){
                    Mage::log( array('payment_id' => (string) $pagamento->getPaymentId(),
                              'code'       => (string) $pagamento->getReturnCode(),
                              'status'     => (string) $pagamento->getStatus(),
                              'message'    => (string) $pagamento->getReturnMessage(),
                              'tid'        => (string) $pagamento->getTid()),null, 'nitrocielo.log');
                }

                Mage::throwException(Mage::helper('payment')->__('Transação não autorizada pela operadora. Entre em contato conosco'));
            }
            
            # TRANSAÇÃO FOI AUTORIZADA/CAPTURADA
            if(Mage::getStoreConfig('payment/nitrocielo/debug')){
                Mage::log( array('payment_id'=> (string) $pagamento->getPaymentId(),
                      'codigo'    => (string) $pagamento->getReturnCode(),
                      'status'    => (string) $pagamento->getStatus(),
                      'message'   => (string) $pagamento->getReturnMessage(),
                      'tid'       => (string) $pagamento->getTid()),null, 'nitrocielo.log');            
            }
            # Atribui os valores
            $payment->setCcTransId($pagamento->getTid());
            //$payment->setQuotePaymentId($pagamento->getPaymentId());
            $payment->setAdditionalInformation('payment_id',  (string) $pagamento->getPaymentId());
            $payment->setAdditionalInformation('payment_links',  (string) json_encode($pagamento->getLinks()));
            $payment->setAdditionalInformation('autorizacao_codigo',  (string) $pagamento->getAuthorizationCode());
            $payment->setAdditionalInformation('autorizacao_mensagem',(string) $pagamento->getReturnMessage());
            $payment->setAdditionalInformation('autorizacao_valor',(string) ($pagamento->getAmount()/100));

            # Verifica se o retorno é status capturado. Se sim é pq captura é automática
            if($pagamento->getReturnCode()==6){
                $payment->setAdditionalInformation('captura_codigo', (string) $retorno->getReturnCode());
                $payment->setAdditionalInformation('captura_mensagem', (string) $pagamento->getReturnMessage());
            }

            $payment->save();

            return $this;
        }
    }

    public function formatDob($date)
    {
        $date = date('Y-m-d', strtotime($date));
        return $date;
    }

    /**========================================================
     * Prepare info instance for save
     * Prepara a instancia info para receber os dados do cartão
     * @return Mage_Payment_Model_Abstract
     ==========================================================*/
    public function prepareSave() {   }

    /**
     * Capture payment abstract method
     *
     * @param Varien_Object $payment
     * @param float $amount
     *
     * @return Mage_Payment_Model_Abstract
     */
    public function capture(Varien_Object $payment, $amount)
    {

        $payment_id = $payment->getAdditionalInformation('payment_id');

        # Se não for a área administrativa então a ação é autorizar e capturar automaticamente
        if(!$payment->getCcTransId() || !$payment_id)
        {
            $this->authorize($payment, $amount);
            return $this;
        }
        if(!$this->canCapture())
           Mage::throwException(Mage::helper('payment')->__('Esse pedido não pode ser capturado.'));
        
        # ENVIA AS INFORMAÇÕES PARA INTEGRAÇÃO CIELO
        $cielo = Mage::getModel('nitrocielo/cielo');
        $cielo->setEnvironment();

        
        $valor      = number_format($amount, 2, '', '');

        $retorno = $cielo->setCaptura($payment_id);
        # Verifica se o retorno é status capturado. Se sim é pq captura é automática
        if(Mage::getStoreConfig('payment/nitrocielo/debug')){
            Mage::log( $retorno->getReturnCode() ,null, 'nitrocielo.log');
            Mage::log( $retorno->getReturnMessage() ,null, 'nitrocielo.log');
            Mage::log( $retorno  ,null, 'nitrocielo.log');
        }


        if($retorno->getReturnCode()!=6)
        {
            # Grava no log do módulo
            if(Mage::getStoreConfig('payment/nitrocielo/debug')){
                Mage::log( array('payment_id' => '',
                      'code'    => (string) $retorno->getReturnCode(),
                      'message' => (string) $retorno->getReturnMessage(),
                      'tid'     => ''),null, 'nitrocielo.log');
            }

            Mage::throwException(Mage::helper('payment')->__('Problemas ao capturar o pedido'));
        }

        $payment->setAdditionalInformation('captura_codigo', (string) $retorno->getReturnCode());
        $payment->setAdditionalInformation('captura_mensagem', (string) $retorno->getReturnMessage());
        $payment->save();

        # TRANSAÇÃO FOI CAPTURADA
        if(Mage::getStoreConfig('payment/nitrocielo/debug')){
            Mage::log( array('payment_id'=> (string) $payment_id,
              'codigo'    => (string) $retorno->getReturnCode(),
              'status'    => (string) $retorno->getStatus(),
              'message'   => (string) $retorno->getReturnMessage(),
              'tid'       => (string) $payment->getCcTransId()),null, 'nitrocielo.log');
        }

        return $this;
    }

    public function cancelamento($observer)
    {

        $payment     = $observer->getEvent()->getPayment();
        $payment_id  = $payment->getAdditionalInformation('payment_id');
        $valor       = number_format($payment->getAmountAuthorized(), 2, '', '');

        # Caso o pedido não tenha sido finalizado pelo módulo cielo
        if($payment->getMethod() != 'nitrocielo'  ){
            
            return true;
        }

         # Caso o pedido não tenha sido finalizado pelo módulo cielo
        if( ! $payment_id ){
             Mage::getSingleton('adminhtml/session')->addWarning( "Pagamento não cancelado na Cielo" ); 
            return true;
        }
        
        # ENVIA AS INFORMAÇÕES PARA INTEGRAÇÃO CIELO
        $cielo = Mage::getModel('nitrocielo/cielo');
        $cielo->setEnvironment();

        $retorno = $cielo->setCancelamento($payment_id, $valor);

        # Verifica se o retorno é status capturado. Se sim é pq captura é automática
        if($retorno->getStatus()!=10 || $retorno->getReturnCode()!=9)
        {
            # Grava no log do módulo
            if(Mage::getStoreConfig('payment/nitrocielo/debug')){
                Mage::log( array('payment_id' => '',
                      'code'    => (string) $retorno->getReturnCode(),
                      'message' => (string) $retorno->getReturnMessage(),
                      'tid'     => ''),null, 'nitrocielo.log');
            }

            Mage::throwException(Mage::helper('payment')->__('Problemas ao cancelar o pedido'));
        }

        $payment->setAdditionalInformation('cancelamento_mensagem', (string) $retorno->getReturnMessage());
        $payment->save();

        # TRANSAÇÃO FOI CAPTURADA
        if(Mage::getStoreConfig('payment/nitrocielo/debug')){
            Mage::log( array('payment_id'=> (string) $payment->getQuotePaymentId(),
              'codigo'    => (string) $retorno->getReturnCode(),
              'status'    => (string) $retorno->getStatus(),
              'message'   => (string) $retorno->getReturnMessage(),
              'tid'       => (string) $payment->getCcTransId()),null, 'nitrocielo.log');
        }

        return $this;
    }

    public function estorno($observer) { 
             Mage::getSingleton('adminhtml/session')->addWarning( "Pagamento não Reembolsado na Cielo" );
    }
}
