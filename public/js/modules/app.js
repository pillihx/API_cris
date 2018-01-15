/**
* The main module that does some global initialization.
* @module app
* @author AdminBootstrap.com
*/

window.App = function() {
	/**
	* @function Initializes plugins that may be needed on any page.
	*/
	function initPlugins() {

		// Activo el menú
		var url = window.location.href;
		var hash = url.substring(url.indexOf('?'));

		if(hash.indexOf('&id=') >= 0) {
			// Limpio el ID de la URL
			hash = hash.substring(0, hash.indexOf('&id='));
			// Reemplazo modificar por agregar para efecto de activar el menu
			hash = hash.replace('modificar', 'agregar');
		}
		var menu = $('.xp-menu').find('a[href$="/'+hash+'"]:first');

		menu.parent().addClass('active');
		menu.parent().parent().parent().addClass('active');

		// Bootstrap Tooltips
		$('[data-toggle="tooltip"]').tooltip()

		// Bootstrap Popovers
		$('[data-toggle="popover"]').popover()
	}

	return {
		classes: {},
		layout: {},
		page: {},
		service: {},
		settings: {
			firstWeekDay: 1
		},
		utils: {
			getUTCTimeFromLocalTime: function(time) {
				var date = new Date(time);
				return Date.UTC(date.getFullYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes(), date.getSeconds(), date.getMilliseconds());
			},
			getLocalTimeFromUTCTime: function(time) {
				var offset = new Date().getTimezoneOffset() * 60 * 1000;
				return new Date(time).getTime() + offset;
			},
			formatNumber: function(num, c, cur, d, t) {
				var c = isNaN(c = Math.abs(c)) ? 2 : c,
				cur = cur == undefined ? "" : cur,
				d = d == undefined ? "." : d,
				t = t == undefined ? "," : t,
				s = num < 0 ? "-" : "",
				i = String(parseInt(num = Math.abs(Number(num) || 0).toFixed(c))),
				j = (j = i.length) > 3 ? j % 3 : 0;
				return s + cur + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(num - i).toFixed(c).slice(2) : "");
			}
		},
		init: function() {
			initPlugins();
		}
	}
}();

$(document).ready(function() {
	App.init();
})
