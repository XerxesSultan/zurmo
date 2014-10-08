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

    class EmailCampaignContentView extends EditView
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
                        	array('type'    => 'SkipLink', 'redirectUrl' => 'preview'),
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
                            					array('attributeName' => 'supportsRichText',
                            						'type'          => 'SupportsRichTextCheckBox'),
                            				),
                            			),
                            		)
                            	),
                            	array('cells' =>
                            		array(
                            			array(
                            				'elements' => array(
                            					array('attributeName' => 'null',
                            						'type' => 'CampaignContactEmailTemplateNamesDropDown')
                            					),
                            			),
                            		)
                            	),
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'null', 'type' => 'Files'),
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
            $content = $this->renderHtmlAndTextContentElement($this->model, null, $form);
            return $content;
        }

        protected function renderHtmlAndTextContentElement($model, $attribute, $form)
        {
            $element = new EmailCampaignHtmlAndTextContentElement($model, $attribute , $form);
            if ($form !== null)
            {
                $this->resolveElementDuringFormLayoutRender($element);
            }
            $spinner = ZurmoHtml::tag('span', array('class' => 'big-spinner'), '');
            return ZurmoHtml::tag('div', array('class' => 'email-template-combined-content'), $element->render());
        }

        protected function resolveElementDuringFormLayoutRender(& $element)
        {
            if ($this->alwaysShowErrorSummary())
            {
                $element->editableTemplate = str_replace('{error}', '', $element->editableTemplate);
            }
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