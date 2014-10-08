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

    class EmailCampaignMarketingListView extends EditView
    {
        public static function getDefaultMetadata()
        {
            $metadata = array(
                'global' => array(
                    'toolbar' => array(
                        'elements' => array(
                            array('type'    => 'CancelLink'),
                            array('type'    => 'SaveButton', 'label' => 'eval:Zurmo::t("EmailCampaignsModule", "Save")'),
                            array('type'	=> 'SaveAndNext'),
                        	array('type'    => 'SkipLink', 'redirectUrl' => 'content'),
                        	array('type'    => 'CampaignDeleteLink'),
                        ),
                    ),
                    'panels' => array(
                        array(
                            'rows' => array(
                 				array('cells' =>
                            		array(
                            			array(
                            				'elements' => array(
                            					array('attributeName' => 'marketingList', 'type' => 'MarketingList'),
                            				),
                            			),
                            		)
                            	),
                            ),
                        ),
                    ),
                ),
            );
            return $metadata;
        }
        
        protected function renderContent()
        {
        	$this->registerCopyInfoFromMarketingListScript();
        	return parent::renderContent();
        }
        
        protected function registerCopyInfoFromMarketingListScript()
        {
        	$url           = Yii::app()->createUrl('marketingLists/default/getInfoToCopyToCampaign');
        	// Begin Not Coding Standard
        	Yii::app()->clientScript->registerScript('copyInfoFromMarketingListScript', "
                $('#Campaign_marketingList_id').live('change', function()
                    {
                       if ($('#Campaign_marketingList_id').val())
                          {
                            $.ajax(
                            {
                                url : '" . $url . "?id=' + $('#Campaign_marketingList_id').val(),
                                type : 'GET',
                                dataType: 'json',
                                success : function(data)
                                {
                                    $('#Campaign_fromName').val(data.fromName);
                                    $('#Campaign_fromAddress').val(data.fromAddress)
                                },
                                error : function()
                                {
                                    //todo: error call
                                }
                            }
                            );
                          }
                    }
                );
            ");
        	// End Not Coding Standard
        }

        protected function alwaysShowErrorSummary()
        {
            return true;
        }

        protected function getNewModelTitleLabel()
        {
            return Zurmo::t('Default', 'Create AutorespondersModuleSingularLabel',
                                                                        LabelUtil::getTranslationParamsForAllModules());
        }
    }
?>