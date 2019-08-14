<?php

class Fooman_EmailAttachments_Model_Observer {

    public function addbutton($observer) {

        if($observer->getEvent()->getBlock() instanceof Mage_Adminhtml_Block_Widget_Grid_Massaction) {
            $secure = Mage::app()->getStore()->isCurrentlySecure() ? 'true' : 'false';
            if($observer->getEvent()->getBlock()->getRequest()->getControllerName() =='sales_order') {
                $observer->getEvent()->getBlock()->addItem('pdforders_order', array(
                    'label'=> Mage::helper('sales')->__('Print Orders'),
                    'url'  => Mage::helper('adminhtml')->getUrl('emailattachments/admin_order/pdforders',Mage::app()->getStore()->isCurrentlySecure() ? array('_secure'=>1) : array()),
                ));
            }
        }
    }

}