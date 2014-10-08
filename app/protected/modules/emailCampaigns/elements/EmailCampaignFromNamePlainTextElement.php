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

    /**
     * Read only element used to display the plain text value of email campaign from name
     */
    class EmailCampaignFromNamePlainTextElement extends ReadOnlyElement
    {
    	protected function renderLabel()
    	{
    		if ($this->form === null)
            {
                return $this->getFormattedAttributeLabel();
            }
            $title      = Zurmo::t('EmailCampaigns', 'Name displayed as "from" in your contact\'s in-box');
            $content    = Zurmo::t('EmailCampaigns', 'From Name');
            $content   .= ZurmoHtml::tag('span', array('id' => 'campaign-from-name-text-tooltip',
                                                        'class' => 'tooltip',
                                                        'title' => $title), '?');
            $content   .= ZurmoHtml::tag('span', array(), ':');
            $enableTrackingTip     = new ZurmoTip();
            $enableTrackingTip->addQTip("#campaign-from-name-text-tooltip");
            return $content;
    	}
    	
        /**
         * Renders a message.
         * @return The element's content.
         */
        protected function renderControlNonEditable()
        {
            $value = $this->model->{$this->attribute};
            if ($value != null)
            {
                return $value;
            }
            return '';
        }
    }
?>