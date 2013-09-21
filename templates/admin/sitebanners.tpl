{HEADER}
<hr>
<p class="pagetitle">Display Banners</p>

<form name="displaybannersform" action="{DISPLAYACTIONVARS}" method="post">
	<span class="gen">
		<input type="radio" name="toggledisplaybanners" value="1" class="gen"{DISPLAYBANNERS}>
		Yes
		<input type="radio" name="toggledisplaybanners" value="0" class="gen"{NOT_DISPLAYBANNERS}>
		No
	</span>
	&nbsp;&nbsp;&nbsp;<input type="submit" name="displaybanners" value="Submit" class="liteoption" />
</form>
<hr>
<p class="pagetitle">Edit Banners</p>
{EDITFORM}
<!-- add banner /-->
<hr>
<p class="pagetitle">Add Banner</p>
<form name="addbanner" enctype="multipart/form-data" action="{ADDACTIONVARS}" method="post">
	<table>
		<tr>
			<td class="bodyline">
				<table>
					<tr>
						<th class="thHead" colspan="2">Add Banner</th>
					</tr>
					<tr>
  						<td class="gen" valign="top">Header (optional):</td>
  						<td class="table" valign="top">
    						<input type="text" name="header" size="50" maxlength="255" value="" />
  						</td>
					</tr>
					<tr>
						<td colspan="2" class="gensmall">
							Specify either image, description and link, or enter the banner code manually.
						</td>
					</tr>

					<tr>
						<td colspan="2">
							<table width="100%">
								<tr>
									<td class="bodyline">
										<table width="100%">
											<tr>
  												<td class="gen" valign="top">Image:</td>
  												<td class="table" valign="top">
      												<input type="file" name="image" size="40" maxlength="255" />
  												</td>
											</tr>
											<tr>
  												<td class="gen" valign="top">Description:</td>
  												<td class="table" valign="top">
    												<input type="text" name="description" size="50" maxlength="255" value="" />
  												</td>
											</tr>
											<tr>
  												<td class="gen" valign="top">Link:</td>
  												<td class="table" valign="top">
    												<input type="text" name="link" size="50" maxlength="255" value="" />
  												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<table width="100%">
								<tr>
									<td class="bodyline">
										<table width="100%">
											<tr>
  												<td class="gen" valign="top">Code (HTML):</td>
  												<td class="table" valign="top">
    												<textarea name="code" cols="50" rows="5" class="gen"></textarea>
  												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
  						<td class="gen" valign="top">&nbsp;</td>
  						<td class="table" valign="top">
							<p>
								<input type="submit" name="addbanner" value="Submit" class="mainoption">
								&nbsp;&nbsp;<input type="reset" value="Reset" class="liteoption">
							</p>
  						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
