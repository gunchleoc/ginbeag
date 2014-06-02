<h1 class="headerpagetitle">Edit Page {ARTICLEPAGE} of Article</h1>
{NAVIGATIONBUTTONS}
<div align="right">{PAGEMENU}</div>
<div class="contentheader">Add section or page</div>
<div class="contentsection">
	<div class="leftalign">
		<form name="addarticlepageform" action="{ACTIONVARS}" method="post">
			<input type="submit" name="addarticlepage" value="Add a Page after this Page" class="mainoption" />
		</form>
	</div>
	<div class="leftalign">
		<form name="articlepages" action="{ACTIONVARS}" method="post">
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        	<input type="submit" name="addarticlesection" value="Add a Section on the bottom of this Page"/>
      	</form>
	</div>
	<!-- BEGIN switch DELETEPAGE -->
	<div class="rightalign">
		<form action="{ACTIONVARS}" method="post">
		  <input type="submit" name="deletelastarticlepage" value="{DELETEPAGE}">
		</form>
	</div>
	<!-- END switch DELETEPAGE -->
	<div class="newline"></div>
</div>
<!-- BEGIN switch ARTICLESECTIONFORM -->
{ARTICLESECTIONFORM}
<!-- END switch ARTICLESECTIONFORM -->
<div class="contentheader">New Section</div>
<div class="contentsection">

      <form name="articlepages" action="{ACTIONVARS}" method="post">
        <input type="submit" name="addarticlesection" value="Add Section" class="mainoption" />
      </form>
</div>
<div align="right">{PAGEMENU}</div>
{NAVIGATIONBUTTONS}
