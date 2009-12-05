<!DOCTYPE html>
<!-- {$exception_class}: {$e->getMessage()|escape:'html'} -->
<html lang="en">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6/prototype.js"></script>
	<script type="text/javascript" src="/static/cobweb/vendor/highlight/highlight.pack.js"></script>

	<style type="text/css" media="screen">
		{include file='cobweb_debug.css'}
	</style>
	
    <script type="text/javascript">
		{include file='cobweb_debug.js'}
    </script>
	
	<title>Cobweb exception: {$exception_class}</title>
</head>

<body>
	<div id="header"><h1>Cobweb caught an exception</h1></div>
	<div id="exception">
		    <h3>Caught exception</h3>
		    <h2>{$exception_class}</h2>
		    <h3>it was thrown in</h3>
		    <p>
			        <a href="txmt://open?url=file://{$e->getFile()}&amp;line={$e->getLine()}">
			    	<tt>{$file_path}</tt>
			        </a>
			    <span style="font-size: 12px;">on line {$e->getLine()}</span>
		    </p>
		
		
		<h3>Message</h3>
		<p class="message">{$e->getMessage()|escape:'html'|nl2br}</p>
	</div>
	
	<div id="content">
	    
		
		<h3>Stacktrace</h3>
		<table cellspacing="0" cellpadding="0" border-collapse="0" style="margin-bottom: 30px;">
			<thead>
				<tr>
					<th style="width: 30px;"></th>
					<th>File</th>
					<th>Context</th>
					<th style="width: 20px;">
						<input id="toggle-all" class="source-toggler" type="button" onclick="Cobweb.Stacktrace.toggleAllSource();" value="" />
					</th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$backtrace item=trace name=loop}
			<tr {if isset($trace.is_controller)}class="controller-stackpoint"{/if}>
				<td style="text-align: right;">{$trace.line|default:""}</td>
				<td>{if isset($trace.file)}<a title="{$trace.file}" href="txmt://open?url=file://{$trace.file}&amp;line={$trace.line}">{$trace.base_filename}{/if}</td>
				<td>
					<tt><b>{$trace.class|default:""}</b>
						{if isset($trace.type)}{if $trace.type == "->"}→{else}{$trace.type|truncate:150}{/if}{/if}
						{$trace.function}
					</tt>
				</td>
				<td style="width: 20px;">
					{if isset($trace.source)}<input class="source-toggler" type="button" onclick="$(this).up().up().next().toggle();" value="" />{/if}
				</td>
			</tr>
			{if isset($trace.source)}
			<tr {if $smarty.foreach.loop.index != 0}style="display: none;"{/if}>
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
										{$arg.value} (<a href="javascript:console.log({$arg.json|escape})">{$arg.type}</a>)
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
						{foreach from=$REQUEST->headers() item=value key=header}
							<tr>
								<td><b>{$header}</b></td>
								<td>{$value}</td>
							</tr>
						{/foreach}
						</table>
						</td>
					</td>
				</tr>
				{if $attempted_patterns}
				<tr>
					<th>Attempted to match URL patterns...</th>
					<td>
						<table style="width: 100%; margin-top: 0px;">
						{foreach from=$attempted_patterns item=pattern}
							<tr>
								<td><tt>{$pattern|escape}</tt></td>
							</tr>
						{/foreach}
						</table>
						</td>
					</td>
				</tr>
				{/if}
				{if $matching_pattern}
				<tr>
					<th>Matching URL pattern</th>
					<td>
						<tt>{$matching_pattern|escape}</tt>
					</td>
				</tr>
				{/if}
			</tbody>
		</table>
		
	</div>
</body>
</html>