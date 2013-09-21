<hr>
<p class="sectiontitle">Banner #{ID}</p>
{BANNER}
			
<!-- BEGIN switch INCOMPLETE -->
<p class="highlight">This banner is not complete and will not be displayed! Please fill out all required fields.</p>
<!-- END switch INCOMPLETE -->

<form name="bannerproperties" enctype="multipart/form-data" action="{BANNERACTIONVARS}" method="post">
	<table>
		<tr>
			<td class="bodyline">
				<table>
					<tr>
						<th class="thHead" colspan="2">
							{HEADER}
						</th>
					</tr>
					<tr>
  						<td class="gen" valign="top">Header (optional):</td>
  						<td class="table" valign="top">
    						<input type="text" name="header" size="50" maxlength="255" value="{HEADER}" />
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
<!-- BEGIN switch IMAGE -->
    												<input type="hidden" name="oldimage" value="{IMAGE}" />
    												<span class="gen">{IMAGE}</span>
    												<br />
<!-- END switch IMAGE -->
													<input type="file" name="image" size="40" maxlength="255" />
  												</td>
											</tr>
											<tr>
  												<td class="gen" valign="top">Description:</td>
  												<td class="table" valign="top">
    												<input type="text" name="description" size="50" maxlength="255" value="{DESCRIPTION}" />
  												</td>
											</tr>
											<tr>
  												<td class="gen" valign="top">Link:</td>
  												<td class="table" valign="top">
    												<input type="text" name="link" size="50" maxlength="255" value="{LINK}" />
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
    												<textarea name="code" cols="50" rows="5" class="gen">{CODE}</textarea>
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
								<input type="submit" name="bannerproperties" value="Submit" class="mainoption">
								&nbsp;&nbsp;<input type="reset" value="Reset" class="liteoption">
							</p>
  						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<form name="movebanner" action="{MOVEACTIONVARS}" method="post">
	<p>
		<input type="submit" name="movebannerup" value="move banner up" class="liteoption" />
		&nbsp;&nbsp;&nbsp;<input type="text" name="positions" size="2" maxlength="3" value="1" />
		&nbsp;&nbsp;&nbsp;<input type="submit" name="movebannerdown" value="move banner down" class="liteoption" />
	</p>
</form>
<form name="deletebanner" action="{DELETEACTIONVARS}" method="post">
	<p>
  		<input type="submit" name="deletebanner" value="Delete this banner" class="liteoption" />
  		<input type="checkbox" name="deletebannerconfirm" value="Confirm delete" class="gen" />
  		<span class="gen">Confirm delete</span>
  	</p>
</form>