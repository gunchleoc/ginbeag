<hr>
<div align="right" class="medtext"><a href="#top">{L_TOPPAGE}</a></div>
<span class="highlight">{L_NAME}</span><b>{NAME}</b>
<br /><span class="highlight">{L_EMAIL}</span><a href="mailto:{EMAIL}">{EMAIL}</a>
<br /><span class="highlight">{L_DATE}</span>{DATE}
<br /><span class="highlight">{L_SUBJECT}</span><b>{SUBJECT}</b>
<br />{MESSAGE}

<!-- BEGIN switch DELETEFORM -->
<form name="deleteform" action="{DELETEACTIONVARS}" method="post">
	<input type="hidden" name="messageid" value="{MESSAGEID}" />
	<input type="submit" name="deleteentry" value="Delete this entry" />
</form>
<!-- END switch DELETEFORM -->