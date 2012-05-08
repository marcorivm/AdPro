<?php require_once('inc/init.php'); ?>
<?php
	require_once('models/evaluation.php');
	$controller = "evaluations";
	$page_title = "Evaluaciones";
	if(isset($_GET['edit'])) 
	{
		$entry = new evaluation($_GET['edit']);
		$entry->update();
		$content = "edit";
		$bodyFluid = true;
		$page_title = $entry->getName();
	} 
	else if(isset($_POST['add'])) 
	{
		$noShow = true;
		if($_POST['add']=="project"){
			$eval_id = $_POST['eval_id'];
			if(is_numeric($_POST['proj_id'])){
				$proj_id = $_POST['proj_id'];
			} else if($_POST['proj_id'] == "new-project") {
				$proj_id = newProject(objectToArray(json_decode($_POST['proj_data'])));
			} else { die("Error fatal!"); }
			$obj_values = objectToArray(json_decode($_POST['obj_values']));
			addProject($proj_id, $eval_id, $obj_values);
		} else if ($_POST['add']=="objective") {
			$eval_id = $_POST['eval_id'];
			if(is_numeric($_POST['obj_id'])){
				$obj_id = $_POST['obj_id'];
			} else if($_POST['obj_id'] == "new-objective") {
				$obj_id = newObjective(objectToArray(json_decode($_POST['obj_data'])));
			} else { die("Error fatal!"); }
			$newObj = new objective($obj_id);
			addObjective($newObj, $eval_id);
		} else if ($_POST['add']=="evaluation")  {
			$eval_id = newEvaluation(objectToArray(json_decode($_POST['eval_data'])));
		}
		if(isset($eval_id)){
			$entry = new evaluation($eval_id);
			$entry->update();
		}
	}
	else if(isset($_POST['remove'])) 
	{
		$noShow = true;
		$eval_id = $_POST['eval_id'];
		$entry = new evaluation($eval_id);
		$entry->update();
		if($_POST['remove']=="project"){
			$proj_id = $_POST['proj_id'];
			removeProject($proj_id, $eval_id);
		} else if($_POST['remove']=="objective"){
			$obj_id = $_POST['obj_id'];
			removeObjective($obj_id, $eval_id);
		} else if($_POST['remove']=="evaluation"){
			deleteEvaluation($eval_id);
		}
		
	}
	else 
	{
		$content = "showAll";
		$page_title = "Evaluaciones disponibles";
		$entries = getEvaluations();
	}
?>
<?php require_once("inc/body.php"); ?>