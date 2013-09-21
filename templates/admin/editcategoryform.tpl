<script type="text/javascript">
<!--
function assignSelectValue(selectbox, textfield) {
  cat=selectbox.options[selectbox.selectedIndex].text;
  textfield.value=cat.substring(cat.lastIndexOf(String.fromCharCode(160))+1);
}
// -->
</script>
<form name="editcategoryform" action="editcategories.php?sid={SID}&action=editcat" method="post">
  <table width="500" border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td class="gensmall">
        Do not add dates as categories, a search by date is implemented into the software.
      </td>
    </tr>
    <tr><td class="spacer" colspan="7"></td></tr>
  	<tr>
	    <td>
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td  valign="top" rowspan="3" align="left">
              {CATEGORYSELECTION}
  					</td>
            <td width="10">&nbsp;</td>
		        <td class="table" width="*">
              <input type="text" name="addsubtext" value="{ADDSUBTEXT}" size="30" />
              <br />
              <input type="submit" name="addsub" value="Add Subcategory" class="liteoption" />
              <br />
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td class="table">
              <input type="text" name="editcattext" value="{EDITCATTEXT}" size="30" />
              <br />
              <input type="submit" name="editcat" value="Rename selected" class="liteoption" />
              <br />
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td class="table">
             <input type="submit" name="delcat" value="Delete selected" />
             <br />
             <input type="checkbox" name="delcat" value="Delete selected" class="gen" />
             <span class="gen">Confirm delete</span>
            </td>
          </tr>
		    </table>
  		</td>
    </tr>
  </table>
</form>
