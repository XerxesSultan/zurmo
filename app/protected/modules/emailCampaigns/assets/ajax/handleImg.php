<?php
//	The Ajax request object sample:
// 	globalDynamicImgObject: {
// 		"img_2##_##Catch All Default Image":"../../../protected/modules/emailCampaigns/assets/images/super/36_innerImg/_2013-04-03_3.29.01.png",
// 		"img_2##_##F":"../../../protected/modules/emailCampaigns/assets/images/super/36_innerImg/_2013-04-03_3.29.57.png",
// 		"img_2##_##M":"../../../protected/modules/emailCampaigns/assets/images/super/36_innerImg/_2013-04-03_3.29.57.png",
// 		"imgUrl":"http://xcrm.shadela.com/app/protected/modules/emailCampaigns/assets/images/super/36_outImg/",
// 		"baseUrl":"http://xcrm.shadela.com/app"}
	$returnArr = Array();
	$arrIndex = 0;
	//Copy all the images into related image path
	foreach($_POST as $imgKey => $imgPath) {
		if($imgKey != 'baseUrl' && $imgKey != 'imgPath') {
			$arrIndex++;
			//Load the selected image with given URL
			$imgUrl = $_POST['baseUrl'] . substr($imgPath, strrpos($imgPath, '/protected/modules/emailCampaigns'));
			//Create the campaign image folder if not exist
			$newImg = $_POST['imgPath'] . $imgKey . '.jpg';
						
			$content = file_get_contents($imgUrl);
			$fp = fopen($newImg, "w");
			fwrite($fp, $content);
			fclose($fp);	
			$returnArr['old_url' . $arrIndex] = $imgUrl;
			$returnArr['new_path' . $arrIndex] = $newImg;
		}
	}
	echo json_encode($returnArr);
?>