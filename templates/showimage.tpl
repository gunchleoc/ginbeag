{HEADER}
<tr>
  	<td valign="top" width="20%">
    	{NAVIGATOR}
		<!-- BEGIN switch BANNERS -->
    	{BANNERS}
		<!-- END switch BANNERS -->
  	</td>
  	<td>&nbsp;</td>
  	<td valign="top" class="gen" width="*">
  
		{PAGEINTRO}
		<table border="0" cellpadding="10" cellspacing="0" width="100%"> 
			<!-- BEGIN switch RETURNPAGE --> 
			<tr>
  				<td align="left" valign="bottom"></td>
  				<td align="center">
  					<a href="{RETURNPAGE}" class="gen">{RETURNPAGETITLE}
    			</td>
  				<td align="right" valign="bottom"></td>
			</tr>
			<!-- END switch RETURNPAGE --> 
  			<tr>
				<td align="left" valign="top">
					<!-- BEGIN switch PREVIOUS -->
					<form name="prevform" action="?item={PREVIOUSITEM}&page={PAGE}&sid={SID}" method="post">
						{PREVIOUS}
						<input type="submit" name="prev" value="<" class="mainoption">
					</form>
					<!-- END switch PREVIOUS -->	
					&nbsp;&nbsp;
				</td>
  				<td align="center">
					<!-- BEGIN switch IMAGEPATH -->
  					<img src="{IMAGEPATH}" alt="{SIMPLECAPTION}" />
  					{CAPTION}
					<!-- END switch IMAGEPATH -->	
					<!-- BEGIN switch NOIMAGE -->
   					<span class="highlight"><i>{NOIMAGE}</i></span>
					<!-- END switch NOIMAGE -->	
  				</td>
  				<td align="right" valign="top">
					<!-- BEGIN switch NEXT -->
					<form name="nextform" action="?item={NEXTITEM}&page={PAGE}&sid={SID}" method="post">
						{NEXT}
						<input type="submit" name="next" value=">" class="mainoption">
					</form>
					<!-- END switch NEXT -->  	
					&nbsp;&nbsp;  	
				</td>
			</tr>
			<!-- BEGIN switch RETURNPAGE --> 
			<tr>
  				<td align="left" valign="bottom"></td>
  				<td align="center">
  					<a href="{RETURNPAGE}" class="gen">{RETURNPAGETITLE}
    			</td>
  				<td align="right" valign="bottom"></td>
			</tr>
			<!-- END switch RETURNPAGE --> 
		</table>
		<hr>
      	{EDITDATA}
	</td>
</tr>
{FOOTER}
