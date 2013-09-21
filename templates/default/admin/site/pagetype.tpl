<form action="{ACTIONVARS}" method="post">
<tr>
<input type="hidden" name="pagetype" value="{PAGETYPE}"></td>
  <td>{PAGETYPE}</td>
  <td><i>{DESCRIPTION}</i></td>
  <td align="center"><input type="checkbox" name="allowroot" <!-- BEGIN switch ALLOWROOT --> checked<!-- END switch ALLOWROOT -->></td>
  <td align="center"><input type="checkbox" name="allowsimplemenu" <!-- BEGIN switch ALLOWSIMPLEMENU --> checked<!-- END switch ALLOWSIMPLEMENU -->></td>
  <td align="center"><!-- BEGIN switch ALLOWSELF --> yes<!-- END switch ALLOWSELF --></td>
  <td class="table"><input type="submit" name="pagetypesettings" value="Save Changes" /></td>
</tr>
</form>