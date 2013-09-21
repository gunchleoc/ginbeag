<form name="movecat" action="?sid={SID}&action=movecat" method="post">
  <table width="500" border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td class="gensmall">
        Move Category.
      </td>
    </tr>
    <tr><td class="spacer" colspan="7"></td></tr>
  	<tr>
	    <td class="gen">
		    <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="table" valign="top">
              <span class="gen">Select a category:</span><br />
              {FROMFORM}
            </td>
            <td class="table" valign="center">
              <span class="highlight">&nbsp;>>&nbsp;</span>
            </td>
            <td class="table" valign="top">
              <span class="gen">Select destination:</span><br />
              {TOFORM}
            </td>
          </tr>
          <tr>
            <td class="table" align="right" colspan="3">&nbsp;<br />
              <input type="submit" name="movecat" value="Move" />
            </td>
          </tr>
		   </table>
  		</td>
    </tr>
  </table>
</form>
