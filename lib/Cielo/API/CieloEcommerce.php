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

class Cielo_API_CieloEcommerce
{
    private $merchant;
    private $environment;

    /**
     * Create an instance of CieloEcommerce choosing the environment where the
     * requests will be send
     *
     * @param
     *            \Nitroecom\Cielo\Model\Library\Merchant merchant
     *            The merchant credentials
     * @param
     *            \Nitroecom\Cielo\Model\Library\Environment environment
     *            The environment: {@link Environment::production()} or
     *            {@link Environment::sandbox()}
     */
    public function __construct(Cielo_Merchant $merchant, Cielo_Environment $environment=null)
    {
        if (!$environment)
            $environment = Cielo_API_Environment::production();

        $this->merchant    = $merchant;
        $this->environment = $environment;
    }

    /**
     * Send the Sale to be created and return the Sale with tid and the status
     * returned by Cielo.
     *
     * @param \Nitroecom\Cielo\Model\Library\Sale $sale
     *            The preconfigured Sale
     * @return \Nitroecom\Cielo\Model\Library\Sale The Sale with authorization, tid, etc. returned by Cielo.
     * @throws CieloRequestException if anything gets wrong.
     * @see <a href=
     *      "https://developercielo.github.io/Webservice-3.0/english.html#error-codes">Error
     *      Codes</a>
     */
    public function createSale(Cielo_API_Sale $sale)
    {        
        $createSaleRequest = new Cielo_API_CreateSaleRequest(
            $this->merchant,
            $this->environment
        );

        return $createSaleRequest->execute($sale);
    }

    /**
     * Query a Sale on Cielo by paymentId
     *
     * @param string $paymentId
     *            The paymentId to be queried
     * @return \Nitroecom\Cielo\Model\Library\Sale The Sale with authorization, tid, etc. returned by Cielo.
     * @throws CieloRequestException if anything gets wrong.
     * @see <a href=
     *      "https://developercielo.github.io/Webservice-3.0/english.html#error-codes">Error
     *      Codes</a>
     */
    public function getSale($paymentId)
    {
        $querySaleRequest = new Cielo_API_QuerySaleRequest(
            $this->merchant,
            $this->environment
        );
        
        return $querySaleRequest->execute($paymentId);
    }

    /**
     * Query a RecurrentPayment on Cielo by RecurrentPaymentId
     *
     * @param string $recurrentPaymentId
     *            The RecurrentPaymentId to be queried
     * @return \Nitroecom\Cielo\Model\Library\RecurrentPayment The RecurrentPayment with authorization, tid, etc. returned by Cielo.
     * @throws CieloRequestException if anything gets wrong.
     * @see <a href=
     *      "https://developercielo.github.io/Webservice-3.0/english.html#error-codes">Error
     *      Codes</a>
     */
    public function getRecurrentPayment($recurrentPaymentId)
    {
        $queryRecurrentPaymentRequest = new Cielo_API_QueryRecurrentPaymentRequest(
            $this->merchant,
            $this->environment
        );

        return $queryRecurrentPaymentRequest->execute($recurrentPaymentId);
    }

    /**
     * Cancel a Sale on Cielo by paymentId and speficying the amount
     *
     * @param string $paymentId
     *            The paymentId to be queried
     * @param integer $amount
     *            Order value in cents
     * @return \Nitroecom\Cielo\Model\Library\Sale The Sale with authorization, tid, etc. returned by Cielo.
     * @throws CieloRequestException if anything gets wrong.
     * @see <a href=
     *      "https://developercielo.github.io/Webservice-3.0/english.html#error-codes">Error
     *      Codes</a>
     */
    public function cancelSale($paymentId, $amount = null)
    {
        $updateSaleRequest = new Cielo_API_UpdateSaleRequest(
            'void',
            $this->merchant,
            $this->environment
        );

        $updateSaleRequest->setAmount($amount);

        return $updateSaleRequest->execute($paymentId);
    }

    /**
     * Capture a Sale on Cielo by paymentId and specifying the amount and the
     * serviceTaxAmount
     *
     * @param string $paymentId
     *            The paymentId to be captured
     * @param integer $amount
     *            Amount of the authorization to be captured
     * @param integer $serviceTaxAmount
     *            Amount of the authorization should be destined for the service
     *            charge
     * @return \Nitroecom\Cielo\Model\Library\Payment The captured Payment.
     *
     * @throws CieloRequestException if anything gets wrong.
     * @see <a href=
     *      "https://developercielo.github.io/Webservice-3.0/english.html#error-codes">Error
     *      Codes</a>
     */
    public function captureSale($paymentId, $amount = null, $serviceTaxAmount = null)
    {
        $updateSaleRequest = new Cielo_API_UpdateSaleRequest(
            'capture',
            $this->merchant,
            $this->environment
        );
        
        $updateSaleRequest->setAmount($amount);
        $updateSaleRequest->setServiceTaxAmount($serviceTaxAmount);

        return $updateSaleRequest->execute($paymentId);
    }
}
