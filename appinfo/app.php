<?php
OCP\App::registerAdmin( 'newsletters', 'settings' );
OC::$CLASSPATH['OC_Newsletters_App'] = 'apps/newsletters/lib/app.php';

OCP\App::addNavigationEntry( array( 
	'id' => 'newsletters',
	'order' => 74,
	'href' => OCP\Util::linkTo( 'newsletters', 'index.php' ),
	'icon' => OCP\Util::imagePath( 'newsletters', 'newsletters.svg' ),
	'name' => 'Newsletters'
));