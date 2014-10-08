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

class EmailCampaignsDefaultController extends ZurmoModuleController
{
	public function run($actionID)
	{
		EmailCampaignClientScript::registerModuleScripts();
		
		parent::run($actionID);
	}
	
	public static function getListBreadcrumbLinks()
	{
		$title = Zurmo::t('EmailCampaignsModule', 'Campaigns');
		return array($title);
	}
	
	public static function getDetailsAndEditBreadcrumbLinks()
	{
		return array(Zurmo::t('EmailCampaignsModule', 'Campaigns') => array('default/list'));
	}
	
	public function filters()
	{
		$modelClassName   = $this->getModule()->getPrimaryModelName();
		$viewClassName    = $modelClassName . 'EditAndDetailsView';
		return array_merge(parent::filters(),
				array(
						array(
								ZurmoBaseController::REQUIRED_ATTRIBUTES_FILTER_PATH . ' + create, createFromRelation, edit',
								'moduleClassName' => get_class($this->getModule()),
								'viewClassName'   => 'EmailCampaignEditAndDetailsView',
						),
						array(
								ZurmoModuleController::ZERO_MODELS_CHECK_FILTER_PATH . ' + list, index',
								'controller' => $this,
						),
				)
		);
	}

	public function actionIndex()
	{
		$this->actionList();
	}
	
	public function actionList()
	{
		$pageSize                       = Yii::app()->pagination->resolveActiveForCurrentUserByType(
				'listPageSize', get_class($this->getModule()));
		$campaign                       = new Campaign(false);
		$searchForm                     = new CampaignsSearchForm($campaign);
		$listAttributesSelector         = new ListAttributesSelector('CampaignsListView',
				get_class($this->getModule()));
		$searchForm->setListAttributesSelector($listAttributesSelector);
		$dataProvider = $this->resolveSearchDataProvider(
				$searchForm,
				$pageSize,
				'EmailTypeForEmailCampaignStateMetadataAdapter',
				'CampaignsSearchView'
		);
		if (isset($_GET['ajax']) && $_GET['ajax'] == 'list-view')
		{
			$mixedView = $this->makeListView(
					$searchForm,
					$dataProvider
			);
			$view = new CampaignsPageView($mixedView);
		}
		else
		{
			$mixedView = $this->makeActionBarSearchAndListView($searchForm, $dataProvider,
					'SecuredActionBarForMarketingSearchAndListView', null, 'CampaignsLink');
			$breadcrumbLinks = static::getListBreadcrumbLinks();
			$view      = new CampaignsPageView(ZurmoDefaultViewUtil::
					makeViewWithBreadcrumbsForCurrentUser($this, $mixedView, $breadcrumbLinks,
							'MarketingBreadCrumbView'));
		}
		echo $view->render();
	}

	public function actionReport($id)
	{
		$campaign = static::getModelAndCatchNotFoundAndDisplayError('Campaign', intval($id));
		ControllerSecurityUtil::resolveAccessCanCurrentUserReadModel($campaign);
		AuditEvent::logAuditEvent(	'ZurmoModule', 
									ZurmoModule::AUDIT_EVENT_ITEM_VIEWED,
									array(strval($campaign), 'EmailCampaignsModule'), 
									$campaign);
		$breadCrumbView             = CampaignsStickySearchUtil::
		resolveBreadCrumbViewForDetailsControllerAction($this,
				'EmailCampaignsSearchView', $campaign);
		$detailsAndRelationsView    = $this->makeDetailsAndRelationsView($campaign, 'CampaignsModule',
				'CampaignDetailsAndRelationsView',
				Yii::app()->request->getRequestUri(),
				$breadCrumbView);
		$view                       = new EmailCampaignsPageView(ZurmoDefaultViewUtil::
				makeStandardViewForCurrentUser($this, $detailsAndRelationsView));
		echo $view->render();
	}
	
