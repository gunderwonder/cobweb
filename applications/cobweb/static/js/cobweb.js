var Cobweb = Cobweb || { };
Cobweb.Resolver = Cobweb.Resolver || { };

Cobweb.Resolver.reverse = function() {
	var REGEX_RE = /\((.*?)\)/g;
	
	var action = arguments[0], 
	    parameters = $A(arguments).slice(1), 
	    reverseMap = Cobweb.Resolver.ReverseMap ? Cobweb.Resolver.ReverseMap : { };
	
	if (!(action in reverseMap))
		throw new Error("No action '#{a}' in reverse map!".interpolate({a : action}));
	
	var pattern = reverseMap[action];
	var matches = pattern.match(REGEX_RE);
	if (matches && matches.length > parameters.length)
		throw new Error(
			"Insufficient number of arguments to resolve '#{a}'".interpolate({
				a : action
			}));
	
	var resolved = pattern;
	if (matches)
		matches.zip(parameters, function(pair) {
			var subpattern = pair[0], replacement = pair[1];
			
			if (!replacement.toString().match(new RegExp(subpattern)))
				throw new Error("Subpattern '#{p}' doesn't match '#{v}'!".interpolate({
					p : subpattern, v : replacement
				}));
			resolved = resolved.replace(subpattern, replacement);
		})
	
	return '/' + resolved.gsub(/[\^\$\?]/, '');
}