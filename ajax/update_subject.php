<?php

OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('newsletters');
OCP\JSON::callCheck();


if ( isset ( $_POST['subject'] ) && $_POST['subject'] !== '' && isset ( $_POST['id'] ) ) {

	$new_subject['subject'] = $_POST['subject'];
	$new_subject['id']		= $_POST['id'];


	$subject = OC_Newsletters_App::updateSubject($new_subject);
	
	print json_encode($subject);
	
}