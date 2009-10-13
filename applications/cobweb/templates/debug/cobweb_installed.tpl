<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6/prototype.js"></script>
	<script type="text/javascript" src="/static/cobweb/vendor/highlight/highlight.pack.js"></script>
	<style type="text/css" media="screen">
		<?php $this->include_template('debug/cobweb_debug.css'); ?>
	</style>
	<title>Cobweb sucessfully installed!</title>
</head>
<body id="cobweb-installation">
	<div id="header"><h1>Cobweb installed</h1></div>
	<div id="content">
		<h2>Congratulations!</h2>
		<p>If you're seeing this message, it means Cobweb has sucessfully installed, and you're good to go!</p>
		
		<h3>status</h3>
		<ul class="warning-list">
		<?php $compiled_templates_dir = Cobweb::get('COMPILED_TEMPLATES_DIRECTORY'); ?>
		<?php $data_dir = Cobweb::get('COMPILED_TEMPLATES_DIRECTORY'); ?>
		<?php if (!is_writable($compiled_templates_dir)): ?>
			<li><span class="error">✖</span>Compiled templates directory <tt><?php echo $compiled_templates_dir ?></tt> is not writable!</li>
		<?php else: ?>
			<li><span>✔</span>Compiled templates directory <tt><?php echo $compiled_templates_dir ?></tt> is writable.</li>
		<?php endif ?>
		<?php if (!is_writable($data_dir)): ?>
			<li><span class="error">✖</span>Data directory <tt><?php echo $data_dir ?></tt> is not writable!</li>
		<?php else: ?>
			<li><span>✔</span>Data directory <tt><?php echo $data_dir ?></tt> is writable.</li>
		<?php endif ?>
		</ul>
	</div>
</div>