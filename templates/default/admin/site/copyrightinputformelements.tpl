<tr>
	<th>Description</th>
    <th>Value</th>
</tr>

<tr>
    <td>Copyright Holder</td>
    <td align="left">
		<input type="text" name="holder" size="51" maxlength="255" value="{HOLDER}" />
    </td>
</tr>

<tr>
    <td>Contact Information</td>
    <td align="left">
		<textarea name="contact" rows="6" cols="50">{CONTACT}</textarea>
    </td>
</tr>

<tr>
    <td>Comments/Restrictions</td>
    <td align="left">
		<textarea name="comments" rows="6" cols="50">{COMMENTS}</textarea>
    </td>
</tr>
  
<tr>
    <td>Preferred Credit</td>
    <td align="left">
		<input type="text" name="credit" size="51" maxlength="255" value="{CREDIT}" />
    </td>
</tr>

<tr>
    <td>Permission</td>
    <td align="left">
		<br />
		<input type="radio" name="permission" value="{PERMISSION_GRANTED}" <!-- BEGIN switch GRANTED --> checked<!-- END switch GRANTED -->>
		Permission granted
		<br />
		<input type="radio" name="permission" value="{PERMISSION_IMAGESONLY}" <!-- BEGIN switch IMAGESONLY --> checked<!-- END switch IMAGESONLY -->>
		Permission for images only
		<br />
		<input type="radio" name="permission" value="{PERMISSION_LINKIMAGESONLY}" <!-- BEGIN switch LINKIMAGESONLY --> checked<!-- END switch LINKIMAGESONLY -->>
		Permission for images and links only
		<br />
		<input type="radio" name="permission" value="{PERMISSION_LINKONLY}" <!-- BEGIN switch LINKONLY --> checked<!-- END switch LINKONLY -->>
		Permission for links only
		<br />
		<input type="radio" name="permission" value="{PERMISSION_REFUSED}" <!-- BEGIN switch REFUSED --> checked<!-- END switch REFUSED -->>
		Permission Refused
		<br />
		<input type="radio" name="permission" value="{PERMISSION_NOREPLY}" <!-- BEGIN switch NOREPLY --> checked<!-- END switch NOREPLY -->>
		No Reply
		<br />
		<input type="radio" name="permission" value="{PERMISSION_PENDING}" <!-- BEGIN switch PENDING --> checked<!-- END switch PENDING -->>
		Permission Pending
		<br />
		<input type="radio" name="permission" value="{NO_PERMISSION}" <!-- BEGIN switch NOPERMISSION --> checked<!-- END switch NOPERMISSION -->>
		No Permission
		<br />&nbsp;
    </td>
</tr>
<tr><td colspan="2" class="spacer"></td></tr>