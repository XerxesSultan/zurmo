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
	class SaveAndNextActionElement extends ActionElement
	{
	    
	    public function __construct($controllerId, $moduleId, $modelId, $params = array())
	    {
	        if (!isset($params['htmlOptions']))
	        {
	            $params['htmlOptions'] = array();
	        }
	        $params['htmlOptions'] = array_merge(array('id'     => 'save_and_next' . ZurmoHtml::ID_PREFIX . ZurmoHtml::$count++,
	                                                   'class'  => 'attachLoading',
	                                                   'params' => array('save_and_next' => 1)), $params['htmlOptions']);
	        parent::__construct($controllerId, $moduleId, $modelId, $params);
	    }
	    
	    protected function getDefaultLabel()
	    {
	        return Zurmo::t('EmailCampaignsModule', 'Save & Next');
	    }
	    
	    protected function getDefaultRoute()
	    {
	    }
	    
	    public function getActionType()
	    {
	        return 'Edit';
	    }
	    
	    public function render()
	    {
	        $htmlOptions = $this->getHtmlOptions();
	        $request     = Yii::app()->getRequest();
	        if ($request->enableCsrfValidation && isset($htmlOptions['csrf']) && $htmlOptions['csrf'])
	        {
	            $htmlOptions['params'][$request->csrfTokenName] = $request->getCsrfToken();
	        }
	        if (isset($htmlOptions['params']))
	        {
	            $params = CJavaScript::encode($htmlOptions['params']);
	            unset($htmlOptions['params']);
	        }
	        else
	        {
	            $params = '{}';
	        }
	        if (isset($htmlOptions['class']))
	        {
	            $htmlOptions['class']  .= ' z-button';
	        }
	        else
	        {
	            $htmlOptions['class']   = 'z-button';
	        }
	        $cs = Yii::app()->getClientScript();
	        $cs->registerCoreScript('jquery');
	        $cs->registerCoreScript('yii');
	        if (Yii::app()->getClientScript()->isIsolationMode())
	        {
	            $handler = "jQQ.isolate (function(jQuery, $)
	                        {
	                            jQuery.yii.submitForm(document.getElementById('save_and_nextyt2'), '', $params);
	                        }); return false;";
	        }
	        else
	        {
	            $handler = "jQuery.yii.submitForm(this, '', $params); return false;";
	        }
	        if (isset($htmlOptions['onclick']))
	        {
	            $htmlOptions['onclick']  = $htmlOptions['onclick'] . $handler;
	        }
	        else
	        {
	            $htmlOptions['onclick']  = $handler;
	        }
	        $aContent                = ZurmoHtml::wrapLink($this->getLabel());
	        return ZurmoHtml::link($aContent, '#', $htmlOptions);
	    }
	}