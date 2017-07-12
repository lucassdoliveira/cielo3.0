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

class Nitroecom_Cielo_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Validacao retirada do modelo cc da versao 1.7 do Magento
     *
     * @param   string $cc_number
     * @return  bool
     */
    public function validateCcNum($ccNumber)
    {
        $cardNumber = strrev($ccNumber);
        $numSum = 0;

        for ($i=0; $i<strlen($cardNumber); $i++)
        {
            $currentNum = substr($cardNumber, $i, 1);

            /**
             * Double every second digit
             */
            if ($i % 2 == 1)
                $currentNum *= 2;

            /**
             * Add digits of 2-digit numbers together
             */
            if ($currentNum > 9)
            {
                $firstNum   = $currentNum % 10;
                $secondNum  = ($currentNum - $firstNum) / 10;
                $currentNum = $firstNum + $secondNum;
            }

            $numSum += $currentNum;
        }

        /**
         * If the total has no remainder it's OK
         */
        return ($numSum % 10 == 0);
    }

    public function getJurosAmount($parcela, $valortotal)
    {
        $parcelasSemJuros       = Mage::getBlockSingleton('nitrocielo/form')->getParcelassemJuros();
        $numeroMaximodeParcelas = Mage::getBlockSingleton('nitrocielo/form')->getMaximoParcelas();
        $juros_parcela          = Mage::getStoreConfig('payment/nitrocielo/juros_parcela');
        $jurosAmount            = 0;

        if($parcela > $parcelasSemJuros)
        {
            $jurosconvertido = $juros_parcela / 100;
            $parcelacomjuros = $valortotal*$jurosconvertido*pow((1+$jurosconvertido),$parcela)/(pow((1+$jurosconvertido),$parcela)-1);
            $parcelasemjuros = round($valortotal,2)/round($parcela,2);
            $jurosAmount     = (float)$parcelacomjuros - (float)$parcelasemjuros;

            return (float)$jurosAmount*$parcela;
        }
        else return 0;
    }

    public function getDiscountAmount($parcela, $valorTotal)
    {
        if($parcela == 1)
        {
            $desconotavista   = Mage::getStoreConfig('payment/nitrocielo/valor_desconto_avista');
            $desconotavista   = $desconotavista/100;
            $valorcomDesconto = $valorTotal * (1 - $desconotavista);
            $valordodesconto  = $valorTotal - $valorcomDesconto;

            return $valordodesconto;
        }
    }

    public function setDiscountQuote($quote, $discountAmount, $description)
    {
        $quoteid = $quote->getId();
        if($quoteid)
        {
            if($discountAmount>0)
            {
                $total = $quote->getBaseSubtotal();
                $quote->setSubtotal(0);
                $quote->setBaseSubtotal(0);

                $quote->setSubtotalWithDiscount(0);
                $quote->setBaseSubtotalWithDiscount(0);

                $quote->setGrandTotal(0);
                $quote->setBaseGrandTotal(0);

                $canAddItems = $quote->isVirtual()? ('billing') : ('shipping');

                foreach ($quote->getAllAddresses() as $address)
                {
                    $address->setSubtotal(0);
                    $address->setBaseSubtotal(0);
                    $address->setGrandTotal(0);
                    $address->setBaseGrandTotal(0);
                    $address->collectTotals();

                    $quote->setSubtotal((float) $quote->getSubtotal() + $address->getSubtotal());
                    $quote->setBaseSubtotal((float) $quote->getBaseSubtotal() + $address->getBaseSubtotal());
                    $quote->setSubtotalWithDiscount((float) $quote->getSubtotalWithDiscount() + $address->getSubtotalWithDiscount());
                    $quote->setBaseSubtotalWithDiscount((float) $quote->getBaseSubtotalWithDiscount() + $address->getBaseSubtotalWithDiscount());
                    $quote->setGrandTotal((float) $quote->getGrandTotal() + $address->getGrandTotal());
                    $quote->setBaseGrandTotal((float) $quote->getBaseGrandTotal() + $address->getBaseGrandTotal());
                    $quote->save();

                    $quote->setGrandTotal($quote->getBaseSubtotal()-$discountAmount)
                            ->setBaseGrandTotal($quote->getBaseSubtotal()-$discountAmount)
                            ->setSubtotalWithDiscount($quote->getBaseSubtotal()-$discountAmount)
                            ->setBaseSubtotalWithDiscount($quote->getBaseSubtotal()-$discountAmount)
                            ->save();


                    if($address->getAddressType()==$canAddItems)
                    {
                        $address->setSubtotalWithDiscount((float) $address->getSubtotalWithDiscount()-$discountAmount);
                        $address->setGrandTotal((float) $address->getGrandTotal()-$discountAmount);
                        $address->setBaseSubtotalWithDiscount((float) $address->getBaseSubtotalWithDiscount()-$discountAmount);
                        $address->setBaseGrandTotal((float) $address->getBaseGrandTotal()-$discountAmount);

                        if($address->getDiscountDescription())
                        {
                            $address->setDiscountAmount(-($address->getDiscountAmount()-$discountAmount));
                            $address->setDiscountDescription($address->getDiscountDescription().', ' . $description);
                            $address->setBaseDiscountAmount(-($address->getBaseDiscountAmount()-$discountAmount));
                        }
                        else
                        {
                            $address->setDiscountAmount(-($discountAmount));
                            $address->setDiscountDescription($description);
                            $address->setBaseDiscountAmount(-($discountAmount));
                        }

                        $address->save();
                    }
                }

                foreach($quote->getAllItems() as $item)
                {
                    $rat     = $item->getPriceInclTax()/$total;
                    $ratdisc = $discountAmount*$rat;

                    $item->setDiscountAmount(($item->getDiscountAmount()+$ratdisc) * $item->getQty());
                    $item->setBaseDiscountAmount(($item->getBaseDiscountAmount()+$ratdisc) * $item->getQty())->save();
                }
            }
        }
    }
}

?>
