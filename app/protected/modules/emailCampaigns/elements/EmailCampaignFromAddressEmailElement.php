<?php
   /*********************************************************************************
	* Zurmo EmailCampaigns is a custom module developed by Fireals Ltd.,
	* and RIGHTS received by XGATE Corp. Ltd. Copyright (C) 2013 XGATE Corp. Ltd.
	*
	* Zurmo EmailCampaigns module is an enterprise plugin;
	* you can NOT redistribute it and/or modify it without rights given by XGATE Corp. Ltd.
	*
	* Zurmo is distributed in the hope that it will be useful for XGATE services.
	*
	* You can contact XGATE Corp. Ltd. with a mailing address at Unit 107, 1/F.,
	* Building 6, Bio-Informatics Centre No.2 Science Park West Avenue
	* Hong Kong Science Park, Shatin, N.T., HK or at email address info@xgate.com.hk.
	********************************************************************************/

    class EmailCampaignFromAddressEmailElement extends EmailElement
    {
        protected function renderLabel()
        {
            if ($this->form === null)
            {
                return $this->getFormattedAttributeLabel();
            }
            $title      = Zurmo::t('EmailCampaigns', 'Improve your deliverability rate by using your own domain name rather than a major ISP email accounts.');
            $content    = Zurmo::t('EmailCampaigns', 'From Address');
            $content   .= ZurmoHtml::tag('span', array('id' => 'campaign-from-addr-text-tooltip',
                                                        'class' => 'tooltip',
                                                        'title' => $title), '?');
            $content   .= ZurmoHtml::tag('span', array('id' => '',
            											'class' => 'required',
            											'title' => ''), '*');
            $enableTrackingTip     = new ZurmoTip();
            $enableTrackingTip->addQTip("#campaign-from-addr-text-tooltip");
            return $content;
        }
    }
?>