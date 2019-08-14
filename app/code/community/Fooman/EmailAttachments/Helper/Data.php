<?php
class Fooman_EmailAttachments_Helper_Data extends Mage_Core_Helper_Abstract {
    
    public function addAttachment($pdf, $mailObj, $name = "order.pdf") {
        try{
            $file = $pdf->render();
            $mailObj->getMail()->createAttachment($file,'application/pdf',Zend_Mime::DISPOSITION_ATTACHMENT,Zend_Mime::ENCODING_BASE64,$name.'.pdf');
        } catch (Exception $e){
            Mage::log(Mage::helper('emailattachments')->__('Caught error while attaching pdf: %s'),$e->getMessage());
        }
        return $mailObj;
    }

    public function addAgreements($storeId,$mailObj) {
        $agreements = Mage::getModel('checkout/agreement')->getCollection()
            ->addStoreFilter($storeId)
            ->addFieldToFilter('is_active', 1);
        if ($agreements) {
            foreach ($agreements as $agreement) {
                $agreement->load($agreement->getId());
                if($agreement->getIsHtml()) {
                    $html='<html><head><title>'.$agreement->getName().'</title></head><body>'.$agreement->getContent().'</body></html>';
                    $mailObj->getMail()->createAttachment($html,'text/html',Zend_Mime::DISPOSITION_ATTACHMENT,Zend_Mime::ENCODING_BASE64,urlencode($agreement->getName()).'.html');
                } else {
                    $mailObj->getMail()->createAttachment(Mage::helper('core')->htmlEscape($agreement->getContent()),'text/plain',Zend_Mime::DISPOSITION_ATTACHMENT,Zend_Mime::ENCODING_BASE64,urlencode($agreement->getName()).'.txt');
                }
            }
        }
        return $mailObj;
    }
}