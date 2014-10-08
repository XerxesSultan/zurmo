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
 * Class for showing a message and create link when there are no email campaigns visible to the logged in user when
* going to the email campaign list view.
*/
class EmailCampaignsZeroModelsYetView extends ZeroModelsYetView
{
		/**
         * @return string
         */
        protected function getCreateLinkDisplayLabel()
        {
            return Zurmo::t('EmailCampaignsModule', 'Create Campaign');
        }

        /**
         * @return string
         */
        protected function getMessageContent()
        {
            return Zurmo::t('MarketingListsModule', '<h2>"The two offices of memory are collection and distribution"' .
                                                    '</h2><i>- Samuel Johnson</i>' .
                                                    '</i><div class="large-icon"></div><p>Stay fresh in your Contacts\' ' .
                                                    'memory by creating and distributing an email campaign.</p>');
        }
}
?>