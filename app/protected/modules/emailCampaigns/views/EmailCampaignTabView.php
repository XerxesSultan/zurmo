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

class EmailCampaignTabView extends SecuredActionBarForSearchAndListView
{

	/**
	 * @return array
	 */
	public static function getDefaultMetadata()
	{
		$metadata = array(
				'global' => array(
						'toolbar' => array(
								'elements' => array(
										array(
												'type'  		=> 'EmailCampaignCreateLink',
												'htmlOptions' 	=> array('class' => 'icon-marketing-campaigns'),
										),
										array(
												'type'        	=> 'EmailCampaignMarketingListLink',
												'htmlOptions' 	=> array( 'class' => 'icon-marketing-lists', )
										),
										array(
												'type'        	=> 'EmailCampaignContentLink',
												'htmlOptions' 	=> array( 'class' => 'icon-email-templates', )
										),
										array(
												'type'        	=> 'EmailCampaignPreviewLink',
												'htmlOptions' 	=> array( 'class' => 'icon-details', )
										),
										array(
												'type'        	=> 'EmailCampaignTestingLink',
												'htmlOptions' 	=> array( 'class' => 'icon-mission', )
										),
										array(
												'type'        	=> 'EmailCampaignScheduleLink',
												'htmlOptions' 	=> array( 'class' => 'icon-by-time-workflow-in-queues', )
										),
								),
						),
				),
		);
		return $metadata;
	}

}

?>