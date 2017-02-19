<?php

ini_set('display_errors', 1);
error_reporting(-1);

spl_autoload_register(function($class){
	$class = str_replace('\\', '/', $class);

	if (0 === strpos($class, 'TooBasic/Rpc/'))
		require(__DIR__ .'/library/TooBasic-Rpc/'. substr($class, strlen('TooBasic/Rpc/')) .'.php');
	else
		require(__DIR__ .'/library/'. $class .'.php');
});

BitcoindStatus::dispatch(substr($_SERVER['REQUEST_URI'], strlen('/'.__DIR__)));