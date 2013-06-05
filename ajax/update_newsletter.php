<?php

OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('newsletters');
OCP\JSON::callCheck();
function rearrange( $arr ){
    foreach( $arr as $key => $all ){
        foreach( $all as $i => $val ){
            $new[$i][$key] = $val;    
        }    
    }
    return $new;
}
$data = array();
if (isset ($_FILES)) {
	$files = rearrange($_FILES['story']);
	foreach ($files as $story => $file) {
		$image = new Imagick( $file['tmp_name'] );
		$imageprops = $image->getImageGeometry();
		if ($imageprops['width'] > 200 || $imageprops['height'] > 157) $image->scaleImage(200,157, false );
		$image_encoded = base64_encode($image);
		$pathinfo = pathinfo($file['name']);
		$image_name = $pathinfo['filename'];
		$data = array('story' => array($story => array('image'=> array('name'=>$image_name,'encoded'=>$image_encoded ))));
	}
}
if ( isset ( $_POST['id'] ) ) {
	$data = array_merge($_POST, $data);
	$update = OC_Newsletters_App::updateNewsletter($data);
	$update = array_merge($update, $data);
	print json_encode($update);
	
}