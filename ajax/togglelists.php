<?php 
OCP\JSON::callCheck();
if (isset($_POST['id']) && isset($_POST['checked'])) {
	$request['id'] 			= $_POST['id'];
	$request['checked'] 	= $_POST['checked'];
	$return = OC_Newsletters_App::toggleMemberList($request);
	print json_encode($return);
}