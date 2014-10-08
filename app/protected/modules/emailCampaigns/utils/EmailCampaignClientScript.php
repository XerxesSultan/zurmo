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

class EmailCampaignClientScript
{
	private static $_assetsUrl;
	
	public static function getAssetsUrl()
	{
// 		$assetsPath = Yii::getPathOfAlias('application.modules.emailCampaigns.assets');
		
// 		if(YII_DEBUG === true)
// 			self::$_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath, false, -1, true);
// 		else
// 			self::$_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath);
		
		self::$_assetsUrl = Yii::app()->getBaseUrl(true) . '/protected/modules/emailCampaigns/assets';
		
		return self::$_assetsUrl;
	}
	
	public static function registerModuleScripts()
	{
		$assetsUrl = self::getAssetsUrl();
		
		$cs = Yii::app()->clientScript;
		
		$cs->registerCssFile( $assetsUrl . '/css/style.css' );
		$cs->registerScriptFile ( $assetsUrl . '/js/common.js' );
	}
}

?>