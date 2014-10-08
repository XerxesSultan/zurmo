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

    class EmailCampaignsModule extends SecurableModule
    {
        const RIGHT_CREATE_EMAILCAMPAIGNS = 'Create EmailCampaigns';
        const RIGHT_DELETE_EMAILCAMPAIGNS = 'Delete EmailCampaigns';
        const RIGHT_ACCESS_EMAILCAMPAIGNS = 'Access EmailCampaigns Tab';

        public function getDependencies()
        {
            return array(
                'zurmo',
            );
        }

        public function getRootModelNames()
        {
            return array();	
        }

        public static function getDefaultMetadata()
        {
            $metadata = array();
            $metadata['global'] = array(
                'tabMenuItems' => array(
                    array(
                    	'label' => Zurmo::t('EmailCampaignsModule', 'Email Campaigns'),
                        'url'   => array('/emailCampaigns/default'),
                        'right' => self::RIGHT_ACCESS_EMAILCAMPAIGNS,
                        'items' => array(
                            array(
                                'label' => Zurmo::t('EmailCampaignsModule', 'Create Email Campaign'),
                                'url'   => array('/emailCampaigns/default/create'),
                                'right' => self::RIGHT_CREATE_EMAILCAMPAIGNS
                            ),
                            array(
                                'label' => Zurmo::t('EmailCampaignsModule', 'Email Campaigns'),
                                'url'   => array('/emailCampaigns/default'),
                                'right' => self::RIGHT_ACCESS_EMAILCAMPAIGNS
                            ),
                        ),
                    ),
                ),
                'designerMenuItems' => array(
                    'showFieldsLink' => true,
                    'showGeneralLink' => true,
                    'showLayoutsLink' => true,
                    'showMenusLink' => true,
                ),
                'globalSearchAttributeNames' => array(
                    'name'
                )
            );
            return $metadata;
        }

        public static function getPrimaryModelName()
        {
            return 'Campaign';// Use the model defined in the campaigns module
        }

        public static function getSingularCamelCasedName()
        {
            return 'EmailCampaigns';
        }

        protected static function getSingularModuleLabel()
        {
            return 'EmailCampaign';
        }

        public static function getAccessRight()
        {
            return self::RIGHT_ACCESS_EMAILCAMPAIGNS;
        }

        public static function getCreateRight()
        {
            return self::RIGHT_CREATE_EMAILCAMPAIGNS;
        }

        public static function getDeleteRight()
        {
            return self::RIGHT_DELETE_EMAILCAMPAIGNS;
        }

        public static function getGlobalSearchFormClassName()
        {
            return 'EmailCampaignsSearchForm';
        }
    }
?>