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
    * Campign Designer API Controller
    */
    class EmailCampaignsCampaignDesignerApiController extends ZurmoModuleApiController
    {
        protected function getModelName()
        {
            return 'Contact';
        }

        protected static function getSearchFormClassName()
        {
            return 'ContactsSearchForm';
        }

        /**
        * We cant use Module::getStateMetadataAdapterClassName() because that references
        * to Contact model and we are using ContactState model.
        */
        public function getStateMetadataAdapterClassName()
        {
            return null;
        }

        public function actionCreate()
        {
            throw new ApiUnsupportedException();
        }

        public function actionUpdate()
        {
            throw new ApiUnsupportedException();
        }

        public function actionDelete()
        {
            throw new ApiUnsupportedException();
        }
        
       	public function actionGetMarketingList()
       	{
       		$this->getMarketingList();
       	}
       	
       	public function actionGetContactFilterUI()
       	{
       		$this->getContactFilterUI();
       	}
       	
       	public function actionGetContactFilterResult()
       	{
       		$params = Yii::app()->apiRequest->getParams();
//        		if (!isset($params['contact_f1']))
//        		{
//        			$message = Yii::t('Default', 'Please provide at least one filter.');
//        			throw new ApiException($message);
//        		}
       		$this->getContactFilterResult($params);
       	}
        
        public function actionTest()
        {
        	$this->testApi();
        }
        
        /**
         * Try to retrieve the marketing list and return
         * @throws ApiException
         */
        protected function getMarketingList()
        {
        	try
        	{
        		$marketingListDataItems = MarketingList::getAll();
	        	$html = '<select name="list" id="list">';
	            $data = array();
	            $list = array();
	            foreach ($marketingListDataItems as $marketingListDataItem)
	            {
	            	$list['id'] = $marketingListDataItem->id;
	            	$list['name'] = $marketingListDataItem->name;
	            	$list['description'] = $marketingListDataItem->description;
	            	$list['fromName'] = $marketingListDataItem->fromName;
	            	$list['fromAddress'] = $marketingListDataItem->fromAddress;
	            	
	            	$html .= '<option value="' . $marketingListDataItem->id . '">' . $marketingListDataItem->name . '</option>';
	            	
	            	$data['lists']['values']['list'][] = $list;
	            }
	            $html .= '</select>';
	            $data['lists']['html'][] = $html;
	            $result = new ApiResult(ApiResponse::STATUS_SUCCESS, $data, null, null);
        		Yii::app()->apiHelper->sendResponse($result);
        	}
        	catch (Exception $e)
        	{
        		$message = $e->getMessage();
        		throw new ApiException($message);
        	}
        }
        
        /**
         * Try to return the Contact Filter UI
         * @throws ApiException
         */
        protected function getContactFilterUI()
        {
        	try
        	{
        		$data = array();
        		$memberArr = array();
        		$ruleArr = array();
        		
        		//Search out the Custom Fields and return
//         		$customFieldDataItems = CustomFieldData::getAll();
//         		foreach ($customFieldDataItems as $customFieldDataItem)
//         		{
//         			$memberArr[] = $customFieldDataItem->name;
//         		}
        		
        		$contactDefaultMetadata = Contact::getDefaultMetadata();
        		foreach($contactDefaultMetadata as $eachItemKey => $eachItemDefaultMetadata)
        		{        			
        			//Load all the possible members
        			if(isset($eachItemDefaultMetadata['members']))
        			{
        				foreach($eachItemDefaultMetadata['members'] as $eachMember)
        				{
        					$memberArr[] = $eachMember;
        				}
        			}
        			
        			//Load the relations
        			if(isset($eachItemDefaultMetadata['relations']))
        			{
        				foreach($eachItemDefaultMetadata['relations'] as $eachRelationKey => $eachRelationVal)
        				{
        					$memberArr[] = $eachRelationKey;
        				}
        			}
        			
        			//Load the custom fields for contact and person
        			if(isset($eachItemDefaultMetadata['customFields']))
        			{
        				foreach($eachItemDefaultMetadata['customFields'] as $eachCustomFieldKey => $eachCustomFieldVal)
        				{
        					if(!in_array($eachCustomFieldKey, $memberArr))
        					{
        						$memberArr[] = $eachCustomFieldKey;
        					}
        				}
        			}
        			
        			//Load all the possible rules
        			if(isset($eachItemDefaultMetadata['rules']))
        			{
        				foreach($eachItemDefaultMetadata['rules'] as $eachRule)
        				{
        					$ruleArr[$eachRule[0]] = $eachRule;
        				}
        			}
        		}
        		
        		$divFieldsStr = '';
        		$divFieldsStr .= '	<select name="contact_fields" id="contact_fields">';
        		
        		$divStr = '';
        		
        		//Generate the HTML code for each item due to the specific rules
        		$counter = 0;
        		foreach($memberArr as $eachMember)
        		{
        			$counter++;
        			
        			$divFieldsStr .= '		<option>' . $eachMember . '</option>';
        			
        			
        			
        			//Check the Rules of the specific item
        			$memberRuleArr = array();
        			$memberRuleArr['type'] = 'string';
        			$memberRuleArr['length'] = '';
        			$memberRuleArr['required'] = '';
        			$memberRuleArr['readOnly'] = '';
        			foreach($ruleArr as $ruleKey => $ruleVal)
        			{
        				if($ruleKey == $eachMember)
        				{
        					if($ruleVal[1] == 'type')
        					{
        						$memberRuleArr['type'] = $ruleVal['type'];
        					}
        					else if($ruleVal[1] == 'length')
        					{
        						$memberRuleArr['length'] = $ruleVal['min'] . '-' . $ruleVal['max'];
        					}
        					else if($ruleVal[1] == 'required')
        					{
        						$memberRuleArr['required'] = 'required';
        					}
        					else if($ruleVal[1] == 'readOnly')
        					{
        						$memberRuleArr['readOnly'] = 'readOnly';
        					}
        				}
        			}
        			
        			$divStr .= '<div name="' . $eachMember . '" id="' . $eachMember . '" 
        							rule="' . $memberRuleArr['type'] . ' ' . $memberRuleArr['required'] . ' ' . $memberRuleArr['readOnly'] . '" ';
        			if($memberRuleArr['length'] != '')
        			{
        				$divStr .= 'length="' . $memberRuleArr['length'] . '"';
        			}
        			$divStr .= '></div>';
        			
        		}
        		$data['filterUI']['contact']['html']['rules'][] = $divStr;
        		
        		$divFieldsStr .= '	</select>';
        		$data['filterUI']['contact']['html']['fields'][] = $divFieldsStr;
        		
       // 		$data['attr'][] = implode("__", Person::getAttributeNames());
       // 		$data['metadata'][] = implode("__", Person::getDefaultMetadata());
        		$result = new ApiResult(ApiResponse::STATUS_SUCCESS, $data, null, null);
        		Yii::app()->apiHelper->sendResponse($result);
        	}
        	catch (Exception $e)
        	{
        		$message = $e->getMessage();
        		throw new ApiException($message);
        	}
        }
        
        /**
         * Try to return the Contact results due to the filters
         * @throws ApiException
         */
        protected function getContactFilterResult($filters)
        {
        	try
        	{
        		$data = array();
        		
        		$filterParams['data'] = array();
        		$filterParams['data']['pagination']['pageSize'] = $filters['pageSize'];
        		$filterParams['data']['pagination']['page'] = $filters['page'];
        		$filterParams['data']['sort'] = $filters['sort'];
        		
        		//Check if any filter exist or not
        		for($i = 1; $i <= 6; $i++)
        		{
        			if( isset($filters['contact_f'.$i]) && isset($filters['contact_v'.$i]) )
        			{
        				if($filters['contact_f'.$i] == 'email')
        				{
        					$filterParams['data']['search']['primaryEmail']['emailAddress'] = $filters['contact_v'.$i];
        				}
        				else 
        				{
        					$filterParams['data']['search'][$filters['contact_f'.$i]] = $filters['contact_v'.$i];
        				}
        				
        			}
        		}
        		
        		
        		$result    =  $this->processList($filterParams);
        		Yii::app()->apiHelper->sendResponse($result);
        	}
        	catch (Exception $e)
        	{
        		$message = $e->getMessage();
        		throw new ApiException($message);
        	}
        }
        
        /**
         * Test the Api by given name
         * @throws ApiException
         */
        protected function testApi()
        {
        	try
        	{
        		$data = Yii::app()->apiRequest->getParams();
        		$result = new ApiResult(ApiResponse::STATUS_SUCCESS, $data, null, null);
        		Yii::app()->apiHelper->sendResponse($result);
        	}
        	catch (Exception $e)
        	{
        		$message = $e->getMessage();
        		throw new ApiException($message);
        	}
        }

        
    }
?>