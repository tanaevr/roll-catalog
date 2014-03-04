<?php
$output = '';
	
	$config = array(
		'parent' => $parent,
	);
	if(!defined('VIEWPRODUCT_PATH')) define('VIEWPRODUCT_PATH', MODX_CORE_PATH."components/viewproduct/");
	
	require_once VIEWPRODUCT_PATH."viewproduct.class.php";
	$viewProduct = new viewProduct($modx, $scriptProperties);
	
	return $viewProduct->getList($config['parent']);