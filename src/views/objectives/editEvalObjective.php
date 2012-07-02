<form method="post" action="/evaluations.php" name="add-project" id="add_prj_form" class="form-horizontal">
	<fieldset>
		<input type="hidden" name="proj_id" id="proj_id" value="<?php echo $project->getId()?>" />
		<legend>Objetivos obligatorios</legend>
		<?php 
		
		$proj_obj = $entry->getProjectObjectives();
		$objectives[0] = $entry->getObjectives(0);
		foreach($objectives[0] as $currObj) { ?>
		<div class="control-group">
			<label class="control-label" for="obj_val_<?php echo $currObj->getId();?>"><?php echo $currObj->getName();?></label>
			<div class="controls">
				<input type="text" class="input-xlarge obj_obl" id="obj_val_<?php echo $currObj->getId(); ?>" placeholder="0/50/100" value="<?php echo $proj_obj[$currObj->getId()][$project->getId()]['score'] ?>" />
				<input type="hidden" id="obj_id_<?php echo $currObj->getId();?>" class="obj_id" value="<?php echo $currObj->getId();?>" />
				<p class="help-block"><?php echo $currObj->getDescription()?></p>
			</div>
		</div>
		<? } ?>
	</fieldset>
	<fieldset>
		<legend>Objetivos deseables</legend>
		<?php 
		$objectives[1] = $entry->getObjectives(1);
		foreach($objectives[1] as $currObj) { ?>
		<div class="control-group">
			<label class="control-label" for="obj_val_<?php echo $currObj->getId()?>"><?php echo $currObj->getName()?></label>
			<div class="controls">
				<input type="text" class="input-xlarge obj_des" id="obj_val_<?php echo $currObj->getId()?>" placeholder="0-100" value="<?php echo $proj_obj[$currObj->getId()][$project->getId()]['score'] ?>" >
				<input type="hidden" id="obj_id_<?php echo $currObj->getId();?>" class="obj_id" value="<?php echo $currObj->getId();?>" />
				<p class="help-block"><?php echo $currObj->getDescription()?></p>
			</div>
		</div>
		<? } ?>
	</fieldset>
</form>
<script>
$("#add_prj_form").on("submit", function() {
	var isValid = true;
	var obj = $(this);
	var to_send = new Array();
	to_send.push({name: 'add', value: 'project'});
	to_send.push({name: 'proj_id', value: '<?php echo $project->getId(); ?>'})


	var obj_values = new Array(); // Array de el id y la calificación de cada objeto, sin necesidad d nada mas
	$(".obj_obl").each(function() {
		var obj_id = $(this).attr("id").substr(8);
		var obj_val = $(this).val();
		isValid = isValid && validate(this, 0);
		obj_values.push({name: obj_id, value: obj_val});
	});
	$(".obj_des").each(function() {
		var obj_id = $(this).attr("id").substr(8);
		var obj_val = $(this).val();
		isValid = isValid && validate(this, 1);
		obj_values.push({name: obj_id, value: obj_val});
	});
	to_send.push({name: 'eval_id', value: '<?php echo $entry->getId(); ?>'});
	to_send.push({name: 'obj_values', value: $.toJSON(obj_values)});
	
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
</script>