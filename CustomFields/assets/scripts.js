jQuery(function($) {

	$('.apf-field:not(.apf-field_children)').
			find('input, select').
			each(function() {
				PKROptionsControl.showChildrens(PKROptionsControl.getId(this), PKROptionsControl.getVal(this), this);
			});

	$('.apf-field:not(.apf-field_children) select, .apf-field:not(.apf-field_children) input').
			change(function() {
				PKROptionsControl.hideChildrens(PKROptionsControl.getId(this), this);
				PKROptionsControl.showChildrens(PKROptionsControl.getId(this), PKROptionsControl.getVal(this), this);
			});

});

let APFSubPages = {
	menuClick: function(e){
		let menu = e.closest('.apf-fields__menu');
		let wrapper = menu.closest('.apf-wrapper');
		menu.querySelectorAll('.apf-fields__menu-button').forEach(function(button){
			button.classList.remove('active');
		});
		wrapper.querySelector('.apf-fields__subpages').querySelectorAll(':scope > .apf-fields__subpage').forEach(function(subpage){
			subpage.classList.remove('active');
		});
		e.classList.add('active');
		wrapper.querySelector('.apf-fields__subpage[data-id="'+e.dataset.id+'"]').classList.add('active');
	}
};

var PKROptionsControl = {
	getVal: function(e){
		if(['checkbox'].includes(jQuery(e).attr('type'))){
			let values = [];
			jQuery("input[data-id="+this.getId(e)+"]:checked").each(function(){
				values.push(jQuery(this).val());
			});
			return values;
		}
		return jQuery(e).val();
	},
	getId: function(e) {
		if(['checkbox'].includes(jQuery(e).attr('type'))){
			return jQuery(e).data('id');
		}
		return ['radio'].includes(jQuery(e).attr('type')) && jQuery(e).is(':checked')
				? jQuery(e).data('id')
				: jQuery(e).attr('id');
	},
	showChildrens: function(parentId, parentValue, e) {

		if (parentValue === '') {
			return false;
		}

		var childrens = jQuery('[data-parent-' + parentId + ']');

		if (!childrens.length) {
			return false;
		}

		if(['checkbox'].includes(jQuery(e).attr('type'))){

			childrens.each(function(index, element) {

				var dataVal = jQuery(element).data('parent-' + parentId);

				parentValue.forEach(function(value){
					if (dataVal && (dataVal === value || dataVal.indexOf(value) + 1)) {
						jQuery(element).show();
						jQuery(element).find('input, textarea, select').attr('disabled', false);
					}
				});

			});
		}else{
			childrens.each(function(index, element) {

				var dataVal = jQuery(element).data('parent-' + parentId) + '';

				if (dataVal && (dataVal === parentValue || dataVal.indexOf(parentValue) + 1)) {
					jQuery(element).show();
					jQuery(element).find('input, textarea, select').attr('disabled', false);
				}else{
					jQuery(element).hide();
					jQuery(element).find('input, textarea, select').attr('disabled', true);
				}
			});
		}
	},
	hideChildrens: function(parentId, e) {
		var childrenBox = jQuery('[data-parent-' + parentId + ']');
		childrenBox.find('input, textarea, select').attr('disabled', true);
		childrenBox.hide();
	},

};

function apf_init_color(id, props) {
	jQuery("#" + id).wpColorPicker(props);
}

function apf_init_slider(props) {

	var box = jQuery('#apf-slider-' + props.id);

	box.children('.apf-slider__box').slider({
		value: parseInt(props.value),
		min: parseInt(props.min),
		max: parseInt(props.max),
		step: parseInt(props.step),
		create: function(event, ui) {
			var value = box.children('.apf-slider__box').slider('value');
			box.children('.apf-slider__value').text(value);
			box.children('.apf-slider__field').val(value);
		},
		slide: function(event, ui) {
			box.find('.apf-slider__value').text(ui.value);
			box.find('.apf-slider__field').val(ui.value);
		},
	});
}

function apf_add_dynamic_field(e) {
	var parent = jQuery(e).parents('.multi-text__value');
	var box = parent.parent('.multi-text');
	var html = parent.html();
	box.append('<span class="multi-text__value">' + html + '</span>');
	jQuery(e).
			attr('onclick', 'apf_remove_dynamic_field(this);return false;').
			children('i').
			toggleClass('fa-plus fa-minus');
	box.children('span').last().children('input').val('').focus();
}

function apf_remove_dynamic_field(e) {
	jQuery(e).parents('.multi-text__value').remove();
}