	public function actionDetails($id)
	{
		$title = Zurmo::t('EmailCampaignsModule', 'Email Campaign Details');
		$breadcrumbLinks = array($title);
	
		$emailCampaign = static::getModelAndCatchNotFoundAndDisplayError('Campaign', intval($id));
	
		$detailsView = new EmailCampaignDetailsView($this->getId(),
				$this->getModule()->getId(),
				$emailCampaign);
	
		$gridView = new GridView(1, 1);
		$gridView->setView($detailsView, 0, 0);
	
		$view = new EmailCampaignsPageView(ZurmoDefaultViewUtil::
				makeViewWithBreadcrumbsForCurrentUser(
						$this, $gridView, $breadcrumbLinks, 'MarketingBreadCrumbView'));
		echo $view->render();
	}
	
	public function actionCreate()
	{
		$emailCampaign 					 	= new Campaign();
		$emailCampaign->status           	= Campaign::STATUS_ACTIVE;
		$emailCampaign->supportsRichText 	= true;
		$emailCampaign->enableTracking   	= true;
		$emailCampaign->htmlContent			= ' ';
		$emailCampaign->textContent			= ' ';
		
		// check if press button "save and next"
		$request = Yii::app()->request;				
		$isNext = $request->getPost('save_and_next', null);
		if(!empty($isNext)) {
			//Redirect to the next step: fill in content if Save and Next clicked.
			$emailCampaign = $this->attemptToSaveModelFromPost($emailCampaign, 'marketingList');
		}
	
		$gridViewId              = 'notUsed';
		$pageVar                 = 'notUsed';
		$tabView = new EmailCampaignTabView(
				'default',
				'emailCampaigns',
				new Campaign(), //Just to fill in a marketing model
				$gridViewId,
				$pageVar,
				false,
				'EmailCampaignCreateLink');
	
		$breadcrumbLinks            = static::getDetailsAndEditBreadcrumbLinks();
		$breadcrumbLinks[]          = Zurmo::t('EmailCampaignsModule', 'Create');
		
		$describeView                   = new EmailCampaignDescribeView($this->getId(), $this->getModule()->getId(),
				$this->attemptToSaveModelFromPost($emailCampaign),
				Zurmo::t('Default', 'Describe Email'));
		
		$gridView = new GridView(2, 1);
		$gridView->setView($tabView, 0, 0);
		$gridView->setView($describeView, 1, 0);
		
		$view               = new EmailCampaignsPageView(ZurmoDefaultViewUtil::
				makeViewWithBreadcrumbsForCurrentUser($this, $gridView,
						$breadcrumbLinks, 'MarketingBreadCrumbView'));
		echo $view->render();
	
	}
	
	public function actionEdit($id)
	{
		$emailCampaign           = Campaign::getById(intval($id));
		ControllerSecurityUtil::resolveAccessCanCurrentUserWriteModel($emailCampaign);
	
		// check if press button "save and next"
		$request = Yii::app()->request;
		$isNext = $request->getPost('save_and_next', null);
		if(!empty($isNext)) {
			//Redirect to the next step: Setup Content if Save and Next clicked.
			$emailCampaign = $this->attemptToSaveModelFromPost($emailCampaign, 'marketingList');
		}
	
		$breadcrumbLinks    = static::getDetailsAndEditBreadcrumbLinks();
		$breadcrumbLinks[]  = StringUtil::getChoppedStringContent(strval($emailCampaign), 25);
		//todo: wizard
		$gridViewId              = 'notUsed';
		$pageVar                 = 'notUsed';
		$tabView = new EmailCampaignTabView(
				'default',
				'emailCampaigns',
				$emailCampaign, //Just to fill in a marketing model
				$gridViewId,
				$pageVar,
				false,
				'EmailCampaignCreateLink');
	
		$describeView                   = new EmailCampaignDescribeView($this->getId(), $this->getModule()->getId(),
				$this->attemptToSaveModelFromPost($emailCampaign),
				Zurmo::t('Default', 'Describe Email'));
	
		$gridView = new GridView(2, 1);
		$gridView->setView($tabView, 0, 0);
		$gridView->setView($describeView, 1, 0);
	
		$view               = new EmailCampaignsPageView(ZurmoDefaultViewUtil::
				makeViewWithBreadcrumbsForCurrentUser($this, $gridView,
						$breadcrumbLinks, 'MarketingBreadCrumbView'));
		echo $view->render();
	}
	
