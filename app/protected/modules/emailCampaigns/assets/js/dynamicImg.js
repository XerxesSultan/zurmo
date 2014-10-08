
var all_imgs_data = [];

//var dynamicFields = {gender:["male", "female"], level:["vip1", "vip2", "vip3"]};

var globalDynamicImgObject = new Object();

var globalDynamicFieldObject = new Object();

var globalCampaignId = "";

var globalAjaxImgUrl = "";

function dynamicImgFull(imgId,imageArr)
{
	this.imgId=imgId;
	this.imageArr=imageArr;
}

function dynamicImgIndividual(fieldVal,imageUrl)
{
	this.fieldVal=fieldVal;
	this.imageUrl=imageUrl;
}

function setupDynamicImg(div_id, campaignId, ajaxImgUrl)
{
	globalCampaignId = campaignId;
	globalAjaxImgUrl = ajaxImgUrl;
	console.log("global Ajax Url = " + globalAjaxImgUrl);
    //clear any previous modal div body with the same id
    $('#modalBody_'+div_id).remove();
    var modal_div = 
        '<div class="modal hide fade" style="width: 900px; margin-left: -450px;">' +
            '<div class="modal-header" style="background-color:#EEEEEE">' +
                '<button type="button" id="modalCloseBtn" class="close" data-dismiss="modal" aria-hidden="true">X</button>' +
                '<h3 id="myModalLabel">View all images in this page</h3>' +
            '</div>' +
            '<div style="display:none;visibility:hidden;" id="modalBodyEditor_'+div_id+'" ></div>' + 
            '<div class="modal-body" id="modalBody_'+div_id+'" >' +
                
                '<div class="loading"></div>' +
            
            '</div>' +
            '<div class="modal-footer">' +
                '<button id="modalFooterCloseBtn" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>' +
                '<button id="modalFooterSaveBtn" class="btn btn-primary" data-dismiss="modal" onclick="saveChanges(\''+div_id+'\');">Save changes</button>' +
            '</div>' +
        '</div>';
    
    console.log('Go into function setupDynamicImg for div: ' + div_id); 
    $(modal_div).modal('show');
    console.log('after show and go to set timeout now');
    setTimeout(function(){ highlightImgs(div_id); }, 500);
 
}


function highlightImgs(div_id)
{
    var modalDivBodySelector = '#modalBody_'+div_id;
        
    var content = tinyMCE.get(div_id).getContent();

    $(modalDivBodySelector).html('<div class="overlay" style="position:fixed;"></div><br>' + content);
    
    var imgs_arr = [];
        
    $(modalDivBodySelector)
        .find('img')
        .each(function(index){
            //we need to avoid internal page imgs
        	$.each($(this), function(key, element) {
        		var img = element.src;
        		if( img.indexOf('#') == -1 )
                {
                    imgs_arr.push(img);
                    
                    //Remove the hyper link if any in the parent element.
                    if (!$(this).parent().is("a")) {
                    	$(this).wrap("<a>");
                    }  
                    $(this).parent().attr("href", "#");
                }
        	});           
        });

    saveAllImgs(div_id, imgs_arr);
                
    $(modalDivBodySelector)
        .find('img')
        .each(function(index){
            //we need to avoid internal page imgs
        	$.each($(this), function(key, element) {
        		var img = element.src;
        		if( img.indexOf('#') == -1 )
                {            
                    var all_imgs = getAllImgs(div_id);

                    
                    for(var j=0; j<all_imgs.length; j++)
                        if(all_imgs[j].img == img)
                            break;

                    var img_id = 'img_'+all_imgs[j].id;
                    
                    $(this).attr('id', img_id);
                    $(this).attr('class', 'glossy-reflection');
                    $(this).attr('title', 'Click to setup Dynamic Image.');
                    
                    //now enable click
                    $(this).attr('onclick', 'configImg(this, \'' + img_id + '\', \'' + all_imgs[j].img + '\');');
                }
        	});            
        });
}

