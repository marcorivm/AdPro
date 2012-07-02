<h1><?php echo $entry->getName();?></h1>
<h3>Descripci&oacute;n</h3>
<p><?php echo $entry->getDescription(); ?></p>
<table id="evaluation_table" class="table table-striped table-bordered table-condensed">
	<?php 
	$projects = $entry->getProjects();
	$objectives[0] = $entry->getObjectives(0);
	$objectives[1] = $entry->getObjectives(1);
	$proj_obj = $entry->getProjectObjectives();
?>
	<thead>
		<tr>
			<th colspan="1" rowspan="2" class="centered">Proyectos <a href="/projects.php?add=<?php echo $entry->getId(); ?>" data-eval-modal_title="Agregar un proyecto en <?php echo $entry->getName();?>" class="btn btn-success btn-mini eval-add" title="Agregar proyecto"><i class="icon-plus icon-white"></i>Agregar</a></th>
			<th colspan="<?php echo count($objectives[0])?>" rowspan="1"  class="centered">Objetivos obligatorios <a href="/objectives.php?add=<?php echo $entry->getId(); ?>&des=0" data-eval-modal_title="Agregar un objetivo obligatorio en <?php echo $entry->getName();?>" class="btn btn-success btn-mini pull-right eval-add" title="Agregar objetivo obligatorio" ><i class="icon-plus icon-white"></i>Agregar</a></th>
			<th colspan="1" rowspan="2"  class="span1 centered">Aceptado</th>
			<th colspan="<?php echo count($objectives[1])?>" rowspan="1"  class="centered">Objetivos deseables <a href="/objectives.php?add=<?php echo $entry->getId(); ?>&des=1" data-eval-modal_title="Agregar un objetivo deseado en <?php echo $entry->getName();?>" class="btn btn-success btn-mini pull-right eval-add" title="Agregar objetivo deseable"><i class="icon-plus icon-white"></i>Agregar</a></th>
			<th colspan="1" rowspan="2" class="centered">Puntos totales</th>
			<th colspan="1" rowspan="2" class="centered">Acciones</th>
			<tr>
			<?php foreach($objectives[0] as $currObj){ ?>
				<th colspan="1" rowspan="1" class="span3 centered">
					<a href="/evaluations.php" title="Quitar objetivo" data-eval-obj_id="<?php echo $currObj->getId(); ?>" data-eval-eval_id="<?php echo $entry->getId(); ?>" class="btn btn-danger btn-mini remove_obj"><i class="icon-trash icon-white"></i></a>
					<a href="/objectives.php?edit-objective=<?php echo $currObj->getId();?>" title="Ver detalles del objetivo"><?php echo $currObj->getName();?></a>
				</th>
			<?php
			}
			foreach($objectives[1] as $currObj){
			?>
				<th colspan="1" rowspan="1" class="span3 centered">
					<a href="/evaluations.php" title="Quitar objetivo" data-eval-obj_id="<?php echo $currObj->getId(); ?>" data-eval-eval_id="<?php echo $entry->getId(); ?>" class="btn btn-danger btn-mini remove_obj"><i class="icon-trash icon-white"></i></a>
					<a href="/objectives.php?edit-objective=<?php echo $currObj->getId();?>" title="Ver detalles del objetivo"><?php echo $currObj->getName();?></a>
				</th>
			<?php } ?>
			</tr>
		</tr>
	</thead>
	<tbody>
		<?php foreach($projects as &$currProj){ ?>
			<tr id="proj_<?php echo $currProj->getId();?>">
				<td class="project_cell"><a href="/projects.php?edit-project=<?php echo $currProj->getId();?>" title="Ver informaci&oacute;n del proyecto"><?php echo $currProj->getName();?></a></td>
				<?php
				foreach($objectives[0] as $currObj){
					$currProj->setAcceptable($currObj->isAccepted($proj_obj[$currObj->getId()][$currProj->getId()]['score']));
				?>
				<td class="proj_<?php echo $currProj->getId();?>_<?php echo $currObj->getId();?> tip" title="<?php echo $proj_obj[$currObj->getId()][$currProj->getId()]['comments'];?>"><?php echo $currObj->getScore($proj_obj[$currObj->getId()][$currProj->getId()]['score']);?></td>
				<?php } ?>
				<td class="<?php echo ($currProj->isAcceptable())? "is_acceptable": "not_acceptable";?>"><span><?php echo ($currProj->isAcceptable())? "1": "0";?></span></td>
				<?php
				foreach($objectives[1] as $currObj){
					$currProj->addToScore($currObj->getScore($proj_obj[$currObj->getId()][$currProj->getId()]['score']));
				?>
				<td class="tip" title="<?php echo $proj_obj[$currObj->getId()][$currProj->getId()]['comments'];?>"><?php echo $currObj->getScore($proj_obj[$currObj->getId()][$currProj->getId()]['score']);?></td>
				<?php
				}
				?>
				<td class="final_score <?php echo ($currProj->isAcceptable())? 'is_acceptable': 'not_acceptable';?>"><?php echo $currProj->getTotalScore();?></td>
				<td>
					<a title="Quitar proyecto" href="/evaluations.php" data-eval-proj_id="<?php echo $currProj->getId(); ?>" data-eval-eval_id="<?php echo $entry->getId(); ?>" class="btn btn-danger btn-mini remove_proj"><i class="icon-trash icon-white"></i></a>&nbsp;
					<a title="Editar proyecto"  href="/projects.php" data-eval-modal_title="Editando <?php echo $currProj->getName().' en '.$entry->getName();?>" data-eval-proj_id="<?php echo $currProj->getId(); ?>" data-eval-eval_id="<?php echo $entry->getId(); ?>" class="btn btn-warning btn-mini edit_proj"><i class="icon-edit icon-white"></i></a>
				</td>
			</tr>
		<?php } unset($currProj);?>
	</tbody>
