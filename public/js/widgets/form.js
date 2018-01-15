/**
* Table Widget.
* @module widgets/table
* @param {object} options - Widget init options
* @author AdminBootstrap.com
*/

App.classes.ValidateWidget = function(options) {
	var o = options || {};

	/**
	* @const {object} Constant css-selectors to link up with HTML markup.
	*/
	var c = {};

	/**
	* @var {object} Module settings object.
	*/
	var s = {
		$form: $(o.form),
		$ignore: $(o.ignore),
		$info: $(o.info),
		validate: null
	};

	/**
	* @function Validate initial options
	*
	* @return {boolean} Result
	*/
	function validate() {
		/*try {
			if(typeof $.fn.DataTable != 'function') throw 'TableWidget: DataTables Plugin no ha sido encontrado.';
			if(!s.$table.length && !s.$table.is('table')) throw 'TableWidget: "' + o.table + '"" no es una tabla valida.';
		} catch(e) {
			console.warn(e);
			return false;
		}*/

		return true;
	}

	function initDataTable() {
		$.extend($.validator.messages, {
			required: 'Este campo es obligatorio.',
			remote: 'Por favor, rellena este campo.',
			email: 'Por favor, escribe una dirección de correo válida.',
			url: 'Por favor, escribe una URL válida.',
			date: 'Por favor, escribe una fecha válida.',
			dateISO: 'Por favor, escribe una fecha (ISO) válida.',
			number: 'Por favor, escribe un número válido.',
			digits: 'Por favor, escribe sólo dígitos.',
			creditcard: 'Por favor, escribe un número de tarjeta válido.',
			equalTo: 'Por favor, escribe el mismo valor de nuevo.',
			extension: 'Por favor, escribe un valor con una extensión aceptada.',
			maxlength: $.validator.format( 'Por favor, no escribas más de {0} caracteres.' ),
			minlength: $.validator.format( 'Por favor, no escribas menos de {0} caracteres.' ),
			rangelength: $.validator.format( 'Por favor, escribe un valor entre {0} y {1} caracteres.' ),
			range: $.validator.format( 'Por favor, escribe un valor entre {0} y {1}.' ),
			max: $.validator.format( 'Por favor, escribe un valor menor o igual a {0}.' ),
			min: $.validator.format( 'Por favor, escribe un valor mayor o igual a {0}.' )
		});

		s.validate = $(s.$form).validate({
				debug: true,
				ignore: s.$ignore,
				invalidHandler: function(e, validator) {
					e.preventDefault();
					/*if(validator.errorList.length) {
						$('.st-panel__tabs a[href="#' + jQuery(validator.errorList[0].element).closest('.tab-pane').attr('id') + '"]').tab('show');
					}*/
				},
				errorPlacement: function(error, element) {
					if(element.is('select')) {
						error.appendTo(element.parent());
					} else {
						error.insertAfter(element);
					}
				}
			});
	}

	function bindUIActions() {

	}

	function init() {
		if(validate()) {
			initDataTable();
			bindUIActions();
		}
	};

	init();

	return s;
};
