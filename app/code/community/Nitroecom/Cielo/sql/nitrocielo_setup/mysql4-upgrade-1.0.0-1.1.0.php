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

$installer->addAttribute('order', 'base_juros', array
(
	'label' => 'Base Juros',
	'type'  => 'decimal',
));

$installer->addAttribute('quote', 'juros', array
(
	'label' => 'Juros',
	'type'  => 'decimal',
));

$installer->addAttribute('quote', 'base_juros', array
(
	'label' => 'Base Juros',
	'type'  => 'decimal',
));

$installer->addAttribute('order', 'juros', array
(
	'label' => 'Juros',
	'type'  => 'decimal',
));

$installer->addAttribute('invoice', 'base_juros', array
(
	'label' => 'Base Juros',
	'type'  => 'decimal',
));

$installer->addAttribute('invoice', 'juros', array
(
	'label' => 'Juros',
	'type'  => 'decimal',
));

$installer->addAttribute('creditmemo', 'base_juros', array
(
	'label' => 'Base Juros',
	'type'  => 'decimal',
));

$installer->addAttribute('creditmemo', 'juros', array
(
	'label' => 'Juros',
	'type'  => 'decimal',
));

$installer->endSetup();
