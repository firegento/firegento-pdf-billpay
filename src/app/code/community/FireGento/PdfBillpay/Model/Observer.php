<?php
/**
 * This file is part of a FireGento e.V. module.
 *
 * This FireGento e.V. module is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * PHP version 5
 *
 * @category  FireGento
 * @package   FireGento_PdfBillpay
 * @author    FireGento Team <team@firegento.com>
 * @copyright 2016 FireGento Team (http://www.firegento.com)
 * @license   http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
 */

/**
 * Adds the detailed Billpay payment information in the comments section of the FireGento PDF invoices.
 *
 * @category  FireGento
 * @package   FireGento_PdfBillpay
 * @author    FireGento Team <team@firegento.com>
 */
class FireGento_PdfBillpay_Model_Observer
{

    /**
     * Adds the detailed Billpay payment information in the comments section of the FireGento PDF invoices.
     *
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function firegentoPdfInvoiceInsertNote(Varien_Event_Observer $observer)
    {
        /** @var Mage_Sales_Model_Order $order */
        $order = $observer->getOrder();

        $paymentMethod = $order->getPayment()->getMethod();
        if ( ! Mage::helper('billpay/api')->isBillpayPayment($paymentMethod)) {
            return $this;
        }

        $result = $observer->getResult();
        $notes  = $result->getNotes();

        /** @var Billpay_Block_AbstractInfo $paymentBlock */
        $paymentBlock = Mage::helper('payment')->getInfoBlock($order->getPayment());
        $paymentText  = $paymentBlock->toPdf();
        $paymentText  = str_replace('{{pdf_row_separator}}', '', $paymentText);
        $notes[]      = $paymentText;

        $result->setNotes($notes);

        return $this;
    }

}
