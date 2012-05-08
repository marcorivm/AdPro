<?php require_once('inc/init.php'); ?>
<?php
	require_once('models/evaluation.php');
	$controller = "projects";
	$page_title = "Proyectos";
	if(isset($_GET['add'])) 
	{
		$eval_id = $_GET['add'];
		$entry = new evaluation($eval_id);
		$available = $entry->getNotUsedProjects();
		$content = "addToEval";
		$noHead = true;
		$noFooter = true;
	} 
	else if(isset($_POST['edit']))
	{
		$eval_id = $_POST['eval_id'];
		$proj_id = $_POST['proj_id'];
		$entry = new evaluation($eval_id);
		$project = new project($proj_id);
		$content = "editEvalProject";
		$noHead = true;
		$noFooter = true;
	} 
	else if(isset($_POST['remove']))
	{
		$noShow = true;
		$proj_id = $_POST['proj_id'];
		if($_POST['remove']=="project"){
			deleteProject($proj_id);
		}
	}
	else if (isset($_GET['edit-project']))
	{
		$proj_id = $_GET['edit-project'];
		$entry = new project($proj_id);
		$entry->evaluations = findEvaluations($entry->getId());
		$page_title = "Monstrando proyecto: ".$entry->getName();
		$content = "details";
	}
	else 
	{
		$page_title = "Proyectos disponibles";
		$content = "showAll";
		$entries = getProjects();
	}
?>
<?php require_once("inc/body.php"); ?>