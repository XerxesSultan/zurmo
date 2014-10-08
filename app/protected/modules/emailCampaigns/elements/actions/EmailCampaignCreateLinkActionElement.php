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
     * Link element to take you to the create email campaign step
     */
    class EmailCampaignCreateLinkActionElement extends EditLinkActionElement
    {
        /**
         * @return string
         */
        protected function getDefaultLabel()
        {
            return Zurmo::t('EmailCampaignsModule', '1. Describe');
        }

        /**
         * @return string
         */
        protected function getDefaultRoute()
        {
        	//Check if the page is new create campaign page or edit campaign page
        	if (Yii::app()->request->getParam('id') != null) {
        		$params = array('id' => intval(Yii::app()->request->getParam('id')));
            	return Yii::app()->createUrl('emailCampaigns/default/edit', $params);
        	}
        	else
        		return Yii::app()->createUrl('emailCampaigns/default/create');
        }
    }
?>