	public function actionMarketingList($id)
	{
		$emailCampaign           = Campaign::getById(intval($id));
		ControllerSecurityUtil::resolveAccessCanCurrentUserWriteModel($emailCampaign);
	
		// check if press button "save and next"
		$request = Yii::app()->request;
		$isNext = $request->getPost('save_and_next', null);
		if(!empty($isNext)) {
			//Redirect to the next step: Setup Content if Save and Next clicked.
			$emailCampaign = $this->attemptToSaveModelFromPost($emailCampaign, 'content');
		}
	
		$breadcrumbLinks    = static::getDetailsAndEditBreadcrumbLinks();
		$breadcrumbLinks[]  = StringUtil::getChoppedStringContent(strval($emailCampaign), 25);
		//todo: wizard
		$gridViewId              = 'notUsed';
		$pageVar                 = 'notUsed';
		$tabView = new EmailCampaignTabView(
				'default',
				'emailCampaigns',
				$emailCampaign, //Just to fill in a marketing model
				$gridViewId,
				$pageVar,
				false,
				'EmailCampaignMarketingListLink');
	
		$describeView                   = new EmailCampaignMarketingListView($this->getId(), $this->getModule()->getId(),
				$this->attemptToSaveModelFromPost($emailCampaign),
				Zurmo::t('Default', 'Select Marketing List'));
	
		$gridView = new GridView(2, 1);
		$gridView->setView($tabView, 0, 0);
		$gridView->setView($describeView, 1, 0);
	
		$view               = new EmailCampaignsPageView(ZurmoDefaultViewUtil::
				makeViewWithBreadcrumbsForCurrentUser($this, $gridView,
						$breadcrumbLinks, 'MarketingBreadCrumbView'));
		echo $view->render();
	}
	
	public function actionContent($id)
	{
		$emailCampaign           = Campaign::getById(intval($id));
		ControllerSecurityUtil::resolveAccessCanCurrentUserWriteModel($emailCampaign);
		
		// check if press button "save and next"
		$request = Yii::app()->request;
		$isNext = $request->getPost('save_and_next', null);
		if(!empty($isNext)) {
			//Redirect to the next step: Preview Campaign if Save and Next clicked.
			$emailCampaign = $this->attemptToSaveModelFromPost($emailCampaign, 'preview');
		}
		
		$breadcrumbLinks    = static::getDetailsAndEditBreadcrumbLinks();
		$breadcrumbLinks[]  = StringUtil::getChoppedStringContent(strval($emailCampaign), 25);
		//todo: wizard
		$gridViewId              = 'notUsed';
		$pageVar                 = 'notUsed';
		$tabView = new EmailCampaignTabView(
				'default',
				'emailCampaigns',
				$emailCampaign, //Just to fill in a marketing model
				$gridViewId,
				$pageVar,
				false,
				'EmailCampaignContentLink');
		
		$contentView                   = new EmailCampaignContentView($this->getId(), $this->getModule()->getId(),
				$this->attemptToSaveModelFromPost($emailCampaign),
				Zurmo::t('Default', 'Build My Email'));
		
		$gridView = new GridView(2, 1);
		$gridView->setView($tabView, 0, 0);
		$gridView->setView($contentView, 1, 0);
		
		$view               = new EmailCampaignsPageView(ZurmoDefaultViewUtil::
				makeViewWithBreadcrumbsForCurrentUser($this, $gridView,
						$breadcrumbLinks, 'MarketingBreadCrumbView'));
		echo $view->render();
	}
	
