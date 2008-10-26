<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Cobweb</title>
	
	<link rel="stylesheet" href="{$URL_PREFIX}/static/cobweb/css/documentation.css" type="text/css" media="screen" charset="utf-8" />


	<script type="text/javascript" src="{$URL_PREFIX}/static/cobweb/vendor/highlight/highlight.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6/prototype.js"></script>
	<script type="text/javascript" src="{$URL_PREFIX}/static/cobweb/js/cobweb-site.js"></script>

</head>
<body>

<div id="header">
	<div id="masthead">
		<img src="{$URL_PREFIX}/static/cobweb/img/us-logo.png" alt="" />
		<h1>Cobweb</h1>
	</div>
	
	<div id="search">
		<form action="/search" method="get">
			<fieldset>
				<input type="text" name="q" id="q" />
				<input type="submit" value="Search" />
			</fieldset>
		</form>
	</div>
</div>

<div id="menubar">
	<ol>
		<li><a href="{$URL_PREFIX}/">Home</a></li>
		<li><a href="{$URL_PREFIX}/manual" class="selected">Manual</a></li>
		<li><a href="{$URL_PREFIX}/api">API Documentation</a></li>
		<li><a href="{$URL_PREFIX}/about">About</a></li>
	</ol>
</div>

<div id="content">
	
	<div id="sidebar">
		<div>
			<ol>
				<li>
					<a href="#" class="current">Introduction</a>
				</li>
				<li>
					<a href="/getting-started">Getting started</a>
					<ol>
						<li><a href="/getting-started#requirements">Requirements</a></li>
						<li><a href="/getting-started#installation">Installation</a></li>
					</ol>
				</li>
			</ol>
		</div>
	</div>
	
	<div id="stuff">
		{$markdown}
	</div>
	
	
	
</div>

<div id="footer">&copy; 2008 <a href="http://www.upstruct.com/">upstruct berlin oslo</a></div>
</body>
</html>
