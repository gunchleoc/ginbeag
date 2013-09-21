{HEADER}
<form name="site" action="{ACTIONVARS}" method="post">
	<table>
  		<tr>
    		<td class="bodyline">
      			<table cellpadding="5"
         			<tr>
          				<th class="thHead" colspan="2">Variable Names</th>
       			 	</tr>
        			<tr>
	          			<td class="table" valign="top"><span class="gen">Rename Variables</span>
            				<br><span class="gensmall">Creates new random variable names to confuse spammers</span>
          				</td>
          				<td class="table" valign="top">
            				<input type="submit" name="renamevariables" value="Rename Variables" class="liteoption" />
          				</td>
        			</tr>
        			<tr><td class="spacer" colspan="2"></td></tr>

         			<tr>
          				<th class="thHead" colspan="2">Contact Form</th>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">Use Math CAPTCHA</span>
            				<br><span class="gensmall">Spam protection - people have to solve a simple addition problem on random before sending an e-mail</span>
          				</td>
          				<td class="table" valign="top"><span class="gen">
            				<input type="radio" name="usemathcaptcha" value="1"{USEMATHCAPTCHA}>Yes
            				<input type="radio" name="usemathcaptcha" value="0"{NOT_USEMATHCAPTCHA}>No</span>
          				</td>
        			</tr>
        			<tr><td class="spacer" colspan="2"></td></tr>
       				<tr>
		      			<td colspan="2" align="center">
            				<input type="submit" name="submit" value="Submit" class="mainoption" />
            				&nbsp;&nbsp;
            				<input type="reset" value="Reset" class="liteoption" />
            				&nbsp;&nbsp;
            				{CANCELBUTTON}
		      			</td>
        			</tr>
      			</table>
    		</td>
  		</tr>
	</table>
</form>
{FOOTER}
