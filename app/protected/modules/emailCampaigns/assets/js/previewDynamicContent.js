var globalImgSrcUrl = "";

function previewDynamicContent(div_id, imgSrcUrl)
{
	globalImgSrcUrl = imgSrcUrl;
    //clear any previous modal div body with the same id
    $('#modalPreview_'+div_id).remove();
    $('#modalDynamicField_'+div_id).remove();
    
    var modal_div = 
        '<div class="modal hide fade" style="width: 900px; margin-left: -450px;">' +
            '<div class="modal-header" style="background-color:#EEEEEE">' +
                '<button type="button" id="modalCloseBtn" class="close" data-dismiss="modal" aria-hidden="true">X</button>' +
                '<h3 id="myModalLabel">Preview Dynamic Content</h3>' +
            '</div>' +
            '<div id="modalDynamicField_'+div_id+'"></div>' +
            '<div class="modal-body" id="modalPreview_'+div_id+'" >' +
                
                '<div class="loading"></div>' +
            
            '</div>' +
            '<div class="modal-footer">' +
                '<button id="modalFooterCloseBtn" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>' +
            '</div>' +
        '</div>';
    
    
    $(modal_div).modal('show');
    
    setTimeout(function(){ showContent(div_id); }, 500);
 
}


function showContent(div_id)
{
    var modalDivBodySelector = '#modalPreview_'+div_id;
        
    var content = tinyMCE.get(div_id).getContent();
    $(modalDivBodySelector).html(content);
    
    var imgs_arr = [];
    
    $(modalDivBodySelector)
        .find('img')
        .each(function(index){
            //Check whether the image is dynamic or not
        	$.each($(this), function(key, element) {
        		var img = element.alt;
        		if( img != null && img.indexOf('##_##') != -1 && img.indexOf('img_') != -1)
                {
                	//Check if the field already stored or not
                	if($.inArray(img.substr(img.indexOf('##_##') + 5), imgs_arr) == -1)
                		imgs_arr.push(img.substr(img.indexOf('##_##') + 5));
                }
        	});
            
        });
    
    for (var j=0; j<imgs_arr.length; j++) {$('#dynamicFieldBtn_' + imgs_arr[j]).remove();}  
    
	var dynamicFieldContent = '';
	dynamicFieldContent += '<div>';
	//Loop the Dynamic Fields to setup the list for user to preview Dynamic Images.
	for(var j=0; j<imgs_arr.length; j++)
	{	
		dynamicFieldContent += '	<button id="dynamicFieldBtn_' + imgs_arr[j] + '" type="button" class="btn btn-default" onclick="showDynamicContent(\'' + div_id + '\', \'' + imgs_arr[j] + '\', \'\');">' + imgs_arr[j] + '</button>';
	}
	dynamicFieldContent += '</div>';

//	$(modalDivBodySelector).html(dynamicFieldContent + content);
    $('#modalDynamicField_'+div_id).html(dynamicFieldContent);
}

function showDynamicContent(div_id, fieldName, selectedVal)
{
	var marker = 0;
	var content = '';
	content += '<ul class="nav nav-pills">';
	//Check if the preview page is based on default or not
	if(selectedVal == '')
		content += '  <li class="active" onclick="showDynamicContent(\'' + div_id + '\', \'' + fieldName + '\', \'\');"><a href="#">Default Catch All</a></li>';
	else
		content += '  <li onclick="showDynamicContent(\'' + div_id + '\', \'' + fieldName + '\', \'\');"><a href="#">Default Catch All</a></li>';
	
	//Loop the values of selected Dynamic field and create tabs
	for(var j=0; j<dynamicFields[fieldName].length; j++) {
		if(selectedVal == dynamicFields[fieldName][j]) {
			marker = j + 1;
			content += '  <li class="active" onclick="showDynamicContent(\'' + div_id + '\', \'' + fieldName + '\', \'' + dynamicFields[fieldName][j] + '\');"><a href="#">' + dynamicFields[fieldName][j] + '</a></li>';
		} else
			content += '  <li onclick="showDynamicContent(\'' + div_id + '\', \'' + fieldName + '\', \'' + dynamicFields[fieldName][j] + '\');"><a href="#">' + dynamicFields[fieldName][j] + '</a></li>';
	}
	content += '</ul>';
	
    var modalDivBodySelector = '#modalPreview_'+div_id;  
    content += tinyMCE.get(div_id).getContent();
    $(modalDivBodySelector).html(content);
    $(modalDivBodySelector)
    .find('img')
    .each(function(index){

        //Check whether the image is dynamic or not
    	$.each($(this), function(key, element) {
    		var img = element.alt;
    		if( img != null && img.indexOf('##_##') != -1 && img.indexOf('img_') != -1 && img.indexOf(fieldName) != -1)
            {
    			console.log("The img been selected to be replaced is: " + img);
    			
            	//Change the image src for preview
            	if(marker == 0)
            		img = img.replaceAll(fieldName, 'Catch_All_Default_Image');
            	else
            		img = img.replaceAll(fieldName, dynamicFields[fieldName][marker-1]);
            	img = img.replace(/\#/g, '%23');
            	img = img.replace(/\./g, '_');
            	
            	console.log("The img been changed to: " + img);
            	console.log("The img src changed to: " + globalImgSrcUrl + img + '.jpg');
            	element.src = globalImgSrcUrl + img + '.jpg';
            //	$(this).attr('src', globalImgSrcUrl + img + '.jpg' );
            }
    	});
    	
        
    });
	
}

String.prototype.replaceAll  = function(s1,s2){   
	return this.replace(new RegExp(s1,"gm"),s2);   
}
