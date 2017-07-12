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

$installer = Mage::getResourceModel('sales/setup', 'default_setup');

$installer->startSetup();

//adiciona atributo juros ao endereço da cotação
$installer->addAttribute('quote_address', 'juros', array
(
	'label' => 'Juros',
	'type'  => 'decimal',
));

$installer->addAttribute('quote_address', 'base_juros', array
(
	'label' => 'Base Juros',
	'type'  => 'decimal',
));

//adiciona atributo desconto ao endereço da cotação
$installer->addAttribute('quote_address', 'desconto', array
(
	'label' => 'Desconto',
	'type'  => 'decimal',
));

$installer->addAttribute('quote_address', 'base_desconto', array
(
	'label' => 'Base Desconto',
	'type'  => 'decimal',
));

//adiciona atributo desconto a cotação
$installer->addAttribute('quote', 'desconto', array
(
	'label' => 'Desconto',
	'type'  => 'decimal',
));

$installer->addAttribute('quote', 'base_desconto', array
(
	'label' => 'Base Desconto',
	'type'  => 'decimal',
));


//adiciona atributo desconto ao pedido
$installer->addAttribute('order', 'desconto', array
(
	'label' => 'Desconto',
	'type'  => 'decimal',
));

$installer->addAttribute('order', 'base_desconto', array
(
	'label' => 'Base Desconto',
	'type'  => 'decimal',
));


//adiciona atributo desconto a tabela de fatura
$installer->addAttribute('invoice', 'base_desconto', array
(
	'label' => 'Base Juros',
	'type'  => 'decimal',
));

$installer->addAttribute('invoice', 'desconto', array
(
	'label' => 'Juros',
	'type'  => 'decimal',
));


//adiciona o atributo desconto a tabela de estorno
$installer->addAttribute('creditmemo', 'base_desconto', array
(
	'label' => 'Base Juros',
	'type'  => 'decimal',
));

$installer->addAttribute('creditmemo', 'desconto', array
(
	'label' => 'Juros',
	'type'  => 'decimal',
));

$installer->endSetup();
