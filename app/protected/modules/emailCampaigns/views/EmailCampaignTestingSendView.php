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

    class EmailCampaignTestingSendView extends EditView
    {
    	protected $emailCampaignTestingEmailBeanArr;
    	
    	public function __construct($controllerId, $moduleId, $model, $title = null, $emailCampaignTestingEmailBeanArr)
    	{
    		$this->emailCampaignTestingEmailBeanArr   = $emailCampaignTestingEmailBeanArr;
    		parent::__construct($controllerId, $moduleId, $model, $title);
    	}
    	
        public static function getDefaultMetadata()
        {
            $metadata = array(
                'global' => array(
                    'toolbar' => array(
                        'elements' => array(
                            array('type'    => 'CancelLink'),
                        	array('type'    => 'SkipLink', 'redirectUrl' => 'schedule'),
                        	array('type'    => 'CampaignDeleteLink'),
                        ),
                    ),
                    'panels' => array(),
                ),
            );
            return $metadata;
        }
        
        protected static function getFormId()
        {
        	return 'form-email-campaign-send';
        }
        
    	protected function renderAfterFormLayout($form)
        {
        //    $content = 'Testing panel...';
        
        	$inlineJS = '       			
			<script type="text/JavaScript" language="javascript">
				var testlimit = "30";
				var isMsg = "0";
				var isList = "0";
				
				function checkAll(val) {
				   	var checkboxElements = $("#testEmailList").find("input[type=checkbox]");
					var elename = "";
					for(var i = 1; i <= checkboxElements.length; i++){
						elename = "chkid_" + i;
						document.getElementById(elename).checked = val;
					}
				}
        			

				function removeTest() {
        	//		var theForm = document.getElementById("formEmailCampaignTestingSend");
					document.getElementById("errTB").innerHTML = "";
					var isCheck = false;
					var elename = "";
					var len = $("#testEmailList").find("input[type=checkbox]").length;
					if(len == 0){
						var errmsg = "";
						errmsg += "	<p>Please fix the following input errors:</p>";
						errmsg += "	<ul>";
						errmsg += "     <li>Please add at least one testing email address.</li>";
						errmsg += "	</ul>";
						document.getElementById("errTB").innerHTML = errmsg;
        				document.getElementById("errTB").style.display="block";
						return false;
					}else{
						for(var i = 1; i <= len; i++){
							elename = "chkid_" + i;
							if(document.getElementById(elename).checked){
								isCheck = true;
								break;
							}
						}
					}
					
					if(!isCheck){
						var errmsg = "";
        				errmsg += "	<p>Please fix the following input errors:</p>";
						errmsg += "	<ul>";
						errmsg += "     <li>Please select testing email address to remove.</li>";
						errmsg += "	</ul>";
						document.getElementById("errTB").innerHTML = errmsg;
        				document.getElementById("errTB").style.display="block";
						return false;
					}else{
        				document.getElementById("errTB").style.display="none";
						var c=confirm("Do you want to remove test email?");
						if(c==true){
							document.getElementById("hidval").value = len;
							document.getElementById("mode").value = "RemoveTest";
							document.getElementById("form-email-campaign-send").submit();
						}
					}
				}

        			
				function clickTest(){
					document.getElementById("errTB").innerHTML = "";
					var len = $("#testEmailList").find("input[type=checkbox]").length;
					var elename = "";
					var isCheck = false;
					if(isMsg == 1){
        				var errmsg = "";
						errmsg += "	<p>Please fix the following input errors:</p>";
						errmsg += "	<ul>";
						errmsg += "     <li>Please enter Email Content.</li>";
						errmsg += "	</ul>";
						document.getElementById("errTB").innerHTML = errmsg;
        				document.getElementById("errTB").style.display="block";
						return false;
					}
					
					if(len == 0){
						var errmsg = "";
						errmsg += "	<p>Please fix the following input errors:</p>";
						errmsg += "	<ul>";
						errmsg += "     <li>Please add at least one testing email address.</li>";
						errmsg += "	</ul>";
						document.getElementById("errTB").innerHTML = errmsg;
        				document.getElementById("errTB").style.display="block";
						return false;
					}else{
						for(var i = 1; i <= len; i++){
							elename = "chkid_" + i;
							if(document.getElementById(elename).checked){
								isCheck = true;
								break;
							}
						}
					}
					if(!isCheck){
						var errmsg = "";
        				errmsg += "	<p>Please fix the following input errors:</p>";
						errmsg += "	<ul>";
						errmsg += "     <li>Please select testing email address to send.</li>";
						errmsg += "	</ul>";
						document.getElementById("errTB").innerHTML = errmsg;
        				document.getElementById("errTB").style.display="block";
						return false;
					}else{
        				document.getElementById("hidval").value = len;
						document.getElementById("mode").value = "SendTest";
						document.getElementById("form-email-campaign-send").submit();
					}
				}
        			
			</script>
        			';
            
			$content = '
            <div id="errTB" class="errorSummary" style="display:none;"></div>
            
            	<input type="hidden" id="externalRequestToken" name="externalRequestToken" value="' . ZURMO_TOKEN . '">
            	<input type="hidden" name="hidval" id="hidval">
            	<input type="hidden" name="mode" id="mode">
            	<table cellspacing="0" cellpadding="0">
            		<tbody><tr>
            			<td valign="middle" width="350px">
            				<div style="height:216px; border-right:2px solid #999999; width:100%;">
            					<div style="padding-left:32px; padding-top:5px;" class="email_formlabel">
            						Add Email
            					</div>
            					<div style="padding-left:32px; padding-top:5px;">
            						<input type="text" name="addemail" id="addemail" style="width:200px;">
            						<input type="button" value=" Add " id="btnAddEmail" />
            					</div>
            				</div>
            			</td>
            			<td valign="top">
            				<div style="padding-left:4px; border-bottom:1px">
            					<table border="0" cellpadding="0" cellspacing="0" width="440px">
            						<tbody><tr>
            							<td width="20px">
            								<div style="padding-top:5px;">
            									<input type="checkbox" onclick="checkAll(this.checked)">
            								</div>
            							</td>
            							<td>
							            	<div style="float: left; padding-top: 5px; padding-left:5px;" class="email_formlabel">
							            		Email Address
							            	</div>
							            	<div style="float: right; padding-top: 5px; padding-right:10px;">
							            		<input type="button" value="Remove Email" onclick="removeTest()">&nbsp;
							            		<input type="button" value="Send Test" onclick="clickTest()">
							            	</div>
							            </td>
            						</tr>
            						<tr>
						            	<td colspan="2" align="center">
						            		<div style="padding-top:3px;">
						            			<div style="border-top:1px solid #CCCCCC; width:420px;height:4px"></div>
						            		</div>
						            	</td>
						            </tr>
            					</tbody></table>
            				</div>
            				<div style="padding-left:4px; height:150px; width:440px; border-right: 1px solid #999999; overflow-y:scroll">
            					<table border="0" cellpadding="0" cellspacing="0" width="440px">
            						<tbody id="testEmailList">';
            $emailIndex = 1;
            foreach($this->emailCampaignTestingEmailBeanArr as $eachEmailCampaignTestingEmailBean)
            {
            	$content .= '			<tr>
	            							<td width="20px">
	            								<div style="padding:3px 0px;">
	            									<input type="checkbox" name="chkid_' . $emailIndex . '" id="chkid_' . $emailIndex . '" value="' . $eachEmailCampaignTestingEmailBean->email . '"/>
	            								</div>
	            							</td>
	            							<td>
	                                        	<div style="float: left; padding-top: 0px; padding-left:5px;">
	                                             	<input type="text" name="emailaddr_' . $emailIndex . '" id="emailaddr_' . $emailIndex . '" readonly="readonly" value="' . $eachEmailCampaignTestingEmailBean->email . '" style="width:250px; font-size:11px; background-color:#ededed;">
	                                       		</div>
	                                  		</td>
	            						</tr>';
            	$emailIndex++;
            }
			$content .= '			</tbody>
            					</table>				
            				</div>
            			</td>	
            		</tbody></tr>
            	</table> 
            ';           
            
            $content .= $inlineJS;
            return $content;
        }
        
        protected function renderContent()
        {
        	$this->registerAddNewTestEmailAddressScript();
        	return parent::renderContent();
        }
        
        protected function registerAddNewTestEmailAddressScript()
        {
        	// Begin Not Coding Standard
        	Yii::app()->clientScript->registerScript('addNewTestEmailAddressScript', "
                $(document).ready(function() {
				    $('#btnAddEmail').click(function() {
				        addCheckbox($('#addemail').val());
				    });
				});
				
				function addCheckbox(name) {
        			var testlimit = \"5\";
					var len = $(\"#testEmailList\").find(\"input[type=checkbox]\").length;
					if(len > testlimit){
						window.alert(\"Testing only allow contain \" + testlimit + \" Email Address\");
						return false;
					}
					
					var input = document.getElementById(\"addemail\").value;		
					var reg=new RegExp(\" \",\"g\");
					input=input.replace(reg,''); 
					
					if(isEmpty(input)){
						window.alert(\"Please enter a Email Address\");
						return false;
					}
					
					if(!emailValidation(input)){
						window.alert(\"Please enter a valid Email Address\");
						return false;
					}
					
					var elename = \"\";
					for(var i = 1; i <= len; i++){
						elename = \"emailaddr_\" + i;
						
						if(document.getElementById(elename).value.toLowerCase() == input.toLowerCase()){
							window.alert(\"Duplicate Email Address\");
							return false;
						}
					}
					
					document.getElementById(\"addemail\").value = \"\";
					

				   	var container = $('#testEmailList');
				   	var inputs = container.find('input[type=text]');
				   	var id = inputs.length+1;
        			
        			var htmlCode = '<tr><td width=\"20px\"><div style=\"padding:3px 0px;\">';
        			htmlCode 	+= '<input type=\"checkbox\" name=\"chkid_' + id + '\" id=\"chkid_' + id + '\" value=\"' + name + '\"/>';
        			htmlCode 	+= '<td><div style=\"float: left; padding-top: 0px; padding-left:5px;\">';
        			htmlCode 	+= '<input type=\"text\" name=\"emailaddr_' + id + '\" id=\"emailaddr_' + id + '\" readonly=\"readonly\"';
        			htmlCode 	+= ' 	value=\"' + name + '\" style=\"width:250px; font-size:11px; background-color:#ededed;\">';
        			
        		   	$(htmlCode).appendTo(container);
        			
        		}	
            ");
        	// End Not Coding Standard
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