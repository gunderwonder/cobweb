var Arachnid = {
	
	buildTOC : function() {
		var toc = '<ol>';
		$$('h3').each(function (h) {
			toc += '<li><a href="#' + h.id + '">' + h.innerHTML +'</a></li>';
		});
		$('toc').update(toc);
	}
	
}

initHighlightingOnLoad()
// Cobweb.defer(Arachnid.buildTOC);

Event.observe(window, 'load', function() {
	if (Prototype.Browser.Gecko)
		$$('#menubar ol')[0].setStyle({ top : '4px' });
	
	var resizer = function(e) {
		if (document.viewport.getHeight() > $('content').getHeight())
			$('footer').setStyle({ bottom : '0' });
	}
	
	resizer();
});
