<h1>Proyectos disponibles</h1>
<table id="projects_table" class="table table-striped table-bordered table-condensed tablesorter">
<thead>
	<tr>
		<th>Nombre</th>
		<th>Descripci&oacute;n</th>
		<th>Acciones</th>
	</tr>
</thead>
<tbody>
<?php
	foreach($entries as $currProj){
?>
<tr>
	<td><a href="/projects.php?edit-project=<?php echo $currProj->getId();?>"><?php echo $currProj->getName();?></a></td>
	<td><?php echo $currProj->getDescription();?></td>
	<td >
		<a title="Borrar proyecto" href="/projects.php" data-eval-proj_id="<?php echo $currProj->getId();?>" class="btn btn-danger btn-mini remove_proj"><i class="icon-trash icon-white"></i></a>&nbsp;
		<!--<a title="Editar proyecto"  href="/projects.php?edit-project=<?php echo $currProj->getId();?>" data-eval-eval_id="<?php echo $currProj->getId();?>" class="btn btn-warning btn-mini edit_proj"><i class="icon-edit icon-white"></i></a>-->
	</td>
</tr>
<?php
	}
?>
</tbody>
</table>
<script>
$(".remove_proj").on("click", function() {
	if(confirm("¿Seguro que desea eliminar este proyecto?\n¡Todos los datos relacionados se perderán!")){
		var obj = $(this);
		var to_send = new Array();
		to_send.push({name: 'remove', value: "project"})
		to_send.push({name: 'proj_id', value: obj.attr("data-eval-proj_id")});
		$.post(obj.attr("href"), $.param(to_send), function(data) {
			obj.parents('tr').remove();
		})
	}
	return false;
});
$(document).ready(function() 
    {
		$("#projects_table").dataTable({
			"bPaginate": false,
			"bAutoWidth": false,
		});
    } 
); 
</script>