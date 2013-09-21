<table width ="100%">
	<tr>
    	<td class="table">
      		<a href="?sid={SID}&page={PAGE}&offset={OFFSET}" class="gen">Edit this newsitem</a>
      		<p class="sectiontitle">{SECTIONTITLE}</p>
      		<div class="gen"><b>{SYNOPSIS}</b><br />...<br />...<br />...</div>
      
     		<!-- BEGIN switch LOCATION -->
    		<div>
      			<span class="articlelocation">Location: {LOCATION}</span>
    		</div>
    		<!-- END switch LOCATION -->      

    		<span class="articlesource">
    			Source: <!-- BEGIN switch SOURCELINK -->
    			<a href="{SOURCELINK}" target="_blank">
    			<!-- END switch SOURCELINK -->
    			{SOURCE}
    			<!-- BEGIN switch SOURCELINK -->
    			</a>
    			<!-- END switch SOURCELINK -->
    		</span>

    		<!-- BEGIN switch CONTRIBUTOR -->
    		<span class="articlesource">Found by: {CONTRIBUTOR}</span>
    		<!-- END switch CONTRIBUTOR -->

      		<p class="articledate">{DATE}<br />Added by {EDITOR}</p>
      		<p class="footer">{COPYRIGHT}</p>
    	</tr>
  	</td>
</table>
<hr>