function apf_file_uploader_init(uploader_id) {

	jQuery('#' + uploader_id + '-select').on('click', function(event) {
		var image = wp.media({
			title: 'Upload Image',
			multiple: false,
		}).open().on('select', function(e) {

			var uploaded_file = image.state().get('selection').first();

			console.log(uploaded_file);

			var file_url = uploaded_file.toJSON().url;
			var file_icon = uploaded_file.toJSON().icon;
			var file_id = uploaded_file.toJSON().id;

			jQuery('#' + uploader_id + ' .apf-file__url').html(file_url);
			jQuery('#' + uploader_id + ' .apf-file__icon').
					html('<img src="' + file_icon + '">');
			jQuery('#' + uploader_id + '-input').val(file_id);

		});
		return false;

	});

	jQuery('#' + uploader_id + '-remove').on('click', function(event) {
		jQuery('#' + uploader_id + ' .apf-file__url').empty();
		jQuery('#' + uploader_id + ' .apf-file__icon').empty();
		jQuery('#' + uploader_id + '-input').val('');
	});

}

function apf_image_uploader_init(uploader_id) {

	jQuery('#' + uploader_id + '-select').on('click', function(event) {
		var image = wp.media({
			title: 'Upload Image',
			multiple: false,
		}).open().on('select', function(e) {

			var uploaded_file = image.state().get('selection').first();

			console.log(uploaded_file);

			var file_url = uploaded_file.toJSON().url;
			var file_id = uploaded_file.toJSON().id;

			jQuery('#' + uploader_id + ' .apf-image__icon').html('<img src="' + file_url + '">');
			jQuery('#' + uploader_id + '-input').val(file_id);

		});
		return false;

	});

	jQuery('#' + uploader_id + '-remove').on('click', function(event) {
		jQuery('#' + uploader_id + ' .apf-image__url').empty();
		jQuery('#' + uploader_id + ' .apf-image__icon').empty();
		jQuery('#' + uploader_id + '-input').val('');
	});

}

function apf_remove_fields_group(e) {
	jQuery(e).parents('.apf-fields-group').remove();
}

function apf_load_new_fields_group(group_id, metabox_id, post_id) {

	post_id = post_id? post_id: 0;

	var cntGroups = jQuery('#' + group_id + ' .apf-fields-group').length;

	let formData = new FormData();

	formData.append('action', 'apf_load_new_fields_group');
	formData.append('group_id', group_id);
	formData.append('counter', cntGroups);
	formData.append('metabox_id', metabox_id);
	formData.append('post_id', post_id);

	fetch(ajaxurl, {
		method: 'POST',
		body: formData,
	}).then(function(response) {
		response.json().then(function(data) {
			if (data.error) {
				alert(data.error);
			}
			if (data.content) {
				jQuery('#' + group_id).append(data.content);
			}
		});
	});

}

function apf_load_fields(post_id, field_id, className){

	className = className? className: '';

	let typeSlice = jQuery('#' + field_id).val();
	console.log(typeSlice);
	if(!typeSlice){
		jQuery('#slice-fields').html('');
		return;
	}

	let formData = new FormData();

	formData.append('action', 'apf_load_fields');
	formData.append('type', typeSlice);
	formData.append('post_id', post_id);
	formData.append('class', className);

	fetch(ajaxurl, {
		method: 'POST',
		body: formData,
	}).then(function(response) {
		response.json().then(function(data) {
			if (data.error) {
				alert(data.error);
			}
			//if (data.content) {
				jQuery('#slice-fields').html(data.content);
			//}
		});
	});

}

document.addEventListener('submit', function (event) {

	if (!event.target.matches('form#post')) return;

	var FormFactory = new APForm(jQuery('form#post'));

	if (!FormFactory.validate()) {
		event.preventDefault();
		jQuery('#publish').removeClass('disabled');
		return false;
	}

	return true;

});

