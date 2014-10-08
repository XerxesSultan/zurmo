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
    * Campign RSS Reader API Controller
    */
    class EmailCampaignsCampaignRSSReaderApiController extends ZurmoModuleApiController
    {
        protected function getModelName()
        {
            return 'Contact';
        }

        protected static function getSearchFormClassName()
        {
            return 'EmailCampaignsSearchForm';
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
        
       	public function actionGetRSSEmailTemplate()
       	{
       		$params = Yii::app()->apiRequest->getParams();
       		if (!isset($params['rss_url']))
       		{
       			$message = Yii::t('Default', 'Please provide the RSS related URL.');
       			throw new ApiException($message);
       		}
       		if (!isset($params['email_template']))
       		{
       			$message = Yii::t('Default', 'Please provide the email template.');
       			throw new ApiException($message);
       		}
       		if (!isset($params['rss_max_item_num']))
       		{
       			$message = Yii::t('Default', 'Please provide the maximum number of the returned RSS items.');
       			throw new ApiException($message);
       		}
       		if (!isset($params['rss_max_item_content_length']))
       		{
       			$message = Yii::t('Default', 'Please provide the maximum length of the returned RSS items\' content.');
       			throw new ApiException($message);
       		}
       		$this->getRSSEmailTemplate($params);
       	}
       	
        
        /**
         * Try to return the Email Template with related latest RSS items
         * @throws ApiException
         */
        protected function getRSSEmailTemplate($params)
        {
        	try
        	{
        		$data = array();
        		
        		//Get the RSS key value pair
        		$ch = curl_init();
        		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        		curl_setopt($ch, CURLOPT_URL, $params['rss_url']);    // get the url contents
        		
        		$rssXMLData = curl_exec($ch); // execute curl request
        		curl_close($ch);
        		
        		$rssArrayData = $this->parseRSSFeed( XML2Array::createArray($rssXMLData), intval($params['rss_max_item_num']), $params['rss_max_item_content_length'] );
        		foreach($rssArrayData as $rssArrKey=>$rssArrVal)
        		{
        			if($rssArrKey != 'RSSITEMS')
        			{
        				$data['RSSFEEDS'][$rssArrKey] = $rssArrVal;
        				$data['RSSFEEDS']['@attributes']['xmlns:RSSFEED'] = 'XGATE_RSS_FEED';
        			}
        			else 
        			{
        				
        				$data['RSSITEMS'] = $rssArrVal;        				
        			}
        		}
        		
        		//Parse the latest RSS items into the given email template and return a new one
        		$data['emailTemplate'][] = $this->parseEmailTemplate($params['email_template'], $rssArrayData);
        		
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
         * Try to parse the RSS items
         * @return array mapping of the RSS item custom fields and values
         */
        protected function parseRSSFeed($rssArray, $rssMaxItemNum, $rssMaxItemContentLength)
        {
        	//To remove the first two level XML elements of the RSS
        	foreach($rssArray as $rssChannelArr)
        	{
        		$rssDetailArr = $rssChannelArr['channel'];
        	}
        	
        	$rssFeedMapArr = array();
        	
        	//Loop the RSS XML file and build the mapping array
        	$parsedRssItemNum = 0;
        	foreach($rssDetailArr as $rssKey=>$rssItem)
        	{
        		if($rssKey == 'title')
        		{
        			$rssFeedMapArr['RSSFEED:TITLE'] = $rssItem;
        		}
        		//lastBuildDate under channel is standard RSS 2.0 element
        		//However, the sample XML file used pubDate under channel element
        		else if($rssKey == 'pubDate' || $rssKey == 'lastBuildDate')
        		{
        			$rssFeedMapArr['RSSFEED:DATE'] = $rssItem;
        		}
        		else if($rssKey == 'link')
        		{
        			$rssFeedMapArr['RSSFEED:URL'] = $rssItem;
        		}
        		else if($rssKey == 'description')
        		{
        			$rssFeedMapArr['RSSFEED:DESCRIPTION'] = $rssItem;
        		}
        		//Handle each RSS item here
        		else if($rssKey == 'item')
        		{
        			foreach($rssItem as $eachRssItem)
        			{
        				if(isset($eachRssItem['title']) && isset($eachRssItem['link']) && $parsedRssItemNum<$rssMaxItemNum)
        				{
        					$rssItemArr = array();
        					$rssItemCategoryStr = '';
        					foreach($eachRssItem as $eachRssItemKey=>$eachRssItemValue)
        					{
        						if($eachRssItemKey == 'title')
        						{
        							$rssItemArr['RSSITEM:TITLE'] = $eachRssItemValue;
        						}
        						else if($eachRssItemKey == 'link')
        						{
        							$rssItemArr['RSSITEM:URL'] = $eachRssItemValue;
        						}
        						else if($eachRssItemKey == 'pubDate')
        						{
        							$rssItemArr['RSSITEM:DATE'] = $eachRssItemValue;
        						}
        						else if($eachRssItemKey == 'category')
        						{
        							foreach($eachRssItemValue as $eachCategoryKey=>$eachCategoryItem)
        							{
        								if($rssItemCategoryStr != '')
        								{
        									$rssItemCategoryStr .= ', ';
        								}
        								$rssItemCategoryStr .= implode(",", $eachCategoryItem);
        							}
        						}
        						else if($eachRssItemKey == 'description')
        						{
        							$eachRssItemDescriptionStr = implode(",", $eachRssItemValue);
        							$rssItemArr['RSSITEM:CONTENT_FULL'] = $eachRssItemDescriptionStr;
        							//The RSSITEM:CONTENT has to limit maximum predefined words
        							if(strlen($eachRssItemDescriptionStr) > $rssMaxItemContentLength)
        							{
        								$rssItemArr['RSSITEM:CONTENT'] = mb_substr($eachRssItemDescriptionStr, 0, $rssMaxItemContentLength, 'UTF-8');
        								$rssItemArr['RSSITEM:CONTENT'] .= '...';
        							}
        							else 
        							{
        								$rssItemArr['RSSITEM:CONTENT'] = $eachRssItemDescriptionStr;
        							}
        						}
        						else if($eachRssItemKey == 'enclosure')
        						{
        							$rssItemEnclosureArr = array();
        							if(is_array($eachRssItemValue))
        							{
        								foreach($eachRssItemValue as $eachRssEnclosureElementKey=>$eachRssEnclosureElementValue)
        								{
        									if($eachRssEnclosureElementKey == '@attributes')
        									{
        										foreach($eachRssEnclosureElementValue as $eachRssEnclosureElementAttributeKey=>$eachRssEnclosureElementAttributeValue)
        										{
        											$rssItemEnclosureArr[$eachRssEnclosureElementAttributeKey] = $eachRssEnclosureElementAttributeValue;
        											if($eachRssEnclosureElementAttributeKey == 'url')
        											{
        												$rssItemArr['RSSITEM:ENCLOSURE_URL'] = $eachRssEnclosureElementAttributeValue;
        											}
        										}
        									}
        									else // $eachRssEnclosureElementKey == '@value'
        									{
        										$rssItemEnclosureArr['content'] = $eachRssEnclosureElementValue;
        									}
        								}
        								
        							}
        							else 
        							{
        								$rssItemEnclosureArr['content'] = $eachRssItemValue;
        							}
        							//Build the RSSITEM:ENCLOSURE
        							if($rssItemEnclosureArr['type'] == 'image/jpeg')
        							{
        								$rssItemArr['RSSITEM:ENCLOSURE'] = '<img src="' . $rssItemEnclosureArr['url'] . '"/>';
        							}
        						}
        						else 
        						{
        							//Do nothing here, may add other types later here.
        						}
        					}
        					if($rssItemCategoryStr != '')
        					{
        						$rssItemArr['RSSITEM:CATEGORIES'] = $rssItemCategoryStr;
        					}
        					if( isset($rssItemArr['RSSITEM:CONTENT']) && mb_strlen($rssItemArr['RSSITEM:CONTENT'], 'UTF-8')==30 && isset($rssItemArr['RSSITEM:URL']) )
        					{
        						$rssItemArr['RSSITEM:CONTENT'] .= '<a href="' .  $rssItemArr['RSSITEM:URL'] . '">
																		read more...';
        					}
        					if(isset($rssItemArr['RSSITEM:CONTENT_FULL']))
        					{      						
        						// Include the class definition file.
        						// require_once('../assets/html2text/class.html2text.inc');
 
        						// Instantiate a new instance of the class. Passing the string
        						// variable automatically loads the HTML for you.
        						$h2t = new html2text($rssItemArr['RSSITEM:CONTENT']);
        						$h2tFull = new html2text($rssItemArr['RSSITEM:CONTENT_FULL']);
        						
        						// Simply call the get_text() method for the class to convert
        						// the HTML to the plain text. Store it into the variable.
        						$rssItemArr['RSSITEM:CONTENT_TEXT'] = $h2t->get_text();
        						$rssItemArr['RSSITEM:CONTENT_FULL_TEXT'] = $h2tFull->get_text();
        					}
        					$rssItemArr['@attributes']['xmlns:RSSITEM'] = 'XGATE_RSS_ITEM';
        					$rssFeedMapArr['RSSITEMS'][] = $rssItemArr;
        					
        					$parsedRssItemNum++;
        				}
        			}        			
        			
        		}
        	}
        	
        	return $rssFeedMapArr;
        }
        
        /**
         * Try to parse the Email Template and replace the RSS Custom Fields with related values
         * @return string The parsed email template
         */
        protected function parseEmailTemplate($originalEmailTemplate, $rssElementsArr)
        {
        	$parsedEmailTemplate = $originalEmailTemplate;
        	
        	//Parse the email template below
        	//Merge tags for feeds：
        	//*|RSSFEED:TITLE|*
        	$parsedEmailTemplate = str_replace("*|RSSFEED:TITLE|*", $rssElementsArr['RSSFEED:TITLE'], $parsedEmailTemplate);
        	//*|RSSFEED:DATE|*
        	$parsedEmailTemplate = str_replace("*|RSSFEED:DATE|*", $rssElementsArr['RSSFEED:DATE'], $parsedEmailTemplate);
        	//*|RSSFEED:URL|*
        	$parsedEmailTemplate = str_replace("*|RSSFEED:URL|*", $rssElementsArr['RSSFEED:URL'], $parsedEmailTemplate);
        	//*|RSSFEED:DESCRIPTION|*
        	$parsedEmailTemplate = str_replace("*|RSSFEED:DESCRIPTION|*", $rssElementsArr['RSSFEED:DESCRIPTION'], $parsedEmailTemplate);
        	
        	//Merge tags for RSS items
        	$parsedEmailTemplate = $this->parseRssItems($parsedEmailTemplate, $rssElementsArr['RSSITEMS']);
        	
        	return $parsedEmailTemplate;
        }
        
        /**
         * Try to parse the HTML between Rss Items and replace the RSS Item Custom Fields with related values
         * @return string The parsed email template
         */
        protected function parseRssItems($parsedEmailTemplate, $rssItemElementsArr)
        {
        	
        	//Check whether the RSS item start and end marker are been piared(has same number) else return exception
        	$rssItemsStartMarkerNum = substr_count($parsedEmailTemplate, '*|RSSITEMS:|*');
        	$rssItemsEndMarkerNum = substr_count($parsedEmailTemplate, '*|END:RSSITEMS|*');
        	if($rssItemsStartMarkerNum === $rssItemsEndMarkerNum && $rssItemsStartMarkerNum == 1)
        	{
        		$rssItemInnerStr = $this->get_string_between($parsedEmailTemplate, "*|RSSITEMS:|*", "*|END:RSSITEMS|*");
        		//Replace the Inner RSS item String with specific values
        		$finalRssItemStr = '';
        		foreach($rssItemElementsArr as $eachRssItemElementKey=>$eachRssItemElement)
        		{
        			$eachRssItemStr = $rssItemInnerStr;
        			foreach($eachRssItemElement as $eachRssItemElementFieldKey=>$eachRssItemElementFieldValue)
        			{
        				if(is_array($eachRssItemElementFieldValue))
        					$eachRssItemElementFieldValue = implode(",", $eachRssItemElementFieldValue);
        				
        				if($eachRssItemElementFieldKey == 'RSSITEM:TITLE')
        					$eachRssItemStr = str_replace('*|RSSITEM:TITLE|*', $eachRssItemElementFieldValue, $eachRssItemStr);
        				else if($eachRssItemElementFieldKey == 'RSSITEM:URL')
        					$eachRssItemStr = str_replace('*|RSSITEM:URL|*', $eachRssItemElementFieldValue, $eachRssItemStr);
        				else if($eachRssItemElementFieldKey == 'RSSITEM:DATE')
        					$eachRssItemStr = str_replace('*|RSSITEM:DATE|*', $eachRssItemElementFieldValue, $eachRssItemStr);
        				else if($eachRssItemElementFieldKey == 'RSSITEM:CATEGORIES')
        					$eachRssItemStr = str_replace('*|RSSITEM:CATEGORIES|*', $eachRssItemElementFieldValue, $eachRssItemStr);
        				else if($eachRssItemElementFieldKey == 'RSSITEM:CONTENT')
        					$eachRssItemStr = str_replace('*|RSSITEM:CONTENT|*', $eachRssItemElementFieldValue, $eachRssItemStr);
        				else if($eachRssItemElementFieldKey == 'RSSITEM:CONTENT_FULL')
        					$eachRssItemStr = str_replace('*|RSSITEM:CONTENT_FULL|*', $eachRssItemElementFieldValue, $eachRssItemStr);
        				else if($eachRssItemElementFieldKey == 'RSSITEM:CONTENT_TEXT')
        					$eachRssItemStr = str_replace('*|RSSITEM:CONTENT_TEXT|*', $eachRssItemElementFieldValue, $eachRssItemStr);
        				else if($eachRssItemElementFieldKey == 'RSSITEM:CONTENT_FULL_TEXT')
        					$eachRssItemStr = str_replace('*|RSSITEM:CONTENT_FULL_TEXT|*', $eachRssItemElementFieldValue, $eachRssItemStr);
        				else if($eachRssItemElementFieldKey == 'RSSITEM:ENCLOSURE')
        					$eachRssItemStr = str_replace('*|RSSITEM:ENCLOSURE|*', $eachRssItemElementFieldValue, $eachRssItemStr);
        				else if($eachRssItemElementFieldKey == 'RSSITEM:ENCLOSURE_URL')
        					$eachRssItemStr = str_replace('*|RSSITEM:ENCLOSURE_URL|*', $eachRssItemElementFieldValue, $eachRssItemStr);
        				
        			}
        			//Terry said not to contain such symbol, but I'm not sure how he separate the contents...
//         			if($finalRssItemStr != '')
//         				$finalRssItemStr .= '<br/>--------------------------------<br/>';
        			$finalRssItemStr .= $eachRssItemStr;
        		}
        		
        		$parsedEmailTemplate = str_replace($rssItemInnerStr, $finalRssItemStr, $parsedEmailTemplate);
        		
//         		//Find out the first occurrence of RSS item start and end marker position
//         		$rssItemsStartMarkerIndex = strpos($parsedEmailTemplate, '*|RSSITEMS:|*');
//         		$rssItemsEndMarkerIndex = strpos($parsedEmailTemplate, '*|END:RSSITEMS|*');
//         		//Only when this pair of marker exist should replace the Rss item custom fields between them
//         		if($rssItemsStartMarkerIndex!==false && $rssItemsEndMarkerIndex!==false)
//         		{
//         			//Get the string between two marker
//         			$parsed = get_string_between($fullstring, "[tag]", "[/tag]");
        			
//         			$rssItemsStartMarkerLastPos = 0;
//         			$rssItemsStartMarkerPositions = array();
//         			while ($rssItemsStartMarkerLastPos = strpos($parsedEmailTemplate, '*|RSSITEMS:|*', $rssItemsStartMarkerLastPos)) {
//         				$rssItemsStartMarkerPositions[] = $rssItemsStartMarkerLastPos;
//         				$rssItemsStartMarkerLastPos = $rssItemsStartMarkerLastPos + strlen('*|RSSITEMS:|*');
//         			}
        			 
//         			$rssItemsEndMarkerLastPos = 0;
//         			$rssItemsEndMarkerPositions = array();
//         			while ($rssItemsEndMarkerLastPos = strpos($parsedEmailTemplate, '*|END:RSSITEMS|*', $rssItemsEndMarkerLastPos)) {
//         				$rssItemsEndMarkerPositions[] = $rssItemsEndMarkerLastPos;
//         				$rssItemsEndMarkerLastPos = $rssItemsEndMarkerLastPos + strlen('*|END:RSSITEMS|*');
//         			}
        			 
//         			// Handle the multiple RSS Items
//         			$firstPartIndex = 0;
//         			foreach ($rssItemsEndMarkerPositions as $eachRssItemMarkerIndex=>$eachRssItemStartMarkerPosition) {
//         				$parsedEmailTemplate = substr($parsedEmailTemplate, $firstPartIndex, ($eachRssItemStartMarkerPosition - strlen('*|RSSITEMS:|*')))
//         										. '--ReplacedHere[' . $eachRssItemStartMarkerPosition . ']--'
//         										. substr($parsedEmailTemplate, ($rssItemsEndMarkerPositions[$eachRssItemMarkerIndex] + strlen('*|END:RSSITEMS|*')));
//         			}
//         		}
        	}
        	else
        	{
        		$message = Yii::t('Default', 'Please check the *|RSSITEMS:|* and *|END:RSSITEMS|* if they are paired or not.');
        		throw new ApiException($message);
        	}
        	//Remove the *|RSSITEMS:|* and *|END:RSSITEMS|*
        	$parsedEmailTemplate = str_replace('*|RSSITEMS:|*', '', $parsedEmailTemplate);
        	$parsedEmailTemplate = str_replace('*|END:RSSITEMS|*', '', $parsedEmailTemplate);
        	return $parsedEmailTemplate;
        }
        
        protected function get_string_between($string, $start, $end){
        	$string = " ".$string;
        	$ini = strpos($string,$start);
        	if ($ini == 0) return "";
        	$ini += strlen($start);
        	$len = strpos($string,$end,$ini) - $ini;
        	return substr($string,$ini,$len);
        }
   
    }
?>