	public function actionAjaxHandleImg()
	{
		if(Yii::app()->request->isAjaxRequest) {
			Yii::log("Post data is: " . print_r($_POST, true));
			//Copy all the images into related image path
			foreach($_POST as $imgKey => $imgPath) {
				if($imgKey != 'campaignId') {
					//Load the selected image
					$image = Yii::app()->image->load(Yii::app()->basePath . '/../' . $imgPath);
	
					//Save to specific location
					$imgDir = Yii::app()->basePath . "/modules/emailCampaigns/assets/dynamicImages/" . Yii::app()->user->name;
						
					//Create the campaign image folder if not exist
					$folderPath = $imgDir . '/' . $_POST['campaignId'] . '/';
					if (!file_exists($folderPath)) {
						mkdir($folderPath, 0755);
					}
					Yii::log("The saved file is: " . $folderPath . $imgKey . '.jpg');
					$image->save($folderPath . $imgKey . '.jpg');
				}
			}
			echo CJSON::encode("Success");
		} else {
			echo CJSON::encode("Fail");
		}
	
	}
	
	public function actionPreview($id)
	{
		$emailCampaign           = Campaign::getById(intval($id));
		ControllerSecurityUtil::resolveAccessCanCurrentUserWriteModel($emailCampaign);
	
		// check if press button "save and next"
		$request = Yii::app()->request;
		$isNext = $request->getPost('save_and_next', null);
		if(!empty($isNext)) {
			//Redirect to the next step: Testing Campaign if Save and Next clicked.
			$emailCampaign = $this->attemptToSaveModelFromPost($emailCampaign, 'testing');
		}
	
		$breadcrumbLinks    = static::getDetailsAndEditBreadcrumbLinks();
		$breadcrumbLinks[]  = StringUtil::getChoppedStringContent(strval($emailCampaign), 25);
		//todo: wizard
		$gridViewId              = 'notUsed';
		$pageVar                 = 'notUsed';
		$tabView = new EmailCampaignTabView(
				'default',
				'emailCampaigns',
				$emailCampaign, //Just to fill in a marketing model
				$gridViewId,
				$pageVar,
				false,
				'EmailCampaignPreviewLink');
	
		$contentView                   = new EmailCampaignPreviewView($this->getId(), $this->getModule()->getId(),
				$this->attemptToSaveModelFromPost($emailCampaign),
				Zurmo::t('Default', 'Preview My Email'));
	
		$gridView = new GridView(2, 1);
		$gridView->setView($tabView, 0, 0);
		$gridView->setView($contentView, 1, 0);
	
		$view               = new EmailCampaignsPageView(ZurmoDefaultViewUtil::
				makeViewWithBreadcrumbsForCurrentUser($this, $gridView,
						$breadcrumbLinks, 'MarketingBreadCrumbView'));
		echo $view->render();
	}
	
