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

class Nitroecom_Cielo_Block_Parcelas extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('parcela_de', array('label' => Mage::helper('adminhtml')->__('Valor mínimo'),
                                             'style' => 'width:120px' ));

        $this->addColumn('parcela_ate', array('label' => Mage::helper('adminhtml')->__('Valor máximo'),
                                              'style' => 'width:120px' ));

        $this->addColumn('value', array('label' => Mage::helper('adminhtml')->__('Max. Parcelas'),
                                        'style' => 'width:120px' ));

        $this->_addAfter       = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add regra');

        parent::__construct();
    }
}

?>
