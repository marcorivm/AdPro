<form method="post" action="/evaluations.php" name="add-project" id="add_prj_form" class="form-horizontal">
	<fieldset>
		<div class="control-group">
			<label class="control-label" for="proj_id">Proyecto</label>
			<div class="controls">
				<select id="proj_id" class="select-xlarge">
						<option value="no-opt" selected="selected">Seleccione un proyecto para continuar</option>
						<option value="new-project">Agregar nuevo proyecto</option>
					<?php foreach($available as $currProj) { ?>
						<option value="<?php echo $currProj->getId(); ?>"><?php echo $currProj->getName(); ?></option>
					<?php } ?>
				</select>
				<input type="hidden" name="proj_user_id" id="proj_user_id" value="0" class="proj_data" />
				<p class="help-block">Selecciona un proyecto</p>
			</div>
		</div>
		<div class="control-group new_project_data new_proj_container no-show">
			<label class="control-label" for="proj_name">Nombre del proyecto</label>
			<div class="controls">
				<input type="text" name="proj_name" id="proj_name" class="proj_data" />
				<p class="help-block"></p>
			</div>
		</div>
		<div class="control-group new_project_data new_proj_container no-show">
			<label class="control-label" for="proj_description">Descripci&oacute;n</label>
			<div class="controls">
				<textarea name="proj_description" id="proj_description" class="proj_data" ></textarea>
				<p class="help-block"></p>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>Objetivos obligatorios</legend>
		<?php 
		$objectives[0] = $entry->getObjectives(0);
		foreach($objectives[0] as $currObj) { ?>
		<div class="control-group">
			<label class="control-label" for="obj_val_<?php echo $currObj->getId();?>"><?php echo $currObj->getName();?></label>
			<div class="controls">
				<input type="text" class="input-xlarge obj_obl" id="obj_val_<?php echo $currObj->getId(); ?>" placeholder="0/50/100" />
				<input type="hidden" id="obj_id_<?php echo $currObj->getId();?>" class="obj_id" value="<?php echo $currObj->getId();?>" />
				<p class="help-block"><?php echo $currObj->getDescription()?></p>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="obj_comm_<?php echo $currObj->getId();?>">Comentarios</label>
			<div class="controls">
				<textarea class="obj_comment" id="obj_comm_<?php echo $currObj->getId();?>" placeholder="Comentarios sobre el proyecto"></textarea>
				<p class="help-block">Comentarios adicionales sobre el proyecto y el objetivo</p>
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
				<input type="text" class="input-xlarge obj_des" id="obj_val_<?php echo $currObj->getId()?>" placeholder="0-100">
				<input type="hidden" id="obj_id_<?php echo $currObj->getId();?>" class="obj_id" value="<?php echo $currObj->getId();?>" />
				<p class="help-block"><?php echo $currObj->getDescription()?></p>
			</div>
		</div>		
		<div class="control-group">
			<label class="control-label" for="obj_comm_<?php echo $currObj->getId();?>">Comentarios</label>
			<div class="controls">
				<textarea class="obj_comment" id="obj_comm_<?php echo $currObj->getId();?>" placeholder="Comentarios sobre el proyecto"></textarea>
				<p class="help-block">Comentarios adicionales sobre el proyecto y el objetivo</p>
			</div>
		</div>
		<? } ?>
	</fieldset>
</form>
<script>
$("#proj_id").on("change", function() {
	var obj = $(this);
	var new_proj = $(".new_proj_container");
	if(obj.val() == "new-project") {
		if(new_proj.hasClass('no-show')) {
			new_proj.slideDown('fast', function() {
				$(this).removeClass('no-show');
			})
		}
	} else {
		if(!new_proj.hasClass('no-show')) {
			new_proj.slideUp('fast', function() {
				$(this).addClass('no-show');
			})
		}
	}
})
$("#add_prj_form").on("submit", function() {
	var isValid = true;
	var obj = $(this);
	var to_send = new Array();
	to_send.push({name: 'add', value: 'project'});
	var proj_id = $("#proj_id").val();
	to_send.push({name: 'proj_id', value: proj_id})
	isValid = isValid && validate($("#proj_id"), 2);
	if($("#proj_id").val() == "new-project") {
		isValid = isValid && validate($("#proj_name"), 3)
		isValid = isValid && validate($("#proj_description"), 3);
	}
	var proj_data = $(".proj_data").serializeArray();
	var obj_values = new Array(); // Array de el id y la calificación de cada objeto, sin necesidad d nada mas
	$(".obj_obl").each(function() {
		var obj_id = $(this).attr("id").substr(8);
		var obj_val = $(this).val();
		var obj_comment = Validator.getRaw($(this).parents('.control-group').next('.control-group').find('.obj_comment'));
		isValid = isValid && validate(this, 0);
		obj_values.push({name: obj_id, value: obj_val, comment: obj_comment});
	});
	$(".obj_des").each(function() {
		var obj_id = $(this).attr("id").substr(8);
		var obj_val = $(this).val();
		isValid = isValid && validate(this, 1);
		var obj_comment = Validator.getRaw($(this).parents('.control-group').next('.control-group').find('.obj_comment'));
		obj_values.push({name: obj_id, value: obj_val, comment: obj_comment});
	});
	to_send.push({name: 'eval_id', value: '<?php echo $entry->getId(); ?>'});
	to_send.push({name: 'proj_data', value: $.toJSON(proj_data)});
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