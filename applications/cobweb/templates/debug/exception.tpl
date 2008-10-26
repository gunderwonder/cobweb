<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6/prototype.js"></script>
	<script type="text/javascript" src="{$URL_PREFIX}/static/cobweb/vendor/highlight/highlight.js"></script>
	
	<link rel="stylesheet" href="{$URL_PREFIX}/static/cobweb/css/code.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="{$URL_PREFIX}/static/cobweb/css/debug.css" type="text/css" media="screen" />
	
	
    <script type="text/javascript">
		{literal}

		hljs.initHighlightingOnLoad();
		Event.observe(window, 'load', function(e) {
			$$('.php').each(function (p) {
				var old = p.innerHTML.replace(/^\t/gm, '');
				var line = 0;
				var startLine = 0;
				p.classNames().each(function(className) {
					if (className.startsWith('stack-line-')) {
						line = className.substring('stack-line-'.length);
						line = parseInt(line);
						
					}
				});
				if (p.hasClassName('backtrace-comment')) {
					p.innerHTML = old;
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
							// if (c.match(/$/))
							// 	buffer += "\n";
						});
						// buffer += '' + l + '</span>' + "\n";
					}
					else
						buffer += l + "\n";
					i++;
				})

				p.innerHTML = buffer;
			})
			
		});
		function toggleAllSource() {
				$$('.source-toggler').each(function(t) {
					if (t.id == 'toggle-all')	
						return;
					t.click();
				})
			}
		{/literal}
    </script>
	

	<title>Cobweb</title>
	
</head>

<body>
	<div id="header"><h1>Cobweb caught an exception</h1></div>
	<div id="content">
		<h3>caught exception</h3>
		<h2>{$exception_class}</h2>
		<h3>it was thrown in</h3>
		<p><a href="txmt://open?url=file://{$e->getFile()}&amp;line={$e->getLine()}"><tt>{$file_path}</tt></a>
			<span style="font-size: 12px;">on line {$e->getLine()}</span></p>
		
		<h3>message</h3>
		<p>{$e->getMessage()}</p>
		

		
		<h3>stacktrace</h3>
		<table cellspacing="0" cellpadding="0" border-collapse="0" style="margin-bottom: 30px;">
			<thead>
					<tr>
					<th style="width: 30px;"></th>
					<th>File</th>
					<th>Context</th>
					<th style="width: 20px;">
						<input id="toggle-all" class="source-toggler" type="button" onclick="toggleAllSource();" value="" />
					</th>
				</td>
				</tr>
			</thead>
			<tbody>
			{foreach from=$backtrace item=trace}
			<tr {if isset($trace.is_controller)}class="controller-stackpoint"{/if}>
				<td style="text-align: right;">{$trace.line|default:""}</td>
				<td>{if isset($trace.file)}<a title="{$trace.file}" href="txmt://open?url=file://{$trace.file}&amp;line={$trace.line}">{$trace.base_filename}{/if}</td>
				<td>
					<tt><b>{$trace.class|default:""}</b>
						{if isset($trace.type)}{if $trace.type == "->"}→{else}{$trace.type|truncate:150}{/if}{/if}
						{$trace.function}
						({*{if isset($trace.args) && count($trace.args) > 0}<span class="args" title="{foreach from=$trace.named_args key=name item=arg name=loop}{$name} → {$arg}{if not $smarty.foreach.loop.last}&#10;{/if}{/foreach}">arguments</span>{/if}*})
					</tt>
					
				</td>
				<td style="width: 20px;">
					{if isset($trace.source)}<input class="source-toggler" type="button" onclick="$(this).up().up().next().toggle();" value="" />{/if}
				</td>
			</tr>
			{if isset($trace.source)}
			<tr style="display: none;">
				<td colspan="4" >
					<div style="clear: both; margin-left: 30px;">
					
						{if $trace.comments}
						<h4 style="margin-bottom: 0;">Comments</h4>
						<pre><code class="backtrace-comment php">{$trace.comments}</code></pre>
						{/if}
						
						
						{if isset($trace.args) && count($trace.args) > 0}
						<h4 style="margin-bottom: 0;">Parameters</h4>
						<table style="width: auto;">
							{foreach from=$trace.named_args item=arg key=name}
								<tr>
									<td style="min-width: 100px;text-align: right;">
										<tt>${$name}</tt>
									</td>
									<td>
										{$arg.value} (<a href="#" onclick="console.log({$arg.json|escape})">{$arg.type}</a>)
									</td>
								</tr>
							{/foreach}
							
						</table>
						{/if}
					
						<h4 style="margin-bottom: 0;">Source</h4>
					</div>
					<div style="float: left; margin-right: 15px; margin-left: 30px;">
					
						<pre style="background-color: #EAEAEA;">{foreach from=$trace.source_range item=line}{$line}
{/foreach}</pre>
					</div>
					<pre><code class="stack-line-{$trace.stack_line-$trace.source_range[0]} php">{$trace.source}</code><pre>
				</td>
			</tr>
			{/if}
			{/foreach} {* end backtrace *}
			</tbody>
		</table>
		
		<h3>Request Information</h3>
		<table cellspacing="0" cellpadding="0" border-collapse="0" class="vertical-table-header">
			<tbody>
				<tr>
					<th>URI</th>
					<td>{$REQUEST->URI()}</td>
				</tr>
				<tr>
					<th>Method</th>
					<td>{$REQUEST->method()}</td>
				</tr>
				<tr>
					<th>Request headers</th>
					<td>
						<table style="width: 100%; margin-top: 0px;">
						{foreach from=$REQUEST item=value key=header}
							<tr>
								<td><b>{$header}</b></td>
								<td>{$value}</td>
							</tr>
						{/foreach}
						</table>
						</td>
					</td>
				</tr>
				<!-- <tr>
					<th>Response headers</th>
					<td>
						<table style="width: 100%; margin-top: 0px;">
						{foreach from=$response_headers item=value key=header}
							<tr>
								<td><b>{$header}</b></td>
								<td>{$value}</td>
							</tr>
						{/foreach}
						</table>
						</td>
					</td>
				</tr> -->
			</tbody>
		</table>
		
		<div style="position: fixed; bottom: 20px; right: 20px; font-size: 12px; text-align: right;">
			Regards, <br />
			<div style="padding-top: 10px;">Øystein</div>
			Your friendly <br />neighborhood <br />programmer
		</div>
	</div>
</body>
</html>