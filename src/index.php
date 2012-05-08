<?php require_once('inc/init.php'); ?>
<?php
	require_once('models/evaluation.php');
	$controller = "evaluations";
	$content = "showAll";
	$page_title = "Evaluaciones disponibles";
	$entries = getEvaluations();
?>
<?php require_once("inc/body.php"); ?>