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

    class EmailCampaignDetailsView extends View
    {
    	protected $controllerId;
    	
    	protected $moduleId;
    	
    	protected $model;
    	
    	protected $modelClassName;
    	
    	protected $modelId;
    	
    	public function __construct($controllerId, $moduleId, $model)
    	{
    		$this->controllerId   = $controllerId;
    		$this->moduleId       = $moduleId;
    		$this->model          = $model;
    		$this->modelClassName = get_class($model);
    		$this->modelId        = $model->id;
    	}
    	
    	protected function renderContent()
    	{
    		$headers = array(
    				Zurmo::t('EmailCampaignsModule', 'Status'),
    				Zurmo::t('EmailCampaignsModule', 'Module'),
    				Zurmo::t('EmailCampaignsModule', 'Description'),
    		);
    	
    		$modules = array(
    				'describe' => array(
    						'name' => Zurmo::t('EmailCampaignsModule', 'Describe'),
    						'desc' => Zurmo::t('EmailCampaignsModule', 'Provide the basic details about your email'),
    						'link' => ZurmoHtml::normalizeUrl(array('edit', 'id' => $this->model->id)),
    				),
    				'list' => array(
    						'name' => Zurmo::t('EmailCampaignsModule', 'List'),
    						'desc' => Zurmo::t('EmailCampaignsModule', 'Pick which list will be sent this email'),
    						'link' => ZurmoHtml::normalizeUrl(array('marketingList', 'id' => $this->model->id)),
    				),
    				'content' => array(
    						'name' => Zurmo::t('EmailCampaignsModule', 'Content'),
    						'desc' => Zurmo::t('EmailCampaignsModule', 'Build your Email content'),
    						'link' => ZurmoHtml::normalizeUrl(array('content', 'id' => $this->model->id)),
    				),
    				'preview' => array(
    						'name' => Zurmo::t('EmailCampaignsModule', 'Preview'),
    						'desc' => Zurmo::t('EmailCampaignsModule', 'Preveiw your Email content'),
    						'link' => ZurmoHtml::normalizeUrl(array('preview', 'id' => $this->model->id)),
    				),
    				'testing' => array(
    						'name' => Zurmo::t('EmailCampaignsModule', 'Testing'),
    						'desc' => Zurmo::t('EmailCampaignsModule', 'Testing your Email'),
    						'link' => ZurmoHtml::normalizeUrl(array('testing', 'id' => $this->model->id)),
    				),
    				'schedule' => array(
    						'name' => Zurmo::t('EmailCampaignsModule', 'Schedule'),
    						'desc' => Zurmo::t('EmailCampaignsModule', 'Decide when to send this email'),
    						'link' => ZurmoHtml::normalizeUrl(array('schedule', 'id' => $this->model->id)),
    				),
    		);
    	
    		$content = '<table class="items"><thead><tr>';
    	
    		foreach($headers as $header) {
    			$content .= '<th>' . $header . '</th>';
    		}
    	
    		$content .= '</tr></thead><tbody>';
    	
    		$complete = '<span class="green">' . Zurmo::t('EmailCampaignsModule', 'Complete') . '</span>';
    		$incomplete = '<span class="red">' . Zurmo::t('EmailCampaignsModule', 'Incomplete') . '</span>';
    	
    		foreach($modules as $key => $module) {
    			switch($key) {
    				case 'describe':
    					$content .= "<tr><td>{$complete}</td><td><a href='{$module['link']}'>{$module['name']}</a></td><td>{$module['desc']}</td></tr>";
    					break;
    				case 'list':
    					$status = ($this->model->marketingList->id > 0)? $complete : $incomplete;
    					$content .= "<tr><td>{$status}</td><td><a href='{$module['link']}'>{$module['name']}</a></td><td>{$module['desc']}</td></tr>";
    					break;
    				case 'content':
    					$status = (!empty($this->model->htmlContent) && $this->model->htmlContent != ' ')? $complete : $incomplete;
    					$content .= "<tr><td>{$status}</td><td><a href='{$module['link']}'>{$module['name']}</a></td><td>{$module['desc']}</td></tr>";
    					break;
    				case 'preview':
    					$status = (!empty($this->model->htmlContent) && $this->model->htmlContent != ' ')? $complete : $incomplete;
    					$content .= "<tr><td>{$status}</td><td><a href='{$module['link']}'>{$module['name']}</a></td><td>{$module['desc']}</td></tr>";
    					break;
    				case 'testing':
    					$status = (!empty($this->model->htmlContent) && $this->model->htmlContent != ' ')? $complete : $incomplete;
    					$content .= "<tr><td>{$status}</td><td><a href='{$module['link']}'>{$module['name']}</a></td><td>{$module['desc']}</td></tr>";
    					break;
    				case 'schedule':
    					$status = (!empty($this->model->sendOnDateTime))? $complete : $incomplete;
    					$content .= "<tr><td>{$status}</td><td><a href='{$module['link']}'>{$module['name']}</a></td><td>{$module['desc']}</td></tr>";
    					break;
    			}
    		}
    		$content .= '</tbody></table>';
    		return $content;
    	}    	
    	
    }
?>