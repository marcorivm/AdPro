<h1>Objetivos disponibles</h1>

<h2>Objetivos obligatorios</h2>
<table id="objectives_obl_table" class="table table-striped table-bordered table-condensed tablesorter">
<thead>
	<tr>
		<th>Nombre</th>
		<th>Descripci&oacute;n</th>
		<th>Acciones</th>
	</tr>
</thead>
<tbody>
<?php
	foreach($entries as $currObj){
		if($currObj->getType() == 0){
?>
<tr>
	<td><a href="/objectives.php?edit-objective=<?php echo $currObj->getId();?>"><?php echo $currObj->getName();?></a></td>
    <td><?php echo $currObj->getDescription();?></td>
	<td >
		<a title="Borrar objetivo" href="/objectives.php" data-eval-proj_id="<?php echo $currObj->getId();?>" class="btn btn-danger btn-mini remove_obj"><i class="icon-trash icon-white"></i></a>&nbsp;
		<!--<a title="Editar proyecto"  href="/projects.php?edit-project=<?php echo $currObj->getId();?>" data-eval-eval_id="<?php echo $currObj->getId();?>" class="btn btn-warning btn-mini edit_obj"><i class="icon-edit icon-white"></i></a>-->
	</td>
</tr>
<?php
		}
	}
?>
</tbody>
</table>
<h2>Objetivos deseables</h2>
<table id="objectives_des_table" class="table table-striped table-bordered table-condensed tablesorter">
<thead>
	<tr>
		<th>Nombre</th>
		<th>Descripci&oacute;n</th>
		<th>Importancia</th>
		<th>Acciones</th>
	</tr>
</thead>
<tbody>
<?php
	foreach($entries as $currObj){
		if($currObj->getType() == 1){
?>
<tr>
	<td><a href="/objectives.php?edit-objective=<?php echo $currObj->getId();?>"><?php echo $currObj->getName();?></a></td>
    <td><?php echo $currObj->getDescription();?></td>
	<td><?php echo $currObj->getImportance();?></td>
	<td >
		<a title="Borrar objetivo" href="/objectives.php" data-eval-proj_id="<?php echo $currObj->getId();?>" class="btn btn-danger btn-mini remove_obj"><i class="icon-trash icon-white"></i></a>&nbsp;
		<!--<a title="Editar proyecto"  href="/projects.php?edit-project=<?php echo $currObj->getId();?>" data-eval-eval_id="<?php echo $currObj->getId();?>" class="btn btn-warning btn-mini edit_obj"><i class="icon-edit icon-white"></i></a>-->
	</td>
</tr>
<?php
		}
	}
?>
</tbody>
</table>
<script>
$(".remove_obj").on("click", function() {
	if(confirm("¿Seguro que desea eliminar este objetivo?\n¡Todos los datos relacionados se perderán!")){
		var obj = $(this);
		var to_send = new Array();
		to_send.push({name: 'remove', value: "objective"})
		to_send.push({name: 'obj_id', value: obj.attr("data-eval-obj_id")});
		$.post(obj.attr("href"), $.param(to_send), function(data) {
			obj.parents('tr').remove();
		})
	}
	return false;
});
$(document).ready(function() 
    {
		$("#objectives_obl_table").dataTable({
			"bPaginate": false,
			"bAutoWidth": false,
		});
		$("#objectives_des_table").dataTable({
			"bPaginate": false,
			"bAutoWidth": false,
			"aaSorting": [[2]],
		});
    } 
); 
</script>