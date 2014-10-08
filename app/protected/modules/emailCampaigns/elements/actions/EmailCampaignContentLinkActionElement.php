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
     * Link element to take you to the setup content for email campaign step
     */
    class EmailCampaignContentLinkActionElement extends EditLinkActionElement
    {
        /**
         * @return string
         */
        protected function getDefaultLabel()
        {
            return Zurmo::t('EmailCampaignsModule', '3. Content');
        }

        /**
         * @return string
         */
        protected function getDefaultRoute()
        {
        	if (Yii::app()->request->getParam('id') != null) {
        		$params = array('id' => intval(Yii::app()->request->getParam('id')));
        		return Yii::app()->createUrl('emailCampaigns/default/content', $params);
        	} else return Yii::app()->createUrl('emailCampaigns/default/content');
        }
        
        protected function getHtmlOptions()
        {
        	if (!isset($this->params['htmlOptions']))
        	{
        		return array();
        	}
        	
        	if (Yii::app()->request->getParam('id') == null)
        		return array_merge	($this->params['htmlOptions'], 
        					array( 'onclick' => 'return false;', )
        				);
        	else return $this->params['htmlOptions'];
        }
    }
?>