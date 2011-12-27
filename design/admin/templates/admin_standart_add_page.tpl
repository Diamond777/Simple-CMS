{* Smarty *}

<center>
<h4>{$heading_page}</h4>
<div id="message">{$message}</div>
<form id="addForm" method="post" action="{$form_action}" enctype="multipart/form-data" onsubmit="if(!check())return false;">

<div style="width:700px; text-align:left;" class="tab-page" id="modules-cpanel">
<script type="text/javascript">
	var tabPane_{$REQUEST.1}_{$REQUEST.2}=new WebFXTabPane(document.getElementById("modules-cpanel"),0);
	var tabPane=tabPane_{$REQUEST.1}_{$REQUEST.2};
</script>

{$content}

</div>

</form>
</center>