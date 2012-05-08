<h1><?php echo $entry->getName();?></h1>
<h3>Descripción:</h3>
<p><?php echo $entry->getDescription();?></p>
<h3>Tipo:</h3>
<p><?php echo ($entry->getType() == 0)? "Obligatorio" : "Deseable" ?></p>
<h3>Evaluaciones</h3>
<table id="evaluations_table" class="table table-striped table-bordered table-condensed tablesorter">
<thead>
	<tr>
		<th>Nombre</th>
	</tr>
</thead>
<tbody>
<?php
	foreach($entry->evaluations as $currEval){
?>
<tr>
	<td><a href="/evaluations.php?edit=<?php echo $currEval->getId();?>"><?php echo $currEval->getName();?></a></td>
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
		});
	} 
);
</script>