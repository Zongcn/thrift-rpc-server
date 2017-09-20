<?php
$service = array(
	'User' => array(
		'processor_class' => '\\Services\\Djjl\\Service\\User\\UserServiceProcessor',
		'handler_class' => '\\Services\\Djjl\\Handler\\UserHandler',
	)
);
return $service;