<?php
OCP\User::checkLoggedIn();
OCP\JSON::checkAppEnabled('newsletters');

OCP\Util::addScript('newsletters','newsletters');
OC_Util::addScript( 'core', 'multiselect' );
OCP\Util::addStyle('newsletters', 'newsletters');

OCP\App::setActiveNavigationEntry( 'newsletters' );

function rearrange( $arr ){
    foreach( $arr as $key => $all ){
        foreach( $all as $i => $val ){
            $new[$i][$key] = $val;    
        }    
    }
    return $new;
}
if (isset ($_POST['new_newsletter']) && $_POST['new_newsletter'] === 'Save') {
	unset($request);
	$request = array();
	$request['subject'] = $_POST['subject'];
	if(isset ($_FILES)) {
		$files = rearrange($_FILES['story']);
		foreach ($files as $file) {
			$image = new Imagick( $file['tmp_name'] );
			$imageprops = $image->getImageGeometry();
			if ($imageprops['width'] > 200 || $imageprops['height'] > 157) $image->scaleImage(200,157, false );
			$image_encoded = base64_encode($image);
			$pathinfo = pathinfo($file['name']);
			$image_name = $pathinfo['filename'];
			
		}
	}
	$i=0; foreach($_POST['story'] as $story) { 
		$story['image'] = array('encoded' => $image_encoded, 'name' => $image_name );
		$stories[] = $story;
		$i++;
	}
	$request['content'] = json_encode($stories);
	$addNewsletter = OC_Newsletters_App::newNewsletter($request);
}

$tmpl = new OCP\Template( 'newsletters', 'newsletters', 'user' );
$tmpl->assign( 'id', $addNewsletter );
$tmpl->printPage();