<!-- /resources/views/emails/changerequest.blade.php -->

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style type="text/css">
	html {
		font-family: sans-serif;
		-webkit-text-size-adjust: 100%;
		-ms-text-size-adjust: 100%;
	}
	body {
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		font-size: 14px;
		line-height: 1.42857143;
		color: #333;
		background-color: #fff;
		margin: 0;
	}
	h1, h2,	h3, h4 {
	  font-family: inherit;
	  font-weight: 500;
	  line-height: 1.1;
	  color: inherit;
	}
	h1 {
		font-size: 36px;
	}
	h2 {
		font-size: 30px;
	}
	h3 {
		font-size: 24px;
	}
	h4 {
		font-size: 18px;
	}
	small {
		font-size: 80%;
	}
	a, a:visited {
		text-decoration: underline;
		color: #337ab7;
		text-decoration: none;
		background-color: transparent;
	}
	a:focus {
		color: #23527c;
		text-decoration: underline;
	}
	</style>
</head>

<body>
<div class="block">
<!-- start textbox-with-title -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="fulltext">
<tbody>
<tr>
<td>
<table width="580" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" modulebg="edit">
<tbody>
<!-- Spacing -->
<tr>
<td width="100%" height="30"></td>
</tr>
<!-- Spacing -->
<tr>
<td>
<table width="540" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
<tbody>

<div class="block">
<!-- Full + text -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="fullimage">
<tbody>
<tr>
<td>
<table width="580" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth" modulebg="edit">
<tbody>
<tr>
<td width="100%" height="20"></td>
</tr>
<tr>
<td>
<table width="540" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidthinner">
<tbody>

<h2>RADAR Changerequest notification</h2>
<h4>A new template {{ $template_name }} has been created by {{ $username }}.</h4>
<h4><a href="{!! url('sections/' . $section_id . '/templates/' . $template_id); !!}">Click here</a> to see the new template</h4>
<small>If you have any questions, please send an email to: <a href="mailto:{!! App\Helper::setting('administrator_email') !!}">{!! App\Helper::setting('administrator_email') !!}</a></small>

</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</div>

</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<!-- end of textbox-with-title -->
</div>

</body>
