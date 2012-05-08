<h1><?php echo $entry->getName();?></h1>
<h3>Descripción:</h3>
<p><?php echo $entry->getDescription();?></p>
<h3>Evaluaciones</h3>
<table id="evaluations_table" class="table table-striped table-bordered table-condensed tablesorter">
<thead>
	<tr>
		<th>Nombre</th>
		<th>Aceptado</th>
		<th>Score</th>
	</tr>
</thead>
<tbody>
<?php
	foreach($entry->evaluations as $currEval){
		$entry->resetValues();
		$objectives[0] = $currEval->getObjectives(0);
		$objectives[1] = $currEval->getObjectives(1);
		$proj_obj = $currEval->getProjectObjectives();
		foreach($objectives[0] as $currObj){
			$entry->setAcceptable($currObj->isAccepted($proj_obj[$currObj->getId()][$entry->getId()]));
		}
		foreach($objectives[1] as $currObj){
			$entry->addToScore($currObj->getScore($proj_obj[$currObj->getId()][$entry->getId()]));
		}
?>
<tr>
	<td><a href="/evaluations.php?edit=<?php echo $currEval->getId();?>"><?php echo $currEval->getName();?></a></td>
	<td class="<?php echo ($entry->isAcceptable())? "is_acceptable": "not_acceptable";?>"><span><?php echo ($entry->isAcceptable())? "1": "0";?></span></td>
	<td class="final_score <?php echo ($entry->isAcceptable())? 'is_acceptable': 'not_acceptable';?>"><?php echo $entry->getTotalScore();?></td>
</tr>
<?php
	}
?>
</tbody>
</table>
<script>
$(document).ready(function() 
	{
		$("#evaluations_table").dataTable({
			"bPaginate": false,
			"bAutoWidth": false,
			"aaSorting": [ [1], [2] ],
			"aoColumnDefs": [
				{ "aDataSort": [1, 2], "aTargets": [2] },
				{ "aDataSort": [2, 1], "aTargets": [2] }
			],
		});
	} 
);
</script>