</table>
<div class="modal fade" id="addModal">
  <div class="modal-header">
    <button class="close" data-dismiss="modal">×</button>
    <h3></h3>
  </div>
  <div class="modal-body">
	<img src="/assets/img/ajax_loader_64.gif" align="Loading..." height="64" width="64" style="display:block; margin:0 auto 0 auto;"/>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn eval-cancel modal-cancel" data-dismiss="modal">Cancelar</a>
    <a href="#" class="btn btn-primary" id="eval-accept">Agregar</a>
  </div>
</div>
<script>
var modalHTML = '<img src="/assets/img/ajax_loader_64.gif" align="Loading..." height="64" width="64" style="display:block; margin:0 auto 0 auto;"/>';
$(".modal_cancel").on("click", function() {
	$("#addModal .modal-body").html(modalHTML);
})
$(".remove_proj").on("click", function() {
	if(confirm("Seguro que desea quitar este proyecto de esta evaluación?")){
		var obj = $(this);
		var to_send = new Array();
		to_send.push({name: 'remove', value: "project"})
		to_send.push({name: 'proj_id', value: obj.attr("data-eval-proj_id")});
		to_send.push({name: 'eval_id', value: obj.attr("data-eval-eval_id")});
		$.post(obj.attr("href"), $.param(to_send), function(data) {
			obj.parents('tr').remove();
		})
	}
	return false;
});
$(".remove_obj").on("click", function() {
	if(confirm("Seguro que desea quitar este objetivo de esta evaluaciónn?")){
		var obj = $(this);
		var to_send = new Array();
		to_send.push({name: 'remove', value: "objective"})
		to_send.push({name: 'obj_id', value: obj.attr("data-eval-obj_id")});
		to_send.push({name: 'eval_id', value: obj.attr("data-eval-eval_id")});
		$.post(obj.attr("href"), $.param(to_send), function(data) {
			location.reload();
		})
	}
	return false;
});
$(".edit_proj").on("click", function() {
	var obj = $(this);
	var to_send = new Array();
	to_send.push({name: 'edit', value: "project"})
	to_send.push({name: 'proj_id', value: obj.attr("data-eval-proj_id")});
	to_send.push({name: 'eval_id', value: obj.attr("data-eval-eval_id")});
	$("#addModal .modal-header h3").html(obj.attr("data-eval-modal_title"));
	$("#addModal").modal();
	$.post(obj.attr("href"), $.param(to_send), function(data) {
		$("#addModal .modal-body").html(data);
	})
	return false;
});
$("#eval-accept").on("click", function() {
	$("#addModal .modal-body form").submit();
})
$(".eval-add").on("click", function() {
	var obj = $(this);
	$("#addModal .modal-header h3").html(obj.attr("data-eval-modal_title"));
	$("#addModal").modal();
	$.get($(this).attr("href"), function(data){
		$("#addModal .modal-body").html(data);
	});
	
	return false;
});
$(document).ready(function() 
	{ 
		$("#evaluation_table").dataTable({
			"bPaginate": false,
			"sDom": 't',
			"bAutoWidth": false,
			"aaSorting": [ [<?php echo count($objectives[0])+1; ?>,'desc'], [<?php echo count($objectives[0])+count($objectives[1])+2; ?>,'desc'] ],
			"aoColumnDefs": [
				{ "aDataSort": [<?php echo count($objectives[0])+1; ?>, <?php echo count($objectives[0])+count($objectives[1])+2; ?>], "aTargets": [<?php echo count($objectives[0])+count($objectives[1])+2; ?>] },
				{ "aDataSort": [<?php echo count($objectives[0])+count($objectives[1])+2; ?>, <?php echo count($objectives[0])+1; ?>], "aTargets": [<?php echo count($objectives[0])+count($objectives[1])+2; ?>] }
			],

		});
	}
); 
</script>