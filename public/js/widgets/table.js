/**
* Table Widget.
* @module widgets/table
* @param {object} options - Widget init options
* @author AdminBootstrap.com
*/

App.classes.TableWidget = function(options) {
	var o = options || {};

	/**
	* @const {object} Constant css-selectors to link up with HTML markup.
	*/
	var c = {};

	/**
	* @var {object} Module settings object.
	*/
	var s = {
		$table: $(o.table),
		$info: $(o.info),
		$search: $(o.search),
		$length: $(o.length),
		$pagination: $(o.pagination),
		$buttons: $(o.buttons),
		dataTable: null
	};

	/**
	* @function Validate initial options
	*
	* @return {boolean} Result
	*/
	function validate() {
		try {
			if(typeof $.fn.DataTable != 'function') throw 'TableWidget: DataTables Plugin no ha sido encontrado.';
			if(!s.$table.length && !s.$table.is('table')) throw 'TableWidget: "' + o.table + '"" no es una tabla valida.';
		} catch(e) {
			console.warn(e);
			return false;
		}

		return true;
	}

	// DataTable Plugin Init
	function initDataTable() {
		// DataTable options
		var options = {
			'dom': 't',
			'pagingType': 'numbers',
			'pageLength': parseInt(s.$length.val()) || 10,
			language: {
				'sProcessing': 'Procesando...',
				'sLengthMenu': 'Mostrar _MENU_ registros',
				'sZeroRecords': 'No se encontraron resultados',
				'sEmptyTable': 'Ningún dato disponible en esta tabla',
				'sInfo': 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
				'sInfoEmpty': 'Mostrando registros del 0 al 0 de un total de 0 registros',
				'sInfoFiltered': '(filtrado de un total de _MAX_ registros)',
				'decimal': ',',
				'thousands': '.',
				'sInfoPostFix': '',
				'sSearch': 'Buscar:',
				'sUrl': '',
				'sInfoThousands': ',',
				'sLoadingRecords': 'Cargando...',
				'oPaginate': {
					'sFirst': 'Primero',
					'sLast': 'Último',
					'sNext': 'Siguiente',
					'sPrevious': 'Anterior'
				},
				'oAria': {
					'sSortAscending': ': Activar para ordenar la columna de manera ascendente',
					'sSortDescending': ': Activar para ordenar la columna de manera descendente'
				}
			}
		};

		if(o.info) {
			options.dom += 'i';
		}

		if(o.pagination) {
			options.dom += 'p';
		}

		s.dataTable = s.$table.DataTable($.extend(options, o.dataTable));

		// Move Pagination
		if(s.$pagination.length) {
			$(s.dataTable.table().container()).find('.dataTables_paginate').appendTo(s.$pagination);
		}

		// Move Info
		if(s.$info.length) {
			$(s.dataTable.table().container()).find('.dataTables_info').appendTo(s.$info);
		}

		// Move Buttons
		if(s.$buttons.length) {
			s.dataTable.buttons( 0, null ).container().appendTo(s.$buttons);
		}
	}

	function bindUIActions() {
		// Table length control
		if(s.$length.length) {
			s.$length.on('change', function () {
				s.dataTable.page.len(parseInt($(this).val())).draw();
			});
		}
		// Table Search
		if(s.$search.length) {
			s.$search.on('keyup', function () {
				s.dataTable.search($(this).val()).draw();
			});
		}
	}

	function init() {
		if(validate()) {
			initDataTable();
			bindUIActions();
		}
	};

	init();

	s.clearSearch = function() {
		if(this.$search.length) {
			this.$search.val('');
			this.dataTable.search('').draw();
		}
	}

	return s;
};
