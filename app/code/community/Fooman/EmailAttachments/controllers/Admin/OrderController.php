<?php

require_once BP.'/app/code/core/Mage/Adminhtml/controllers/Sales/OrderController.php';

class Fooman_EmailAttachments_Admin_OrderController extends Mage_Adminhtml_Sales_OrderController
{

    public function printAction()
    {
        if ($orderId = $this->getRequest()->getParam('order_id')) {
            if ($order = Mage::getModel('sales/order')->load($orderId)) {
                if ($order->getStoreId()) {
                    Mage::app()->setCurrentStore($order->getStoreId());
                }
                $pdf = Mage::getModel('emailattachments/order_pdf_order')->getPdf(array($order));
                $this->_prepareDownloadResponse('order'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf', $pdf->render(), 'application/pdf');
            }
        }
        else {
            $this->_getSession()->addError($this->__('There are no printable documents related to selected orders'));
        }
        $this->_redirect('*/*/');
    }

    public function pdfordersAction(){
        $orderIds = $this->getRequest()->getPost('order_ids');
        $flag = false;
        if (!empty($orderIds)) {
            foreach ($orderIds as $orderId) {
                $flag = true;
                if (!isset($pdf)){
                    $pdf = Mage::getModel('emailattachments/order_pdf_order')->getPdf(array($orderId));
                } else {
                    $pages = Mage::getModel('emailattachments/order_pdf_order')->getPdf(array($orderId));
                    $pdf->pages = array_merge ($pdf->pages, $pages->pages);
                }
            }
            if ($flag) {
                return $this->_prepareDownloadResponse('order'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf', $pdf->render(), 'application/pdf');
            } else {
                $this->_getSession()->addError($this->__('There are no printable documents related to selected orders'));
                $this->_redirect('*/*/');
            }

        }
        $this->_redirect('*/*/');

    }

    public function pdfpickingAction(){
        $orderIds = $this->getRequest()->getPost('order_ids');
        $flag = false;
        if (!empty($orderIds)) {
            foreach ($orderIds as $orderId) {
                $order = Mage::getModel('sales/order')->load($orderId);
                $flag = true;
                if (!isset($pdf)){
                    $pdf = Mage::getModel('emailattachments/order_pdf_order')->getPicking(array($order));
                } else {
                    $pages = Mage::getModel('emailattachments/order_pdf_order')->getPicking(array($order));
                    $pdf->pages = array_merge ($pdf->pages, $pages->pages);
                }

            }
            if ($flag) {
                return $this->_prepareDownloadResponse('order'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf', $pdf->render(), 'application/pdf');
            } else {
                $this->_getSession()->addError($this->__('There are no printable documents related to selected orders'));
                $this->_redirect('*/*/');
            }

        }
        $this->_redirect('*/*/');

    }

}