<?php 
$query = OCP\DB::prepare('SELECT * FROM *PREFIX*newsletters WHERE id = ?');
$result = $query->execute( array($_GET['id']) );
$data = $result->fetchRow();
$content = json_decode($data['content'], true);
?>
Get the latest info and news form Sontia
<?php print $data['subject']. "\n"; ?>
_________________________________________________________
In this newsletter:
<?php foreach ($content as $story) { print "* $story[heading]\n"; } ?>
_________________________________________________________
<?php foreach ($content as $story) {
	print strtoupper($story['heading']) ."\n\n";
	print $story['text']."\n";
	print "Read more at ".$story['link']."\n";
	print "______________________________\n";
} ?>
www.sontia.com

Facebook : https://www.facebook.com/SontiaLogic
Twitter : http://twitter.com/sontialogic
LinkedIn : http://www.linkedin.com/company/sontia-logic-limited

Change your email preferences or unsubscribe at www.sontia.com/mail/unsubscribe/

Copyright Sontia Logic ltd 2013
