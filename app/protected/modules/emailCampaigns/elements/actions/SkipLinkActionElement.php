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
	class SkipLinkActionElement extends LinkActionElement
    {
        public function getActionType()
        {
            if (!empty($this->modelId) && $this->modelId > 0)
            {
                return 'Details';
            }
            else
            {
                return null;
            }
        }

        protected function getDefaultLabel()
        {
            return Zurmo::t('EmailCampaigns', 'Skip');
        }

        protected function getDefaultRoute()
        {
            if (Yii::app()->request->getParam('redirectUrl') != null)
            {
                return Yii::app()->request->getParam('redirectUrl');
            }
            elseif (!empty($this->modelId) && $this->modelId > 0)
            {
            	return Yii::app()->createUrl($this->moduleId . '/' . $this->controllerId . '/' . $this->getRedirectUrl() . '/', array('id' => $this->modelId));
            }
            else
            {
                return Yii::app()->createUrl($this->moduleId . '/' . $this->controllerId);
            }
        }
    }