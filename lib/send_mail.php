<?php 
require_once('class.phpmailer.php');
require_once('class.smtp.php');

mysql_connect('localhost','newsletterreader','4G3dPHtsPxV8nJj2');
mysql_select_db('owncloud');
$result = mysql_query('SELECT id, email, newsletter, status FROM oc_newsletters_sending WHERE status = 0 ORDER BY queued DESC LIMIT 10 ');
while ($data = mysql_fetch_assoc($result) ) {
	$unsents[] = $data;
}

if ($unsents) {
	foreach($unsents as $unsent) {
		$result = mysql_query("SELECT subject FROM oc_newsletters WHERE id = $unsent[newsletter]");
		$newsletter = mysql_fetch_assoc($result) or die(mysql_error());

		$mail = new PHPMailer();
		$mail->IsSMTP();  // telling the class to use SMTP
		$mail->Host     = "auth.smtp.1and1.co.uk"; // SMTP server
		$mail->SMTPAuth = true;
		$mail->Username = "petemcfarlane@sontia.com";
		$mail->Password = "kRdt2MHC";
		$mail->From     = 'no-reply@sontia.com';
		$mail->FromName = 'Sontia News';
		$mail->AddAddress($unsent['email']);
		$mail->Subject  = $newsletter['subject'];
		$mail->Body     = file_get_contents("https://owncloud.sontia.com/newsletter.php?id=$unsent[newsletter]&view=html");
		$mail->AltBody	= file_get_contents("https://owncloud.sontia.com/newsletter.php?id=$unsent[newsletter]&view=text");

		if( !$mail->Send() ) {
			//print 'error';
		} else {
			$query 	= OC_DB::prepare("UPDATE *PREFIX*newsletters_sending SET status = 1 WHERE id = ? ");
			$query->execute( array($unsent['id']) );
			//print 'success';
		}
	}
} else {
	//echo 'none';
}
?>