	$(document).ready(function() {
		fancySetup();
	});


	function fancySetup() {
		$("a.fancy").fancybox({
			'transitionIn'	: 'elastic',
			'transitionOut'	: 'elastic',
			'opacity'				: true,
			'overlayShow'		: true,
			'zoomSpeedIn'		: 300,
			'zoomSpeedOut'	: 500,
			'padding'				: 0
		});
	}


	function reQuery(func){setTimeout(func,1000);}
	function refreshPage(){window.location.href=window.location.href.replace(/#.*$/,'');}
	function ajaxQuery(params,callback) {
		if (typeof(params.query)=='undefined') return false;
		params['rnd'] = Math.random();
		$.getJSON('/ajax.php',params,function(data){
			if (data==null || data.status=='waiting'){reQuery(function(){ajaxQuery(params,callback)}); return false;}
			if (typeof(callback)=='function'){callback(data);}
		});
	}