function updateRelativeImgInTinyMCE(editorDivId, tempDivId, imgRootPath)
{
	console.log("updateRelativeImgInTinyMCE-- editorDivId = " + editorDivId + ", tempDivId = " + tempDivId + ", imgRootPath = " + imgRootPath);
	var content = tinyMCE.get(editorDivId).getContent();
	$(tempDivId).html(content);
	
    //Change all relative paths into one campaign level path
    $(tempDivId)
    .find('img')
    .each(function(index){
        //we need to avoid absolute paths
    	$.each($(this), function(key, element) {
    		var imgPath = element.src;
	        if( imgPath.indexOf('/') != 0 && imgPath.indexOf('http://') != 0 && imgPath.indexOf('https://') != 0 && imgPath.indexOf('protected') != 0)
	        {          
	        	if(imgPath.lastIndexOf('/') != -1)
	        		$(this).attr('src', imgRootPath + imgPath.substring(imgPath.lastIndexOf('/')));
	        	else
	        		$(this).attr('src', imgRootPath + '/' + imgPath);
	        }  
    	}); 
    });
    
    $(tempDivId).find('*').each(function(i) { 
      if($(this).css("background-image") != "none") { 
    	  console.log("item is:" + $(this).html());
    	  console.log("Get the background-image: " + $(this).css("background-image")); 
      } 
    });
    
    tinyMCE.get(editorDivId).setContent($(tempDivId).html());
}

function changeDynamicImgUrlInTinyMCE(div_id)
{
	console.log("Go into changeDynamicImgUrlInTinyMCE.");

	var modalDivBodySelector = '#modalBodyEditor_'+div_id;
    
    var content = tinyMCE.get(div_id).getContent();

    $(modalDivBodySelector).html(content);
    
    $(modalDivBodySelector)
    .find('img')
    .each(function(index){
    	//we need to avoid internal page imgs
    	$.each($(this), function(key, element) {
    		var img = element.src;
    		if( img.indexOf('#') == -1 )
            {            
                var all_imgs = getAllImgs(div_id);
                for(var j=0; j<all_imgs.length; j++)
                    if(all_imgs[j].img == img)
                        break;

                var img_id = 'img_'+all_imgs[j].id;
                
                if (globalDynamicFieldObject.hasOwnProperty(img_id)) {
                    console.log("Got the image to change src: " + img_id);
                    
          //          $(this).attr('id', img_id + '##_##' + globalDynamicFieldObject[img_id]);
                    element.alt = img_id + '##_##' + globalDynamicFieldObject[img_id];
                    console.log("Change the alt of img into: " + img_id + '##_##' + globalDynamicFieldObject[img_id]);
                    
                    //Replace the original image into dummy default image
           //         $(this).attr('src', globalDummyImgUrl + $(this).width() + 'x' + $(this).height() +
            //        		'/518751/2e37b8.gif&text=Dynamic+image+Area+(' + $(this).width() + '%20x%20' + $(this).height() +')');
                    element.src = globalDummyImgUrl + $(this).width() + 'x' + $(this).height() +
            		'/518751/2e37b8.gif&text=Dynamic+image+Area+(' + $(this).width() + '%20x%20' + $(this).height() +')';
                    console.log("Change the src of the image into: " + globalDummyImgUrl + $(this).width() + 'x' + $(this).height() +
                    		'/518751/2e37b8.gif&text=Dynamic+image+Area+(' + $(this).width() + '%20x%20' + $(this).height() +')');
                }
            } 
    	});
    });    
    
    tinyMCE.get(div_id).setContent($(modalDivBodySelector).html());
}

function saveChanges(div_id)
{
	copyImages(div_id);
}

function copyImages(div_id) 
{
	//Setup imgUrl and baseUrl to retrieve currently image and save new image
	globalDynamicImgObject["imgPath"] = globalOutImgPath;
	globalDynamicImgObject["baseUrl"] = globalBaseUrl;
	console.log("Campaign ID = " + globalCampaignId);
	console.log("globalDynamicImgObject: " + JSON.stringify(globalDynamicImgObject));
    
    // fire off the request to Copy Images Ajax action
    request = $.ajax({
    	type: "POST",
        url: globalAjaxImgUrl,
        data: globalDynamicImgObject,
        dataType: "json",        
        success : function(data)
        {
        	console.log("Go into success, data= " + data);
        	changeDynamicImgUrlInTinyMCE(div_id);
        },
        error : function(jqXHR, textStatus, errorThrown)
        {
        	// log the error to the console
        	console.error(
                    "The following error occured: "+
                    textStatus, errorThrown
                );
        }
    });


}

