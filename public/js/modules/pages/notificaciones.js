/**
* Products page module.
* @module pages/proveedores
* @author AdminBootstrap.com
*/

App.page.notificaciones = function() {
	/**
	* @const {object} Constant css-selectors to link up with HTML markup.
	*/
	var c = {
		TABLE: {
			TABLE: '#table',
			INFO: '#table-info',
			LENGTH: '#table-length',
			SEARCH: '#table-search',
			RESET: {
				BUTTON: '#table-reset',
				CONTAINER: '#table-reset-container'
			},
			PAGINATION: '#table-pagination',
			EXPORT: {
				BUTTONS: '#table-export',
				FILE: 'notificaciones-' + moment().format('MMDDYYYY')
			}
		},
		FORM: {
			FORM: '#form-add',
			IGNORE: '.ignore',
			INFO: '#form-info',
			SAVE: '#form-save',
		},
	}

	/**
	* @var {object} Module settings object.
	*/
	var s = {
		widgets: {}
	};

	function initWidgets() {
		$('#inicio').datepicker().datepicker('setDate', new Date());
		$('#fin').datepicker();

		// DataTable Widget
		s.widgets.table = new App.classes.TableWidget({
			table: c.TABLE.TABLE,
			info: c.TABLE.INFO,
			search: c.TABLE.SEARCH,
			length: c.TABLE.LENGTH,
			pagination: c.TABLE.PAGINATION,
			buttons: c.TABLE.EXPORT.BUTTONS,
			dataTable: {
				'columnDefs': [
					{ 'orderable': false, 'targets': [10] },
					{ 'searchable': true, 'targets': [0, 1, 2, 3] }
				],
				'order': [[0, 'desc']],
				'buttons': {
					buttons: [
						{
							extend: 'pdfHtml5',
							text: 'PDF',
							title: c.TABLE.EXPORT.FILE,
							exportOptions: {
								columns: ':not(.accion)'
							}
						},
						{
							extend: 'excelHtml5',
							text: 'Excel',
							title: c.TABLE.EXPORT.FILE,
							exportOptions: {
								columns: ':not(.accion)'
							}
						},
						{
							extend: 'csvHtml5',
							text: 'CSV',
							title: c.TABLE.EXPORT.FILE,
							exportOptions: {
								columns: ':not(.accion)'
							}
						},
						{
							extend: 'print',
							text: 'Imprimir',
							exportOptions: {
								columns: ':not(.accion)'
							}
						}
					],
					dom: {
						container: {
							tag: 'ul',
							className: 'dropdown-menu'
						},
						button: {
							tag: 'li',
							className: ''
						},
						buttonLiner: {
							tag: 'a'
						}
					}
				}
			}
		});

		// Validate Form Widget
		s.widgets.validate = new App.classes.ValidateWidget({
			form: c.FORM.FORM,
			ignore: c.FORM.IGNORE,
			info: c.FORM.INFO,
			validate: {}
		});
	}

	function initPlugins() {
		return;
	}

	function bindUIActions() {
		// Reset filter and search
		$(document).on('click', c.TABLE.RESET, function() {
			s.widgets.table.clearSearch();
		});

		$(document).on('click', c.FORM.SAVE, function(e) {
			var form = $(c.FORM.FORM);
			var isValid = form.valid();

			if(!isValid) {
				console.log('1');
				e.preventDefault();
			} else {
				console.log('2');
				e.preventDefault();

				$.ajax({
					type: form.attr('method'),
					url: form.attr('action'),
					data: form.serialize(),
					success: function(data) {
						console.log(data);
					},
					error: function(error) {
						error = jQuery.parseJSON(error.responseText);
						$('.notificacion').html('<div class="alert alert-danger alert-dismissible" role="alert"><button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><i class="alert-ico fa fa-fw fa-close"></i><strong>¡Error! </strong>'+error.detalle+'</div>');
					},
				});
			}
		});
	}

	function bindTableEvents() {
		var table = s.widgets.table.dataTable;

		table.on('search.dt', function(e, settings) {
			handleResetBtn();
		});
	}

	function handleResetBtn() {
		var dt = s.widgets.table.dataTable;
		if(dt.page.info().recordsTotal > dt.page.info().recordsDisplay) {
			if(!$(c.TABLE.RESET.BUTTON).length) {
				$(c.TABLE.RESET.CONTAINER).append('<button id="table-reset" class="btn btn-default"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></button>');
			}
		} else {
			$(c.TABLE.RESET.BUTTON).remove();
		}
	}

	function init() {
		initPlugins();
		initWidgets();
		bindTableEvents();
		bindUIActions();
	}

	init();

	return s;
}();