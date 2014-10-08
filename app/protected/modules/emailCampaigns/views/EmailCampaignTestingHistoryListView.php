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

class EmailCampaignTestingHistoryListView extends EditView
{
	protected $campaignTestingHistoryBeanArr;
	
	/**
	 * Constructs an email campaign testing history view.
	 */
	public function __construct($controllerId, $moduleId, $model, $title = null, $campaignTestingHistoryBeanArr)
	{
		$this->campaignTestingHistoryBeanArr   = $campaignTestingHistoryBeanArr;
		parent::__construct($controllerId, $moduleId, $model, $title);
	}
	
	public static function getDefaultMetadata()
	{
		$metadata = array(
				'global' => array(
						'toolbar' => array('elements' => array(),),
						'panels' => array(),
				),
		);
		return $metadata;
	}
	
	protected function renderContent()
    {
    	$content = '';
 		if(isset($this->campaignTestingHistoryBeanArr) && (count($this->campaignTestingHistoryBeanArr) > 0) )
 		{
 			$content .= '
 					<div style="border: 1px solid #999999; padding: 3px;">
 						<div style="overflow-y: scroll; height: 110px; border-right: 1px solid #999999; padding: 3px;">
 							<table cellpadding="0" cellspacing="0" border="0" style="min-width: 715px; width: 100%;">
 								<tbody><tr bgcolor="#787878">
 									<td>
						 				<div style="padding-top:1px; padding-bottom:1px;padding-left:3px; font-size:11px; font-weight: bold; color:#ffffff;">
						 					NAME
						 				</div>
						 			</td>
						 			<td width="20%">
							 			<div style="padding-top:1px; padding-bottom:1px;padding-left:3px; font-size:11px; font-weight: bold;color:#ffffff;">
							 				DATE
							 			</div>
						 			</td>
						 			<td width="8%">
							 			<div style="padding-top:1px; padding-bottom:1px;padding-left:3px; font-size:11px; font-weight: bold;color:#ffffff;">
							 				TOTAL
							 			</div>
						 			</td>
						 			<td width="8%">
							 			<div style="padding-top:1px; padding-bottom:1px;padding-left:3px; font-size:11px; font-weight: bold;color:#ffffff;">
							 				TARGETED
							 			</div>
						 			</td>
						 			<td width="8%">
							 			<div style="padding-top:1px; padding-bottom:1px;padding-left:3px; font-size:11px; font-weight: bold;color:#ffffff;">
							 				OPENED
							 			</div>
						 			</td>
							 		<td width="8%">
							 			<div style="padding-top:1px; padding-bottom:1px;padding-left:3px; font-size:11px; font-weight: bold;color:#ffffff;">
							 				FAILED
							 			</div>
						 			</td>
						 			</tr>';
 			foreach($this->campaignTestingHistoryBeanArr as $eachCampaignTestingHistoryBean)
 			{
 				$content .= '
 									<tr bgolor="#eaeaea">
						 				<td>
						 					<div style="padding-top:1px; padding-bottom:1px;padding-left:3px; font-size:11px;">
						 						' . $eachCampaignTestingHistoryBean->name . '
						 					</div>
						 				</td>
							 			<td>
							 				<div style="padding-top:1px; padding-bottom:1px;padding-left:3px; font-size:11px;">
							 					' . $eachCampaignTestingHistoryBean->sendDateTime . '
							 				</div>
							 			</td>
							 			<td>
							 				<div style="padding-top:1px; padding-bottom:1px;padding-left:3px; font-size:11px;">
							 					' . $eachCampaignTestingHistoryBean->totalSent . '
							 				</div>
							 			</td>
							 			<td>
								 			<div style="padding-top:1px; padding-bottom:1px;padding-left:3px; font-size:11px;">
								 				' . $eachCampaignTestingHistoryBean->totalTargeted . '
								 			</div>
							 			</td>
							 			<td>
							 				<div style="padding-top:1px; padding-bottom:1px;padding-left:3px; font-size:11px;">
							 					' . $eachCampaignTestingHistoryBean->totalOpened . '
							 				</div>
							 			</td>
							 			<td>
							 				<div style="padding-top:1px; padding-bottom:1px;padding-left:3px; font-size:11px;">
							 					' . $eachCampaignTestingHistoryBean->totalFailed . '
							 				</div>
							 			</td>
						 			</tr>';
  			}
 			
 			$content .= '		</tbody></table>
 							</div>
 						</div>';
 			

 		}
 		else 
 		{
 			$content .= '
 					<div style="border: 1px solid #999999; padding: 3px;">
 						<div style="overflow-y: scroll; height: 110px; border-right: 1px solid #999999; padding: 3px; vertical-align:center">
		 					<table cellpadding="0" cellspacing="0" border="0" style="min-width: 705px; width:100%; border:1px solid #999999;background-color:#eaeaea;">
		 						<tbody><tr>
		 							<td align="center">
		 								<div style="padding-top:6px; padding-bottom:6px;font-weight:bold; text-align:center">
		 									No Record(s) Found
		 								</div>
		 							</td>
		 						</tr>
		 					</tbody></table>
 						</div>
 					</div>';
 			
 		}
		return $content;
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