function APForm(form) {

	this.form = form;
	this.errors = {};

	this.validate = function () {

		var valid = true;

		for (var objKey in this.checkForm) {
			var chekObject = this.checkForm[objKey];
			if (!chekObject.isValid.call(this)) {
				valid = false;
				break;
			}
		};

		if (this.errors) {
			for (var k in this.errors) {
				this.showError(this.errors[k]);
			};
		}
		return valid;

	};

	this.addChekForm = function (id, data) {
		this.checkForm[id] = data;
	};

	this.addChekFields = function (id, data) {
		this.checkFields[id] = data;
	};

	this.addError = function (id, error) {
		this.errors[id] = error;
	};

	this.shake = function (shakeBox) {
		shakeBox.css('box-shadow', 'red 0px 0px 5px 1px');
	};

	this.noShake = function (shakeBox) {
		shakeBox.css('box-shadow', 'none');
	};

	this.showError = function (error) {
		alert(error);
		//rcl_notice( error, 'error', 10000 );
	};

	this.checkForm = {
		checkFields: {
			isValid: function () {

				var valid = true;
				var parent = this;

				this.form.find('input,select,textarea').each(function () {

					var field = jQuery(this);
					var typeField = field.attr('type');

					if (field.is('textarea')) {
						typeField = 'textarea';
					} else if (field.is('select')) {
						typeField = 'select';
						if (jQuery(field).prop('multiple')) {
							typeField = 'multiselect';
						}
					}

					var checkFields = parent.checkFields;

					for (var objKey in checkFields) {

						var chekObject = checkFields[objKey];

						if (chekObject.types.length && jQuery.inArray(typeField, chekObject.types) < 0) {
							continue;
						}

						var shakeBox = jQuery.inArray(typeField, ['radio', 'checkbox']) < 0 ? field : field.next('label');

						if (jQuery.inArray(typeField, ['hidden', 'multiselect']) >= 0) {
							shakeBox = field.parent();
						}

						if (!chekObject.isValid(field)) {

							parent.shake(shakeBox);
							parent.addError(objKey, chekObject.errorText());
							valid = false;
							return;

						} else {
							parent.noShake(shakeBox);
						}

					};
				});
				return valid;
			}
		}
	};

	this.checkFields = {
		required: {
			types: [],
			isValid: function (field) {

				var required = true;

				if (!field.prop("required"))
					return required;

				if (field.prop("disabled"))
					return required;

				var value = false;

				if (field.attr('type') == 'checkbox') {
					if (jQuery('input[name="' + field.attr('name') + '"]:checked').val())
						value = true;
				} else if (field.attr('type') == 'radio') {
					if (jQuery('input[name="' + field.attr('name') + '"]:checked').val())
						value = true;
				} else {
					if (field.val())
						value = true;
				}

				if (!value) {
					required = false;
				}

				return required;

			},
			errorText: function () {
				return 'Заполните обязательные поля!';
			}
		},
		reqMultiSelect: {
			types: ['multiselect'],
			isValid: function (field) {

				if (field.prop("disabled"))
					return true;

				if (!field.attr("is_required"))
					return true;

				return field.val().length;

			},
			errorText: function () {
				return 'Заполните обязательные поля!';
			}
		},
		numberRange: {
			types: ['number'],
			isValid: function (field) {
				var range = true;

				var val = field.val();

				if (val === '')
					return true;

				val = parseInt(val);
				var min = parseInt(field.attr('min'));
				var max = parseInt(field.attr('max'));

				if (min != 'undefined' && min > val || max != 'undefined' && max < val) {
					range = false;
				}

				return range;
			},
			errorText: function () {
				return 'Укажите верный диапазон!';
			}

		},
		pattern: {
			types: ['text', 'tel'],
			isValid: function (field) {

				var val = field.val();

				if (!val)
					return true;

				var pattern = field.attr('pattern');

				if (!pattern)
					return true;

				var re = new RegExp(pattern);

				return re.test(val);
			},
			errorText: function () {
				return 'Укажите верный формат данных поля!';
			}

		},
		fileMaxSize: {
			types: ['file'],
			isValid: function (field) {

				var valid = true;

				field.each(function () {

					var maxsize = jQuery(this).data("size");
					var fileInput = jQuery(this)[0];
					var file = fileInput.files[0];

					if (!file)
						return;

					var filesize = file.size / 1024 / 1024;

					if (filesize > maxsize) {
						valid = false;
						return;
					}

				});

				return valid;
			},
			errorText: function () {
				return 'Превышен размер файла!';
			}

		},
		fileAccept: {
			types: ['file'],
			isValid: function (field) {

				var valid = true;

				field.each(function () {

					var fileInput = jQuery(this)[0];
					var file = fileInput.files[0];
					var accept = fileInput.accept.split(',');

					if (!file)
						return;

					if (accept) {

						var fileType = false;

						if (file.type) {

							for (var i in accept) {
								if (accept[i] == file.type) {
									fileType = true;
									return;
								}
							}

						}

						var exts = jQuery(this).data("ext");

						if (!exts)
							return;

						if (!fileType) {

							var exts = exts.split(',');
							var filename = file.name;

							for (var i in exts) {
								if (filename.indexOf('.' + exts[i]) + 1) {
									fileType = true;
									return;
								}
							}

						}

						if (!fileType) {
							valid = false;
							return;
						}

					}

				});

				return valid;
			},
			errorText: function () {
				return 'Указан неверный тип файла!';
			}

		}
	};

	this.send = function (action, success) {

	};

}