	public function actionTesting($id)
	{
		
		$mode = "";
		if(isset($_POST['mode']))
		{
			$mode = strtolower(trim($_POST['mode']));
		}
		
		if($mode != '')
		{
			//Get the selected emails
			while(list($key,$val) = each($_POST))
			{
				if(substr($key, 0, 6) == "chkid_")
				{
					$selectedEmailArray[] = trim($val);
				}
			}
			
			//Try to remove the test emails
			if($mode == 'removetest') 
			{
				//Delete the testing emails from DB
				
				//Unfreeze the RedBean Database first
				RedBeanDatabase::unfreeze();
				
				
				$userBean = R::findOne('_user', "id = :id ", array(':id' => Yii::app()->user->userModel->id));
				if ($userBean === false)
				{
					throw new NotFoundException();
				}
				
				foreach($selectedEmailArray as $eachSelectedEmail)
				{
					$emailCampaignTestingEmailBeans = $userBean->ownCampaigntestemail;
					foreach($emailCampaignTestingEmailBeans as $eachEmailCampaignTestingEmailBean)
					{
						if($eachEmailCampaignTestingEmailBean->email == $eachSelectedEmail)
						{
							R::trash( $eachEmailCampaignTestingEmailBean );
						}
					}	
				}
				R::store($userBean);
				
				RedBeanDatabase::freeze();
			}
			//Try to send out testing emails
			else if($mode == 'sendtest') 
			{
				$emailOpened = 0;
				$emailFailed = 0;
				
				//Unfreeze the RedBean Database first
				RedBeanDatabase::unfreeze();
				$userBean = R::findOne('_user', "id = :id ", array(':id' => Yii::app()->user->userModel->id));
				if ($userBean === false)
				{
					throw new NotFoundException();
				}
				
				foreach ($selectedEmailArray as $eachSelectedEmail)
				{
					//Add Test Emails if not exist
					$emailCampaignTestingEmailExistBean = R::findOne('campaigntestemail', "email = :email ", array(':email' => $eachSelectedEmail));
					if($emailCampaignTestingEmailExistBean == null)
					{
						$emailCampaignTestingEmailBean = R::dispense('campaigntestemail');
						$emailCampaignTestingEmailBean->email = $eachSelectedEmail;
						R::store($emailCampaignTestingEmailBean);
						$userBean->ownCampaigntestemail[] = $emailCampaignTestingEmailBean;
					}

					//Try to send each email out -- To add codes here for sending emails...
				
					//If email been failed, add the number of emailFailed
				}
				R::store($userBean);
				RedBeanDatabase::freeze();
				
				//Check the testing email report and count the opened number here
					
				//Then save the testing history if any
				if(count($selectedEmailArray) > 0)
				{
					//Unfreeze the RedBean Database first
					RedBeanDatabase::unfreeze();
						
					$emailCampaignBean = R::load('campaign', intval($id));
					$numHistory = count($emailCampaignBean->ownCampaigntesthistory);
					$emailCampaignTestingHistoryBean = R::dispense('campaigntesthistory');
					$emailCampaignTestingHistoryBean->name = $emailCampaignBean->name . '(Test ' . ($numHistory + 1) . ')';
					$emailCampaignTestingHistoryBean->sendDateTime = DateTimeUtil::convertTimestampToDbFormatDateTime(time());
					$emailCampaignTestingHistoryBean->totalSent = count($selectedEmailArray);
					$emailCampaignTestingHistoryBean->totalTargeted = ( count($selectedEmailArray) - $emailFailed);
					$emailCampaignTestingHistoryBean->totalOpened = $emailOpened;
					$emailCampaignTestingHistoryBean->totalFailed = $emailFailed;
				
					R::store($emailCampaignTestingHistoryBean);
					$emailCampaignBean->ownCampaigntesthistory[] = $emailCampaignTestingHistoryBean;
					R::store($emailCampaignBean);
						
					RedBeanDatabase::freeze();
				}
			}	
		}

		//Load the email campaign history bean
		$emailCampaignBean = R::load('campaign', intval($id));
		$emailCampaignTestingHistoryBeanArr = $emailCampaignBean->ownCampaigntesthistory;
		
		//Load the testing emails
		$userBean = R::findOne('_user', "id = :id ", array(':id' => Yii::app()->user->userModel->id));
		if ($userBean === false)
		{
			throw new NotFoundException();
		}
		$emailCampaignTestingEmailBeanArr = $userBean->ownCampaigntestemail;
		
		
		$emailCampaign           = Campaign::getById(intval($id));
		ControllerSecurityUtil::resolveAccessCanCurrentUserWriteModel($emailCampaign);
	
		// check if press button "save and next"
		$request = Yii::app()->request;
		$isNext = $request->getPost('save_and_next', null);
		if(!empty($isNext)) {
			//Redirect to the next step: Testing Campaign if Save and Next clicked.
			$emailCampaign = $this->attemptToSaveModelFromPost($emailCampaign, 'schedule');
		}
	
		$breadcrumbLinks    = static::getDetailsAndEditBreadcrumbLinks();
		$breadcrumbLinks[]  = StringUtil::getChoppedStringContent(strval($emailCampaign), 25);
		//todo: wizard
		$gridViewId              = 'notUsed';
		$pageVar                 = 'notUsed';
		$tabView = new EmailCampaignTabView(
				'default',
				'emailCampaigns',
				$emailCampaign, //Just to fill in a marketing model
				$gridViewId,
				$pageVar,
				false,
				'EmailCampaignTestingLink');
		
		$testingHistoryView                   = new EmailCampaignTestingHistoryListView($this->getId(), $this->getModule()->getId(),
				$this->attemptToSaveModelFromPost($emailCampaign),
				Zurmo::t('Default', 'Test History'),
				$emailCampaignTestingHistoryBeanArr);
		
		$testingSendView                   = new EmailCampaignTestingSendView($this->getId(), $this->getModule()->getId(),
				$this->attemptToSaveModelFromPost($emailCampaign),
				Zurmo::t('Default', 'Send a Test Message'),
				$emailCampaignTestingEmailBeanArr);
	
		$gridView = new GridView(3, 1);
		$gridView->setView($tabView, 0, 0);
		$gridView->setView($testingHistoryView, 1, 0);
		$gridView->setView($testingSendView, 2, 0);
	
		$view               = new EmailCampaignsPageView(ZurmoDefaultViewUtil::
				makeViewWithBreadcrumbsForCurrentUser($this, $gridView,
						$breadcrumbLinks, 'MarketingBreadCrumbView'));
		echo $view->render();
	}
	
