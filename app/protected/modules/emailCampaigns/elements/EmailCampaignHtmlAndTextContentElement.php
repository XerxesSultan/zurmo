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
     * Element used to edit text and html as email content in the create email campaign flow step 3.
     */
    class EmailCampaignHtmlAndTextContentElement extends Element implements DerivedElementInterface
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
            $labels = array(Zurmo::t('EmailCampaignsModule', 'Html Content'),
                            Zurmo::t('EmailCampaignsModule', 'Text Content'));
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
            //Register the dynamic image related sources
            $this->registerDynamicImgRes();
            //Create the Controller instance
            list($controller) = Yii::app()->createController('emailCampaigns/default/content');
            
 //           Yii::app()->clientScript->registerScriptFile ( Yii::app()->getBaseUrl(true) . '/protected/modules/emailCampaigns/assets/js/dynamicImg.js' );
            $dynamicImgLink 	= '<a class="active" herf="javascript:void(0);" onclick="setupDynamicImg(\'' . $this->getEditableInputId(static::HTML_CONTENT_INPUT_NAME) . '\', \'' . $this->model->id . '\', \'' . Yii::app()->getBaseUrl(true) . '/protected/modules/emailCampaigns/assets/ajax/handleImg.php\');"><h1>Setup Dynamic Image</h1></a>';           
            $dynamicImgContent  = ZurmoHtml::tag('div', array('id' => 'dynamic-img-setup', 'class' => 'dynamic-img-setup'), $dynamicImgLink);
            
            $previewImgLink 	= '<a class="active" herf="javascript:void(0);" onclick="previewDynamicContent(\'' . $this->getEditableInputId(static::HTML_CONTENT_INPUT_NAME) . '\', \'' . Yii::app()->getBaseUrl(true) . '/protected/modules/emailCampaigns/assets/images/' . Yii::app()->user->name . '/' . $this->model->id . '_outImg/\');"><h1>Preview Dynamic Image</h1></a>';
            $previewImgContent  = ZurmoHtml::tag('div', array('id' => 'dynamic-img-preview', 'class' => 'dynamic-img-setup'), $previewImgLink);
                        
            $htmlTabHyperLink   = ZurmoHtml::link($this->renderHtmlContentAreaLabel(), '#htmlTabLink', array('class' => 'active-tab'));
            $textTabHyperLink   = ZurmoHtml::link($this->renderTextContentAreaLabel(), '#textTabLink');
            if ($this->form)
            {
                $controllerId           = $this->getControllerId();
                $moduleId               = $this->getModuleId();
                $modelId                = $this->model->id;
            }
            $tabContent         = ZurmoHtml::tag('div', array('class' => 'tabs-nav'), $htmlTabHyperLink . $textTabHyperLink);
            
            $htmlContentDiv     = ZurmoHtml::tag('div',
            		array('id' => 'htmlTab', 'style' => 'display:block',
            				'class' => 'active-tab tab email-template-' . static::HTML_CONTENT_INPUT_NAME),
            		$htmlContent);

            $plainTextDiv       = ZurmoHtml::tag('div',
                                                array('id' => 'textTab', 'style' => 'display:none',
                                                      'class' => 'tab email-template-' . static::TEXT_CONTENT_INPUT_NAME),
                                                $plainTextContent);
            
            return ZurmoHtml::tag('div', array('class' => 'email-template-content'), $dynamicImgContent  .  $previewImgContent  .  $tabContent . $htmlContentDiv . $plainTextDiv);
        }

        protected function registerTabbedContentScripts()
        {
            $scriptName = 'email-campaign-contents-tab-switch-handler';
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
                		
                			//The div sections
                			if(document.getElementById('htmlTab').style.display == 'block') { 
                				document.getElementById('htmlTab').style.display = 'none';              				
                				$('#htmlTab').removeClass('active-tab');
                				document.getElementById('textTab').style.display = 'block';
                				$('#textTab').addClass('active-tab');
            				} else {
                				document.getElementById('textTab').style.display = 'none';
            					$('#textTab').removeClass('active-tab');
                				document.getElementById('htmlTab').style.display = 'block';
                				$('#htmlTab').addClass('active-tab');
            				}
                			
                            return false;
                        });
                    ");
            }
        }
        
        protected function registerDynamicImgRes()
        {
        	$resUrl = Yii::app()->baseUrl . '/protected/modules/emailCampaigns/assets';
        	$cs = Yii::app()->getClientScript();
        	$cs->registerCssFile($resUrl.'/bootstrap/css/bootstrap.xgate.css');
        	$cs->registerCssFile($resUrl.'/css/dynamicImg.css');
        	$cs->registerScriptFile($resUrl.'/bootstrap/js/bootstrap.js');
        	$cs->registerScriptFile($resUrl.'/js/dynamicImg.js');
        	$cs->registerScriptFile($resUrl.'/js/previewDynamicContent.js');
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

        // REVIEW : @Shoaibi Create a HTML element out of it.
        protected function renderHtmlContentArea()
        {
            $id                      = $this->getEditableInputId(static::HTML_CONTENT_INPUT_NAME);
//             $htmlOptions             = array();
//             $htmlOptions['id']       = $id;
//             $htmlOptions['name']     = $this->getEditableInputName(static::HTML_CONTENT_INPUT_NAME);
//             $cClipWidget             = new CClipWidget();
//             $cClipWidget->beginClip("Redactor");
//             $cClipWidget->widget('application.core.widgets.Redactor', array(
//                                         'htmlOptions' => $htmlOptions,
//                                         'content'     => $this->model->htmlContent,
//                                 ));
//             $cClipWidget->endClip();

            $cClipWidget = new CClipWidget();
            $cClipWidget->beginClip("Redactor");
            $cClipWidget->widget('application.extensions.tinymce.ETinyMce',
            		array(
            				'id'=>$id,
            				'name'=>$this->getEditableInputName(static::HTML_CONTENT_INPUT_NAME),
            				'value'=>$this->model->htmlContent,
            				'editorTemplate'=>'full',
            				'useSwitch' => false,
            				'width' => '100%',
            				'height' => '800px',
            		)
            );
            $cClipWidget->endClip();
            
            // Setup sessions on login page for example
            $_SESSION['XGZurmoIsLoggedInState'] = !Yii::app()->user->isGuest;
            if(!Yii::app()->user->isGuest) {       
            	// Check whether the folder been created or not
            	$imgDir = Yii::app()->basePath . "/modules/emailCampaigns/assets/images/" . Yii::app()->user->name;
            	
            	//Create the user image folder if not exist
            	if(!file_exists($imgDir . '/')) {
            		mkdir($imgDir . '/', 0755);
            	}
            		
            	//Create the campaign image folder for content setup with Tiny Editor if not exist
            	$folderPath = $imgDir . '/' . $this->model->id . '_innerImg/';
            	if (!file_exists($folderPath)) {
            		mkdir($folderPath, 0755);
            	}
            	
            	//Create the campaign image folder for email sending if not exist
            	$outFolderPath = $imgDir . '/' . $this->model->id . '_outImg/';
            	if (!file_exists($outFolderPath)) {
            		mkdir($outFolderPath, 0755);
            	}
            	            	
            	$imgSystemBaseUrl = Yii::app()->getBaseUrl(true);
            	
            	$_SESSION['XGZurmoUser'] = Yii::app()->user->name;
            	     	
            	$_SESSION['filemanager.filesystem.path'] = $folderPath;
            	$_SESSION['filemanager.filesystem.rootpath'] = $folderPath;
            }
            
            $content                 = ZurmoHtml::label($this->renderHtmlContentAreaLabel(), $id);
            $content                .= $cClipWidget->getController()->clips['Redactor'];
            $content                .= $this->renderHtmlContentAreaError();
            
            $dynamicImgJS  = '';
            $dynamicImgJS .= "
                        <script type=\"text/javascript\">
        					var globalDummyImgUrl = \"" . Yii::app()->getBaseUrl(true) . "/protected/extensions/dummyImg/code.php?x=\";
        					var globalImgRootPath = \"" . $folderPath . "\";
        					var globalOutImgPath = \"" . $outFolderPath . "\";
        					var globalBaseUrl = \"" . $imgSystemBaseUrl . "\";
        					var dynamicFields = new Object();
        					dynamicFields.gender=[\"F\",\"M\"];
        					dynamicFields.vipLevel=[\"Diamond\",\"Gold\",\"Silver\"];
        				</script>
                    ";
            $content 				.= $dynamicImgJS;
            return $content;
        }

         protected function renderTextContentArea()
         {
            $textContentElement                         = new TextAreaElement($this->model, static::TEXT_CONTENT_INPUT_NAME, $this->form);
            $textContentElement->editableTemplate       = $this->editableTemplate;
            return $textContentElement->render();
         }

        protected function renderHtmlContentAreaError()
        {
            if (strpos($this->editableTemplate, '{error}') !== false)
            {
                return $this->form->error($this->model, static::HTML_CONTENT_INPUT_NAME);
            }
            else
            {
                return null;
            }
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