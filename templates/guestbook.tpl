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
		<table border="0" cellpadding="10" cellspacing="0" width="100%"> 

			<tr>
				<td>
				
					<p class="pagetitle">{TITLE}</p>
				
				
					<!-- BEGIN switch DISABLED -->
    				<p class="highlight">{DISABLED}</p>
					<!-- END switch DISABLED -->
<!-- BEGIN switch ENABLED -->
				
					<!-- BEGIN switch MESSAGE -->
    				<p class="highlight">{MESSAGE}</p>
					<!-- END switch MESSAGE -->
				
					<!-- BEGIN switch ERROR -->
    				<!-- desired <div class="highlight">{ ERROR}</div> //-->
    				{ERROR}
					<!-- END switch ERROR -->

					<!-- BEGIN switch EMAILERROR -->
    				<p class="gen" align="left">{ERREMAILERROROR}</p>
    				<p>{L_TRYAGAIN}<p>
					<!-- END switch EMAILERROR -->
				
					<!-- BEGIN switch EMAILINFO -->
    				{EMAILINFO}
					<!-- END switch EMAILINFO -->				
				
					<!-- BEGIN switch POST -->
					{POST}				
					<!-- END switch POST -->

					<!-- BEGIN switch POSTADDED -->
    				<p class="highlight">{POSTADDED}</p>
    			
					<form name="postform" method="post">
						<input type="submit" value="OK" class="mainoption">
					</form>    			
					<!-- END switch POSTADDED -->
				
					<!-- BEGIN switch RETURN -->
    				<form name="returnform" method="get">
						<input type="submit" value="{RETURN}" class="mainoption">
					</form>   			
					<!-- END switch RETURN -->
				
				
					<!-- BEGIN switch LEAVEMESSAGE -->
					<form name="postform" method="post">
						<input type="submit" name="post" value="{LEAVEMESSAGE}" class="mainoption">
					</form>			
					<!-- END switch LEAVEMESSAGE -->					

					<!-- BEGIN switch GUESTBOOKFORM -->
					<!--<p class="pagetitle">{GUESTBOOKFORMMESSAGE}</p> toto do I need this?//-->
    				{GUESTBOOKFORM}
					<!-- END switch GUESTBOOKFORM -->

					<!-- BEGIN switch GUESTBOOKEMAILFORM -->
    				{GUESTBOOKEMAILFORM}
					<!-- END switch GUESTBOOKEMAILFORM -->
				
					<!-- BEGIN switch ENTRIES -->
    				{ENTRIES}
					<!-- END switch ENTRIES -->
<!-- END switch ENABLED -->					
				</td>
			</tr>
		</table>
	</td>
</tr>
{FOOTER}
