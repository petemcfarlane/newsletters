<?php 
OCP\JSON::callCheck();
if ( isset($_POST['newsletter_id']) ) {
	$return = OC_Newsletters_App::queue_mail($_POST['newsletter_id']);
	print json_encode($return);
}