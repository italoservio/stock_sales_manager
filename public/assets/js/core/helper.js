const helper = {

	validate(p_value, p_arr) {
		p_value = p_value.trim();
		var valid = true;
		for (const i of p_arr) {
			switch(i) {
				case 'required':
					valid = (p_value !== '' && p_value !== null);
					break;
				case 'text':
					valid = /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/g.test(p_value);
					break;
				case 'number':
					valid = /^[0-9]+$/g.test(p_value);
					break;
				case 'textnumber':
					valid = /^[0-9A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]*$/g.test(p_value);
					break;
				case 'email':
					valid = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/g.test(p_value);
					break;
			}
		}
		return valid;
	},

	alert(p_flag, p_msg) {
		$html = `
		<div class="alert alert-dismissible alert-${p_flag} my-2">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <strong>ERRO:</strong> ${p_msg}.
		</div>
		`;
		return $html;
	},

	addFieldValidation(p_arrFields, p_bool) {
		this.clearFieldValidation(p_arrFields);
		var css = (p_bool) ? 'is-valid' : 'is-invalid';
		for (const i of p_arrFields) {
			i.addClass(css);
		}
	},

	clearFieldValidation(p_arrFields) {
		for (const i of p_arrFields) {
			if (i.hasClass('is-valid')) {
				i.removeClass('is-valid');
			} else if (i.hasClass('is-invalid')) {
				i.removeClass('is-invalid');
			}
		}
	}

}