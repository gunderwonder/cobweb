{literal}
var Cobweb = Cobweb || {};

Cobweb.Stacktrace = {
	highlightStack : function() {
		$$('.php').each(function (p) {
			var old = p.innerHTML.replace(/^\t/gm, ''), line = 0, startLine = 0;
			p.classNames().each(function(className) {
				if (className.startsWith('stack-line-'))
					line = parseInt(className.substring('stack-line-'.length));
			});
			if (p.hasClassName('backtrace-comment')) {
				p.update(old);
				return;
			}
				
			var lines = old.split('\n'), i = 0, buffer = '';
			lines.each(function(l) {
				if (i == line) {
					var nonWhitespaceSeen = false;
					var j = 0;
					var chars = $A(l.split(''));
					chars.each(function(c) {
						if (nonWhitespaceSeen)
							buffer += c;
						else if (c.match(/\s/))
							buffer += c;
						else {
							buffer += '<span class="stack-line">' + c;
							nonWhitespaceSeen = true;
						}
						if (j == chars.size() - 1)
							buffer += "</span>\n";
						j++;
					});
				}
				else
					buffer += l + "\n";
				i++;
			})
        
			p.update(buffer);
		})
	},
	
	toggleAllSource : function() {
		$$('.source-toggler').each(function(t) {
			if (t.id == 'toggle-all')	
				return;
			t.click();
		})
	}
}

if (typeof hljs != 'undefined') {
	hljs.initHighlightingOnLoad('php');
	Event.observe(window, 'load', Cobweb.Stacktrace.highlightStack);
}
{/literal}