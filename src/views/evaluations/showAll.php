<h1>Evaluaciones disponibles</h1>
<p><a href="/evaluations.php" class="btn btn-success btn-large eval-add" title="Agregar proyecto"><i class="icon-plus icon-white"></i>Agregar nueva evaluaci&oacute;n</a></p>
<table id="evaluations_table" class="table table-striped table-bordered table-condensed tablesorter">
<thead>
	<tr>
		<th>Nombre</th>
		<th>Descripci&oacute;n</th>
		<th>Ultima modificaci&oacute;n</th>
		<th>Acciones</th>
	</tr>
</thead>
<tbody>
<?php
	foreach($entries as $currEval){
?>
<tr>
	<td><a href="/evaluations.php?edit=<?php echo $currEval->getId();?>"><?php echo $currEval->getName();?></a></td>
	<td><?php echo $currEval->getShortDescription(); ?></td>
	<td><?php echo $currEval->getModified();?></td>
	<td>
		<a title="Borrar evaluaci&oacute;n" href="/evaluations.php" data-eval-eval_id="<?php echo $currEval->getId();?>" class="btn btn-danger btn-mini remove_eval"><i class="icon-trash icon-white"></i></a>&nbsp;
		<a title="Editar evaluaci&oacute;n"  href="/evaluations.php?edit=<?php echo $currEval->getId();?>" data-eval-eval_id="<?php echo $currEval->getId();?>" class="btn btn-warning btn-mini edit_eval"><i class="icon-edit icon-white"></i></a>
	</td>
</tr>
<?php
	}
?>
</tbody>
</table>
<div class="modal fade" id="addModal">
	<div class="modal-header">
		<button class="close" data-dismiss="modal">×</button>
		<h3>Aregar nueva evaluaci&oacute;n</h3>
	</div>
	<div class="modal-body">
		<form method="post" action="/evaluations.php" name="add-evaluation" id="add_eval_form" class="form-horizontal">
			<fieldset>
				<div class="control-group" style="display:none;">
					<div class="controls">
						<input type="hidden" name="user_id" id="user_id" class="eval_data" value="0"/>
						<p class="help-block"></p>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="eval_name">Nombre de la evaluaci&oacute;n</label>
					<div class="controls">
						<input type="text" name="eval_name" id="eval_name" class="eval_data" />
						<p class="help-block"></p>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="eval_description">Descripci&oacute;n</label>
					<div class="controls">
						<textarea name="eval_description" id="eval_description" class="eval_data" ></textarea>
						<p class="help-block"></p>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn eval-cancel modal-cancel" data-dismiss="modal">Cancelar</a>
		<a href="#" class="btn btn-primary" id="eval-accept">Agregar</a>
	</div>
</div>
<script>
$(".remove_eval").on("click", function() {
	if(confirm("¿Seguro que desea eliminar esta evaluación?\n¡Todos los datos se perderán!")){
		var obj = $(this);
		var to_send = new Array();
		to_send.push({name: 'remove', value: "evaluation"})
		to_send.push({name: 'eval_id', value: obj.attr("data-eval-eval_id")});
		$.post(obj.attr("href"), $.param(to_send), function(data) {
			obj.parents('tr').remove();
		})
	}
	return false;
});
$("#eval-accept").on("click", function() {
	$("#addModal .modal-body form").submit();
})
$(".eval-add").on("click", function() {
	$("#addModal").modal();
	return false;
});
$("#add_eval_form").on("submit", function() {
	var isValid = true;
	var obj = $(this);
	var to_send = new Array();
	to_send.push({name: 'add', value: 'evaluation'});
	var eval_data = $(".eval_data").each(function(){
		isValid = isValid && validate(this, 3);
	}).serializeArray();	
	to_send.push({name: 'eval_data', value: $.toJSON(eval_data)});
	
	if(isValid){
		$.post(obj.attr("action"),to_send, function(data){
			location.reload();
		});
	}
	return false;
})
function validate (obj, type) {
	var isValid = true;
	var raw_value = Validator.getRaw(obj);
	obj = $(obj);
	var control_group = obj.parents(".control-group");
	var help_block = control_group.find('.help-block');
	if(Validator.isEmpty(help_block.get(0).original_msg)) {
		help_block.get(0).original_msg = help_block.html();
	}
	var error_msg = help_block.get(0).original_msg;
	switch(type) {
		case 0:
			isValid = isValid && Validator.isNumeric(raw_value) && (raw_value == 0 || raw_value == 50 || raw_value == 100);
			if(!isValid){
				error_msg = "Debe ser 0 si <b>no</b> cumple, 50 si no es aplicable y 100 si <b>sí</b> cumple"; 
			}
			break;
		case 1:
			isValid = isValid && Validator.isNumeric(raw_value) && (raw_value >= 0 && raw_value <= 100);
			if(!isValid){
				error_msg = "El valor debe ser un número entre el 0 y el 100"; 
			}
			break;
		case 2:
			isValid = isValid && (raw_value == "new-project" || Validator.isNumeric(raw_value));
			if(!isValid){
				error_msg = "Debes seleccionar un proyecto valido!"; 
			}
			break;
		case 3:
			isValid = isValid && !Validator.isRawEmpty(raw_value);
			if(!isValid){
				error_msg = "No debe estar vacio!"; 
			}
			break;
	}
	control_group.removeClass('error, success');
	control_group.addClass((isValid)? 'success': 'error');
	help_block.html(error_msg);
	return isValid
}
$(document).ready(function() 
	{
		$("#evaluations_table").dataTable({
			"bPaginate": false,
			"bAutoWidth": false,
			"aaSorting": [ [1], [0] ],
		});
	} 
); 
</script>