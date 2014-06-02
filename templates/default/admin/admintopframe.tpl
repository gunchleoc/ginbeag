<div class="leftalign admintoppageinfo">
	<!-- BEGIN switch LOGOUTLINK -->
	<!-- BEGIN switch PAGE -->
	Page #{PAGE} &nbsp; — &nbsp;  <span class="highlight">{PAGETITLE}</span><!-- BEGIN switch PUBLISHED --> &nbsp;{PUBLISHED}<!-- END switch PUBLISHED -->
	<!-- END switch PAGE -->
	<!-- END switch LOGOUTLINK -->
</div>
<div class="rightalign">
	<h1 class="maintitle">{SITENAME} Admin Panel</h1>
</div>
<div class="newline">
	<!-- BEGIN switch LOGINLINK -->
	<div class="leftalign highlight">Access restricted — please use one of the links to the right to register or log in.</div>
	<div class="rightalign">{REGISTERLINK} &nbsp; — &nbsp; {LOGINLINK}</div>
	<!-- END switch LOGINLINK -->
	<!-- BEGIN switch LOGOUTLINK -->
	<table  style="width:100%!important; margin:0em!important"><tr><td class="admintoplinks" width="50%">

		| Pages |
		<!-- BEGIN switch SHOWEDITLINKS -->
			&nbsp; {NEWPAGELINK} &nbsp; — &nbsp;
			<!-- BEGIN switch EDITPAGELINK -->
			{EDITPAGELINK} &nbsp; — &nbsp;
			<!-- END switch EDITPAGELINK -->
			{PREVIEWPAGELINK}
			<!-- BEGIN switch DELETEPAGELINK -->
			&nbsp; — &nbsp; {DELETEPAGELINK}
			<!-- END switch DELETEPAGELINK -->
			<!-- BEGIN switch PUBLISHLINK -->
	   		&nbsp; — &nbsp; {PUBLISHLINK}
	   		<!-- END switch PUBLISHLINK -->
			<!-- BEGIN switch DONELINK -->
	   		&nbsp; — &nbsp; {DONELINK}
	   		<!-- END switch DONELINK -->
	   	<!-- END switch SHOWEDITLINKS -->
	   	<!-- BEGIN switch SHOWSITELINKS -->
	   	&nbsp; {RETURNPAGEEDITINGLINK}
	   	<!-- END switch SHOWSITELINKS -->
	</td>
	<td class="admintoplinks" width="25%">
		| Global | &nbsp; {IMAGESLINK} &nbsp; — &nbsp; {CATEGORIESLINK} &nbsp; — &nbsp; {SITEADMINLINK}
	</td>
	<td class="admintoplinks" width="25%">
		| Account | &nbsp; {PROFILELINK} &nbsp; — &nbsp; {LOGOUTLINK}
	</td>
	</tr></table>
	<!-- END switch LOGOUTLINK -->

</div>
<!-- BEGIN switch ONLINEUSERS -->
<div class="footer rightalign">Online webpage editors: {ONLINEUSERS}</div>
<!-- END switch ONLINEUSERS -->