function configImg(obj, img_id, img_src)
{
	console.log("Go into configImg for img with id: " + img_id);
    //Disable all the parent window buttons when configuring the child Dynamic Images.
    $("#modalCloseBtn").css('display', 'none');
    $("#modalFooterCloseBtn").attr('disabled', true);
    $("#modalFooterSaveBtn").attr('disabled', true);
    
    
    //clear any previous modal div body with the same id
    $('#modalImg_'+img_id).remove();
    $('#MapDynamicImgSaveBtn_'+img_id).remove();
    
    var modal_dynamicImg_div = 
        '<div class="modal hide fade" style="width: 888px; margin-left: -445px;">' +
            '<div class="modal-header" style="background-color:#EEEEEE">' +
                '<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="closeDynamicImgChanges();">X</button>' +
                '<h3 id="myModalLabel">Setup Dynamic Image in this page</h3>' +
            '</div>' +
            '<div class="modal-body" id="modalImg_'+img_id+'" style="min-height:400px;" >' +
                
                '<div class="loading"></div>' +
            '</div>' +
            '<div class="modal-footer">' +
                '<button class="btn" data-dismiss="modal" aria-hidden="true" onclick="closeDynamicImgChanges();">Close</button>' +
                '<button class="btn btn-primary" data-dismiss="modal" disabled="disabled" id="MapDynamicImgSaveBtn_' + img_id + '" onclick="saveDynamicImgChanges(\''+img_id+'\');">Save changes</button>' +
            '</div>' +
        '</div>';
    
    console.log("Before invoking the modal show.");
    $(modal_dynamicImg_div).modal('show');
    
    setTimeout(function(){ listDynamicImg(img_id); }, 500);    
}

function listDynamicImg(img_id)
{
	var modalDivImgSelector = '#modalImg_'+img_id;
	
    //clear any previous modal div body for the dynamic mapping table.
    $('#fillImgTable').remove();
    $('#selectedDynamicField_' + img_id).remove();
    for (x in dynamicFields) {$('#dynamicFieldBtn_' + x).remove();}    	
    $('#selectedDynamicFieldCount_' + img_id).remove();
	
	var content = '';
	content += '<div>';
    content += '	  	<input type="hidden" id="selectedDynamicField_' + img_id + '">';
    content += '	  	<input type="hidden" id="selectedDynamicFieldCount_' + img_id + '">';
    //Loop the Dynamic Fields to setup the list for user to setup Dynamic Images.
	for (x in dynamicFields)
	{	
		content += '	<button id="dynamicFieldBtn_' + x + '" type="button" class="btn btn-default" onclick="fillImg(\'' + x + '\', \'' + img_id + '\');">' + x + '</button>';
	}
	content += '</div>';
    
    content += '<div class="modal-body" id="fillImgTable">';
    content += '	Please select a Dynamic Field First.';
    content += '</div>';

    $(modalDivImgSelector).html(content);
}

//function testme(){alert("halo");}

function fillImg(fieldId, img_id)
{
	//Set the selected dynamic field value
	$("#selectedDynamicField_" + img_id).val(fieldId);
	for (x in dynamicFields) 
	{
		if(fieldId == x) 
		{
			$("#dynamicFieldBtn_" + x).addClass("active");
		}
		else
		{
			$("#dynamicFieldBtn_" + x).attr("disabled", "disabled");
		}
	}  
	
	var modalDivImgItem = '#fillImgTable';
	var content = '';
	content += '	<table class="table table-striped">';
	content += '		<tr>';
	content += '			<th>';
	content += '				Dynamic Field Value';
	content += '			</th>';
	content += '			<th>';
	content += '				Related Dynamic Image';
	content += '			</th>';
	content += '			<th>';
	content += '				Thumbnail';
	content += '			</th>';
	content += '			<th>';
	content += '				Status<p class="text-warning">(Please upload all pictures before saving)</p>';
	content += '			</th>';
	content += '		</tr>';
	
	content += '		<tr>';
	content += '			<td>';
	content += '				Catch All Default Image';
	content += '			</td>';
	content += '			<td>';
	content += '				<form name="dynamicImg-1">';
	content += '					<input id="imgUrlHidden_' + img_id + '_-1" class="imgUrlHiddenVal" onchange="changeSuccessStatus(this.id, \'' + img_id + '\')" type="hidden" name="imgUrlHidden_' + img_id + '_-1" value="">';
	content += '					<a href="javascript:mcFileManager.open(\'dynamicImg-1\',\'imgUrlHidden_' + img_id + '_-1\', \'\', \'\', {relative_urls : true});">[Select Image]</a>';
	content += '				</form>';
	content += '			</td>';
	content += '			<td>';
	content += '				';
	content += '			</td>';
	content += '			<td>';
	content += '				Default';
	content += '			</td>';
	content += '		</tr>';
	
	var counter = 0;
	$.each(dynamicFields[fieldId] , function(i, val) { 
		counter++;
		content += '	<tr>';
		content += '		<td>';
		content += 				val;
		content += '		</td>';
		content += '		<td>';
		content += '			<form name="dynamicImg' + i + '">';
		content += '				<input id="imgUrlHidden_' + img_id + '_' + i + '" class="imgUrlHiddenVal" onchange="changeSuccessStatus(this.id, \'' + img_id + '\')" type="hidden" name="imgUrlHidden_' + img_id + '_' + i + '" value="">';
		content += '				<a href="javascript:mcFileManager.open(\'dynamicImg' + i + '\',\'imgUrlHidden_' + img_id + '_' + i + '\', \'\', \'\', {relative_urls : true});">[Select Image]</a>';
		content += '			</form>';
		content += '		</td>';
		content += '			<td>';
		content += '				';
		content += '			</td>';
		content += '		<td>';
		content += '			Default';
		content += '		</td>';
		content += '	</tr>';
	});
	content += '	</table>';
	
	$("#selectedDynamicFieldCount_" + img_id).val(counter);
	
	$(modalDivImgItem).html(content);
	
}

