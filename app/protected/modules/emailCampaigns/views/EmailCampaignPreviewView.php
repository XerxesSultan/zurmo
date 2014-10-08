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

    class EmailCampaignPreviewView extends EditView
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
                        	array('type'    => 'SkipLink', 'redirectUrl' => 'testing'),
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
                            					array(	'attributeName' => 'name',
                            							'type'          => 'EmailCampaignNamePlainText',
                            					),
                            				),
                            			),
                            		)
                            	),
                            	array('cells' =>
                            		array(
                            			array(
                            				'elements' => array(
                            					array(	'attributeName' => 'fromName',
                            							'type'          => 'EmailCampaignFromNamePlainText',
                            					),
                            				),
                            			),
                            		)
                            	), 
                            	array('cells' =>
                            		array(
                            			array(
                            				'elements' => array(
                            					array(	'attributeName' => 'fromAddress',
                            							'type'          => 'EmailCampaignFromAddressPlainText',
                            					),
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
        
    	protected function renderAfterFormLayout($form)
        {
            $content = $this->renderHtmlAndTextPreviewElement($this->model, null, $form);
            return $content;
        }

        protected function renderHtmlAndTextPreviewElement($model, $attribute, $form)
        {
            $element = new EmailCampaignHtmlAndTextPreviewElement($model, $attribute , $form);
            $spinner = ZurmoHtml::tag('span', array('class' => 'big-spinner'), '');
            return ZurmoHtml::tag('div', array('class' => 'email-template-combined-content'), $element->render());
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