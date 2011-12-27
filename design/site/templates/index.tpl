{* Smarty *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
{include file='head.tpl'}
<body>
	
	
<div id="wrapper">

	<div id="header">
		<strong>Header:</strong>
	</div><!-- #header-->

	<div id="middle">

		<div id="container">
			<div id="content">
				{$content}
			</div><!-- #content-->
		</div><!-- #container-->

		<div class="sidebar" id="sideLeft">
			<strong>Left Sidebar:</strong>
		</div><!-- .sidebar#sideLeft -->

		<div class="sidebar" id="sideRight">
			<strong>Right Sidebar:</strong>
		</div><!-- .sidebar#sideRight -->

	</div><!-- #middle-->

</div><!-- #wrapper -->

<div id="footer">
	<strong>Footer:</strong> 
</div><!-- #footer -->
</body>
</html>