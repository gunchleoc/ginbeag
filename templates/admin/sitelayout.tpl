{HEADER}
<form name="site" action="{ACTIONVARS}" method="post">
	<table>
  		<tr>
    		<td class="bodyline">
      			<table cellpadding="5"
        			<tr>
          				<th class="thHead" colspan="2"  width="100%">Site Header Elements</th>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">Site Name</span>
          					<br><span class="gensmall">The Site Name will be displayed on all pages.</span>
          				</td>
          				<td class="table" valign="top"><input type="text" name="sitename" size="50" maxlength="255" value="{SITENAME}" /></td>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">Site Description</span>
          					<br><span class="gensmall">Smaller text that goes underneath the Site Name.</span>
          				</td>
          				<td class="table" valign="top"><input type="text" name="sitedescription" size="50" maxlength="255" value="{SITEDESCRIPTION}" /></td>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">Left Header Image</span>
            				<br><span class="gensmall">Please use your FTP program to upload the image to <i>{UPLOADPATH}</i>.</span>
          				</td>
          				<td class="table" valign="top"><input type="text" name="leftimage" size="50" maxlength="255" value="{LEFTIMAGE}" /></td>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">Left Header Link</span>
            				<br><span class="gensmall">e.g. <i>index.php</i> for the splash page.</span>
          				</td>
          				<td class="table" valign="top"><input type="text" name="leftlink" size="50" maxlength="255" value="{LEFTLINK}" /></td>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">Right Header Image</span>
            				<br><span class="gensmall">Please use your FTP program to upload the image to <i>{UPLOADPATH}</i>.</span>
          				</td>
          				<td class="table" valign="top"><input type="text" name="rightimage" size="50" maxlength="255" value="{RIGHTIMAGE}" /></td>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">Right Header Link</span>
            				<br><span class="gensmall">e.g. <i>index.php</i> for the splash page.</span>
          				</td>
          				<td class="table" valign="top"><input type="text" name="rightlink" size="50" maxlength="255" value="{RIGHTLINK}" /></td>
        			</tr>
        			<tr><td class="spacer" colspan="2"></td></tr>
			        <tr>
          				<th class="thHead" colspan="2"  width="100%">Site Footer Elements</th>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">Footer Message</span>
          					<br><span class="gensmall">The Footer Message will be displayed on all pages.</span>
          				</td>
          				<td class="table" valign="top"><input type="text" name="footermessage" size="50" maxlength="255" value="{FOOTERMESSAGE}" />
          					<br /><span class="gensmall">{FOOTERMESSAGEDISPLAY}</span>
          				</td>
        			</tr>
        			<tr><td class="spacer" colspan="2"></td></tr>
			        <tr>
          				<th class="thHead" colspan="2">Page Setup</th>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">Links Per Page</span></td>
          				<td class="table" valign="top"><input type="text" name="linksperpage" size="5" maxlength="255" value="{LINKSPERPAGE}" /></td>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">News Items Per Page</span></td>
          				<td class="table" valign="top"><input type="text" name="newsperpage" size="5" maxlength="255" value="{NEWSPERPAGE}" /></td>
        			</tr>
        			<tr><td class="spacer" colspan="2"></td></tr>
			        <tr>
          				<th class="thHead" colspan="2">Splash Page Links</th>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">Links on Splash Page:</span></td>
          				<td class="table" valign="top">
          					<span class="table">{LINKSONSPLASHPAGEDISPLAY}</span>
          				</td>
        			</tr>
        			<tr>
          				<td class="table" colspan="2" align="center">
				            <!-- <select name="linksonsplashpage[]" size="10" multiple> //-->
								{LINKSONSPLASHPAGE}
            				<!-- </select> //-->
          				</td>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">Show All Links on Splash Page</span>
            				<br><span class="gensmall">Toggles if all main pages are automatically displayed on the Splash Page, or if the above selection is used.</span>
          				</td>
          				<td class="table" valign="top"><span class="gen">
            				<input type="radio" name="alllinksonsplashpage" value="1" {ALLLINKSONSPLASH}>Show All
            				<input type="radio" name="alllinksonsplashpage" value="0" {NOT_ALLLINKSONSPLASH}>Show the above selection only</span>
          				</td>
        			</tr>
			        <tr>
          				<th class="thHead" colspan="2">Splash Page Layout</th>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">Display Site Description on Splash Page</span>
            				<br><span class="gensmall">Toggles if the Site Description will be displayed on the Splash Page.</span>
          				</td>
          				<td class="table" valign="top"><span class="gen">
            				<input type="radio" name="showsd" value="1" {SITEDESCRIPTIONONSPLASH}>Yes
            				<input type="radio" name="showsd" value="0" {NOT_SITEDESCRIPTIONONSPLASH}>No</span>
          				</td>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">Splash Page Font</span>
            				<br><span class="gensmall">Font style for the text entered below.</span>
          				</td>
          				<td class="table" valign="top"><span class="gen">
            				<input type="radio" name="spfont" value="normal" {FONTNORMAL}>Normal
            				<input type="radio" name="spfont" value="italic" {FONTITALIC}>Italic
            				<input type="radio" name="spfont" value="bold" {FONTBOLD}>Bold</span>
          				</td>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">Splash Page Text 1</span>
            				<br><span class="gensmall">This text will appear above the image.</span>
          				</td>
          				<td class="table" valign="top">
            				<textarea name="sptext1" rows="10" cols="50">{SPLASHTEXT1}</textarea>
          				</td>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">Splash Page Image</span>
            				<br><span class="gensmall">Optional image to be centered below the navigator. Please use your FTP program to upload the image to <i>{UPLOADPATH}</i>.</span>
          				</td>
          				<td class="table" valign="top"><input type="text" name="spimage" size="50" maxlength="255" value="{SPLASHIMAGE}" /></td>
        			</tr>
        			<tr>
          				<td class="table" valign="top"><span class="gen">Splash Page Text 2</span>
            				<br><span class="gensmall">This text will appear below the image.</span>
          				</td>
          				<td class="table" valign="top">
            				<textarea name="sptext2" rows="10" cols="50">{SPLASHTEXT2}</textarea>
          				</td>
        			</tr>
        			<tr>
          				<td class="table" colspan="2" valign="top">
            				<a href="../.." target="_blank" class="gen">View Splash Page</a>
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