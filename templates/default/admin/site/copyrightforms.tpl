<h1 class="headerpagetitle">Copyright Permissions</h1>
<form name="searchform" action="{SEARCHFORMPARAMS}" method="post">
	<input type="text" name="holder" maxlength="255" value="{SEARCHTEXT}" />
	<input type="submit" name="search" value="Search Holder" class="mainoption" />
	<input type="submit" name="cancel" value="Clear Search" />
</form>

<form name="orderform" action="{ORDERFORMPARAMS}" method="post">

	<select name="filterpermission" size="1">
		<option value="10000"<!-- BEGIN switch PERMISSIONS --> selected<!-- END switch PERMISSIONS -->>
			-- Permissions --
		</option>
		<option value="{PERMMISSION_GRANTED}"<!-- BEGIN switch GRANTED --> selected<!-- END switch GRANTED -->>
			Granted
		</option>
		<option value="{NO_PERMISSION}"<!-- BEGIN switch NOPERMISSION --> selected<!-- END switch NOPERMISSION -->>
			No Permission
		</option>
		<option value="{PERMISSION_REFUSED}"<!-- BEGIN switch REFUSED --> selected<!-- END switch REFUSED -->>
			Refused
		</option>
		<option value="{PERMISSION_IMAGESONLY}"<!-- BEGIN switch IMAGESONLY --> selected<!-- END switch IMAGESONLY -->>
			Images Only
		</option>
		<option value="{PERMISSION_LINKIMAGESONLY}"<!-- BEGIN switch LINKIMAGESONLY --> selected<!-- END switch LINKIMAGESONLY -->>
			Links and Images Only
		</option>
		<option value="{PERMISSION_LINKONLY}"<!-- BEGIN switch LINKONLY --> selected<!-- END switch LINKONLY -->>
			Links Only
		</option>
		<option value="{PERMISSION_NOREPLY}"<!-- BEGIN switch NOREPLY --> selected<!-- END switch NOREPLY -->>
			No Reply
		</option>
		<option value="{PERMISSION_PENDING}"<!-- BEGIN switch PENDING --> selected<!-- END switch PENDING -->>
			Pending
		</option>
	</select>
	
	<select name="order" size="1">
		<option value="copyright_id" <!-- BEGIN switch ID --> selected<!-- END switch ID -->>
			-- Order --
		</option>
		<option value="holder" <!-- BEGIN switch HOLDER --> selected<!-- END switch HOLDER -->>
			Copyright Holder
		</option>
		<option value="contact" <!-- BEGIN switch CONTACT --> selected<!-- END switch CONTACT -->>
			Contact Information
		</option>
		<option value="comments" <!-- BEGIN switch COMMENTS --> selected<!-- END switch COMMENTS -->>
			Comments/Restrictions
		</option>
		<option value="permission" <!-- BEGIN switch PERMISSION --> selected<!-- END switch PERMISSION -->>
			Permission
		</option>
		<option value="credit" <!-- BEGIN switch CREDIT --> selected<!-- END switch CREDIT -->>
			Preferred Credit
		</option>
		<option value="editor_id" <!-- BEGIN switch EDITOR --> selected<!-- END switch EDITOR -->>
			Responsible
		</option>
		<option value="added" <!-- BEGIN switch DATEADDED --> selected<!-- END switch DATEADDED -->>
			Date Added
		</option>
		<option value="editdate" <!-- BEGIN switch EDIDATE --> selected<!-- END switch EDIDATE -->>
			Last Update
		</option>
	</select>
	<select name="ascdesc" size="1">
		<option value="asc" <!-- BEGIN switch ASC --> selected<!-- END switch ASC -->>
			Ascending
		</option>
		<option value="desc" <!-- BEGIN switch DESC --> selected<!-- END switch DESC -->>
			Descending
		</option>
	</select>
	<input type="submit" name="search" value="Go" class="mainoption" />
	<br />
	<div align="right">{PAGEMENU}</div>
</form>
<hr>
<div align="right">{PAGEMENU}</div>
<form name="addform" action="{ACTIONVARS}" method="post">
	<input type="submit" name="addcopyrightform" value="Add Copyright Holder" class="mainoption" />
</form>

<table cellpadding="5">
	<caption>Copyright Holders</caption>
	<tr>
		<th>ID</th>
		<th>Copyright Holder</th>
		<th>Contact Information</th>
		<th>Comments/ Restrictions</th>
		<th>Perm.</th>
		<th>Preferred Credit</th>
		<th><font size="-2">Responsible/Added/ Last&nbsp;Update</font></th>
		<th>Edit</th>
	</tr>
	{ENTRIES}
</table>
<br />
<form name="addform" action="{ACTIONVARS}" method="post">
  	<input type="submit" name="addcopyrightform" value="Add Copyright Holder" class="mainoption" />
</form>
{EXPLANATION}