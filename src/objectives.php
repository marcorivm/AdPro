<?php require_once('inc/init.php'); ?>
<?php
	require_once('models/evaluation.php');
	$controller = "objectives";
	$page_title = "Objetivos";	
	if(isset($_GET['add']) && isset($_GET['des'])) 
	{
		$eval_id = $_GET['add'];
		$obj_type = $_GET['des'];
		$entry = new evaluation($eval_id);
		$available = $entry->getNotUsedObjectives($obj_type);
		$content = "addToEval";
		$noHead = true;
		$noFooter = true;
	} 
	else if(isset($_POST['edit']))
	{
		$eval_id = $_POST['eval_id'];
		$obj_id = $_POST['obj_id'];
		$entry = new evaluation($eval_id);
		$project = new objective($obj_id);
		$content = "editEvalObjective";
		$noHead = true;
		$noFooter = true;
	}
	else if(isset($_POST['remove']))
	{
		$noShow = true;
		$obj_id = $_POST['obj_id'];
		if($_POST['remove']=="objective"){
			deleteObjective($obj_id);
		}
	}
	else if (isset($_GET['edit-objective']))
	{
		$obj_id = $_GET['edit-objective'];
		$entry = new objective($obj_id);
		$entry->evaluations = findEvaluations($entry->getId(), 1);
		$page_title = "Monstrando objetivo: ".$entry->getName();
		$content = "details";
	}
	else
	{
		$page_title = "Objetivos disponibles";
		$content = "showAll";
		$entries = getObjectives();
	}
?>
<?php require_once("inc/body.php"); ?>