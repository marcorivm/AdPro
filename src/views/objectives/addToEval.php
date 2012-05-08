<?php $obj_type_name = ($obj_type == 0)? "obligatorio" : "deseable"; ?>
<form method="post" action="/evaluations.php" name="add-objective" id="add_obj_form" class="form-horizontal">
	<fieldset>
		<input type="hidden" name="obj_user_id" id="obj_user_id" value="0" class="obj_data" />
		<div class="control-group">
			<label class="control-label" for="obj_id">Objetivo</label>
			<div class="controls">
				<select id="obj_id" class="select-xlarge">
						<option value="no-opt" selected="selected">Seleccione un objetivo para continuar</option>
						<option value="new-objective">Agregar nuevo objetivo <?php echo $obj_type_name ?></option>
					<?php foreach($available as $currObj) { ?>
						<option value="<?php echo $currObj->getId(); ?>"  <?php echo ($obj_type == 1)? 'data-obj-importance="'.$currObj->getImportance().'"' : ''; ?> data-obj-description=""<?php echo $currObj->getDescription(); ?>""><?php echo $currObj->getName(); ?></option>
					<?php } ?>
				</select>
				<p class="help-block">Selecciona un objetivo <?php echo $obj_type_name ?></p>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>Datos del objetivo <?php echo $obj_type_name; ?></legend>
		<div class="control-group new_objective_data new_obj_container no-show">
			<label class="control-label" for="obj_name">Nombre del objetivo <?php echo $obj_type_name ?></label>
			<div class="controls">
				<input type="text" name="obj_name" id="obj_name" class="obj_data" />
				<p class="help-block"></p>
			</div>
		</div>
		<?php if($obj_type == 1) { ?>
		<div class="control-group new_objective_data new_obj_container no-show">
			<label class="control-label" for="obj_importance">Importancia</label>
			<div class="controls">
				<input type="text" name="obj_importance" id="obj_importance" class="obj_data"  placeholder="0-100"/>
				<p class="help-block"></p>
			</div>
		</div>
		<?php } else { ?>
				<input type="hidden" name="obj_importance" id="obj_importance" class="obj_data" value="-1"/>
		<?php } ?>
		<div class="control-group new_objective_data new_obj_container no-show">
			<label class="control-label" for="obj_description">Descripci&oacute;n</label>
			<div class="controls">
				<textarea name="obj_description" id="obj_description" class="obj_data" ></textarea>
				<p class="help-block"></p>
			</div>
		</div>
	</fieldset>
</form>
<script>
$("#obj_id").on("change", function() {
	var obj = $(this);
	var new_proj = $(".new_obj_container");
	var obj_name = $("#obj_name");
	var obj_description = $("#obj_description");
	var obj_importance = $("#obj_importance");
	if(obj.val() == "new-objective") {
		obj_name.val("").prop("readonly", "");
		obj_description.val("").prop("readonly", "");
		<?php if($obj_type == 1) { ?>
			obj_importance.val("").prop("readonly", "");
		<?php } else { ?>
			obj_importance.val("-1");
		<?php } ?>
		if(new_proj.hasClass('no-show')) {
			new_proj.slideDown('fast', function() {
				$(this).removeClass('no-show');
			})
		}
	} else {
		var curr_opt = $('#obj_id option[value="'+obj.val()+'"]')
		obj_name.val(curr_opt.text()).prop("readonly", "readonly");
		obj_description.val(curr_opt.attr("data-obj-description")).prop("readonly", "readonly");
		<?php if($obj_type == 1) { ?>
			obj_importance.val(curr_opt.attr("data-obj-importance")).prop("readonly", "readonly");
		<?php } else { ?>
			obj_importance.val("-1");
		<?php } ?>
		if(new_proj.hasClass('no-show')) {
			new_proj.slideDown('fast', function() {
				$(this).removeClass('no-show');
			})
		}
	}
})
$("#add_obj_form").on("submit", function() {
	var isValid = true;
	var obj = $(this);
	var to_send = new Array();
	to_send.push({name: 'add', value: 'objective'});
	var obj_id = $("#obj_id").val();
	to_send.push({name: 'obj_id', value: obj_id})
	isValid = isValid && validate($("#obj_id"), 2);
	if($("#obj_id").val() == "new-objective") {
		isValid = isValid && validate($("#obj_name"), 3)
		isValid = isValid && validate($("#obj_description"), 3);
		<?php if($obj_type == 1) { ?>
			isValid = isValid && validate($("#obj_importance"),1);
		<?php } ?>
	}
	var obj_data = $(".obj_data").serializeArray();
	
	to_send.push({name: 'eval_id', value: '<?php echo $entry->getId(); ?>'});
	to_send.push({name: 'obj_data', value: $.toJSON(obj_data)});
	
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
			isValid = isValid && (raw_value == "new-objective" || Validator.isNumeric(raw_value));
			if(!isValid){
				error_msg = "Debes seleccionar un objetivo valido!"; 
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