function changeSuccessStatus(id, img_id)
{
	//Change the selected field's row class into success.
	$("#" + id).parent().parent().parent().attr('class', 'success');
	
	//Update the Status
	$("#" + id).parent().parent().next().next().html("Success");
	
	//Setup thumbnail image
	$("#" + id).parent().parent().next().html('<img src="' + $("#" + id).val() + '" height="50" />');

	//Check whether all status changed to Success, if so enable the Save button
	var passAll = true;
    for (var i=-1; i<$("#selectedDynamicFieldCount_" + img_id).val(); i++) {
    	var imageGroupId = "#imgUrlHidden_" + img_id + "_" + i;
    	if($(imageGroupId).val() == "") {
   // 		alert(imageGroupId + " value is empty not enable...");
    		passAll = false;
    	}
    		
    }
    
    if(passAll) {
    	$("#MapDynamicImgSaveBtn_" + img_id).attr('disabled', false);
    }
}

function saveDynamicImgChanges(img_id)
{
    //Enable all the parent window buttons when closed the configuring the child Dynamic Images window.
    $("#modalCloseBtn").css('display', 'block');
    $("#modalFooterCloseBtn").attr('disabled', false);
    $("#modalFooterSaveBtn").attr('disabled', false);	


    globalDynamicFieldObject[img_id] = $.trim( $("#selectedDynamicField_" + img_id).val() );
//    globalDynamicImgObject[img_id + "##_##fieldName"] = $.trim( $("#selectedDynamicField_" + img_id).val() );
    for (var i=-1; i<$("#selectedDynamicFieldCount_" + img_id).val(); i++) {
    	var imageGroupId = "#imgUrlHidden_" + img_id + "_" + i;
    	globalDynamicImgObject[img_id + "##_##" + $.trim( $(imageGroupId).parent().parent().prev().html() )] = $.trim( $(imageGroupId).val() );
    }
    
    //Replace the original image into dummy default image
    $("#" + img_id).attr('src', globalDummyImgUrl + $("#" + img_id).width() + 'x' + $("#" + img_id).height() +
    		'/518751/2e37b8.gif&text=Dynamic+image+Area+(' + $("#" + img_id).width() + '%20x%20' + $("#" + img_id).height() +')');
}

function closeDynamicImgChanges()
{
    //Enable all the parent window buttons when closed the configuring the child Dynamic Images window.
    $("#modalCloseBtn").css('display', 'block');
    $("#modalFooterCloseBtn").attr('disabled', false);
    $("#modalFooterSaveBtn").attr('disabled', false);
    
}

function saveAllImgs(div_id, imgs_arr)
{
    var all_imgs = $('#'+div_id).data('all_imgs');
    if(!all_imgs)
        all_imgs = [];

    var new_imgs = [];

    for(var i=0; i<imgs_arr.length; i++)
    {
        var found = false;
        for(var j=0; j<all_imgs.length; j++)
        {
            if(all_imgs[j].img == imgs_arr[i])
            {
                    found = true;
                    break;
            }
        }

        for(var j=i+1; j<imgs_arr.length; j++)
        {
            if(imgs_arr[j] == imgs_arr[i])
            {
                    found = true;
                    break;
            }
        }

        if(!found)
        {
            new_imgs.push(imgs_arr[i]);
        }

    }

    var all_imgs_len = all_imgs.length;
    for(var i=0; i<new_imgs.length; i++)
    {
    	var tname= 'img'+(all_imgs_len+i);
    	all_imgs.push({'name': tname, 'img': new_imgs[i], 'id': i});
    }

    
    $('#'+div_id).data('all_imgs', all_imgs);
    
    
}

function getAllImgs(div_id)
{
    return $('#'+div_id).data('all_imgs');    
}