	public function actionSchedule($id)
	{
		$emailCampaign           = Campaign::getById(intval($id));
		ControllerSecurityUtil::resolveAccessCanCurrentUserWriteModel($emailCampaign);
	
		$breadcrumbLinks    = static::getDetailsAndEditBreadcrumbLinks();
		$breadcrumbLinks[]  = StringUtil::getChoppedStringContent(strval($emailCampaign), 25);
		//todo: wizard
		$gridViewId              = 'notUsed';
		$pageVar                 = 'notUsed';
		$tabView = new EmailCampaignTabView(
				'default',
				'emailCampaigns',
				$emailCampaign, //Just to fill in a marketing model
				$gridViewId,
				$pageVar,
				false,
				'EmailCampaignScheduleLink');
	
		$contentView                   = new EmailCampaignScheduleView($this->getId(), $this->getModule()->getId(),
				$this->attemptToSaveModelFromPost($emailCampaign),
				Zurmo::t('Default', 'Schedule Delivery'));
	
		$gridView = new GridView(2, 1);
		$gridView->setView($tabView, 0, 0);
		$gridView->setView($contentView, 1, 0);
	
		$view               = new EmailCampaignsPageView(ZurmoDefaultViewUtil::
				makeViewWithBreadcrumbsForCurrentUser($this, $gridView,
						$breadcrumbLinks, 'MarketingBreadCrumbView'));
		echo $view->render();
	}
	
	public function actionDelete($id)
	{
		$campaign = static::getModelAndCatchNotFoundAndDisplayError('Campaign', intval($id));
		ControllerSecurityUtil::resolveAccessCanCurrentUserDeleteModel($campaign);
		$campaign->delete();
		$this->redirect(array($this->getId() . '/index'));
	}
	
	protected static function getSearchFormClassName()
	{
		return 'CampaignsSearchForm';
	}
	
	protected static function getZurmoControllerUtil()
	{
		return new CampaignZurmoControllerUtil();
	}
}
?>