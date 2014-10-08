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
     * Element used to display text and html as preview in the create email campaign flow step 4.
     */
    class EmailCampaignHtmlAndTextPreviewElement extends ReadOnlyElement implements DerivedElementInterface
    {
        const HTML_CONTENT_INPUT_NAME = 'htmlContent';

        const TEXT_CONTENT_INPUT_NAME = 'textContent';

        public static function getModelAttributeNames()
        {
            return array(
                static::HTML_CONTENT_INPUT_NAME,
                static::TEXT_CONTENT_INPUT_NAME,
            );
        }

        public static function renderModelAttributeLabel($name)
        {
            $labels = static::renderLabels();
            return $labels[$name];
        }

        protected static function renderLabels()
        {
            $labels = array(Zurmo::t('EmailTemplatesModule', 'Html Content Preview'),
                            Zurmo::t('EmailTemplatesModule', 'Text Content Preview'));
            return array_combine(static::getModelAttributeNames(), $labels);
        }

        protected function renderHtmlContentAreaLabel()
        {
            return static::renderModelAttributeLabel(static::HTML_CONTENT_INPUT_NAME);
        }

        protected function renderTextContentAreaLabel()
        {
            return static::renderModelAttributeLabel(static::TEXT_CONTENT_INPUT_NAME);
        }

        protected function resolveTabbedContent($plainTextContent, $htmlContent)
        {
            $this->registerTabbedContentScripts();
            $htmlTabHyperLink   = ZurmoHtml::link($this->renderHtmlContentAreaLabel(), '#tab1', array('class' => 'active-tab'));
            $textTabHyperLink   = ZurmoHtml::link($this->renderTextContentAreaLabel(), '#tab2');
            if ($this->form)
            {
                $controllerId           = $this->getControllerId();
                $moduleId               = $this->getModuleId();
                $modelId                = $this->model->id;
            }
            $tabContent         = ZurmoHtml::tag('div', array('class' => 'tabs-nav'), $htmlTabHyperLink . $textTabHyperLink);

            $htmlContentDiv     = ZurmoHtml::tag('div',
            		array('id' => 'tab1',
            				'class' => 'active-tab tab email-template-' . static::HTML_CONTENT_INPUT_NAME),
            		$htmlContent);
            
            $plainTextDiv       = ZurmoHtml::tag('div',
                                                array('id' => 'tab2',
                                                      'class' => 'tab email-template-' . static::TEXT_CONTENT_INPUT_NAME),
                                                $plainTextContent);
            
            return ZurmoHtml::tag('div', array('class' => 'email-template-content'), $tabContent . $htmlContentDiv . $plainTextDiv );
        }

        protected function registerTabbedContentScripts()
        {
            $scriptName = 'email-templates-tab-switch-handler';
            if (Yii::app()->clientScript->isScriptRegistered($scriptName))
            {
                return;
            }
            else
            {
                Yii::app()->clientScript->registerScript($scriptName, "
                        $('.tabs-nav a:not(.simple-link)').click( function()
                        {
                            //the menu items
                            $('.active-tab', $(this).parent()).removeClass('active-tab');
                            $(this).addClass('active-tab');
                            //the sections
                            var _old = $('.tab.active-tab'); //maybe add context here for tab-container
                            _old.fadeToggle();
                            var _new = $( $(this).attr('href') );
                            _new.fadeToggle(150, 'linear', function()
                            {
                                    _old.removeClass('active-tab');
                                    _new.addClass('active-tab');
                            });
                            return false;
                        });
                    ");
            }
        }

        protected function renderControlNonEditable()
        {
            assert('$this->attribute == null');
            return $this->resolveTabbedContent($this->model->textContent, $this->model->htmlContent);
        }

        protected function renderControlEditable()
        {
            return $this->resolveTabbedContent($this->renderTextContentArea(), $this->renderHtmlContentArea());
        }

        protected function renderLabel()
        {
            return null;
        }

        protected function renderError()
        {
            return null;
        }

        protected function getControllerId()
        {
            return Yii::app()->getController()->getId();
        }

        protected function getModuleId()
        {
            return 'emailCampaigns';
        }
    }
?>