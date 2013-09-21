<h1 class="headerpagetitle">Banners</h1>
<form name="displaybannersform" action="{DISPLAYACTIONVARS}" method="post">
{DISPLAYHIDDENVARS}
	<div class="contentheader">General Banner Settings</div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Display banners?</legend>	
			<input type="radio" name="toggledisplaybanners" value="1" {DISPLAYBANNERS}>
				Yes
				<input type="radio" name="toggledisplaybanners" value="0" {NOT_DISPLAYBANNERS}>
				No
			<div class="formexplain">Turn all banners on or off.</div>
		</fieldset>
		<input type="submit" name="displaybanners" value="Submit" />
	</div>
</form>

<div class="contentheader">Edit Banners</div>
<div class="contentsection">
	{EDITFORM}
</div>

<form name="addbanner" enctype="multipart/form-data" action="{ADDACTIONVARS}" method="post">
	<div class="contentheader">Add Banner</div>
	<div class="contentsection">
		<fieldset>
			<legend class="highlight">Header (optional)</legend>	
			<input type="text" name="header" size="50" maxlength="255" value="" />
			<div class="formexplain">Optional title for the banner.</div>
		</fieldset>
		<span class="highlight">- For the content, specify either image, description and link, or enter the banner code manually. -</span>
		<fieldset>
			<legend class="highlight">Banner Content</legend>	
			<label for="image">Image:</label>
			<input id="image" type="file" name="image" size="40" maxlength="255" />
			<br /><label for="description">Description:</label>
			<input id="description" type="text" name="description" size="50" maxlength="255" value="" />
			<br /><label for="link">Link:</label>
			<input id="link" type="text" name="link" size="50" maxlength="255" value="" />
			<div class="formexplain">Specify image, description and link. If you use this, leave the Banner Code blank.</div>
		</fieldset>
		<span class="highlight">- or -</span>
		<fieldset>
			<legend class="highlight">Banner Code</legend>	
			<label for="code">Code (HTML):</label>
			<textarea id="code" name="code" cols="50" rows="5"></textarea>
			<div class="formexplain">Enter the banner code manually. If you use this, leave Image, Description and Link blank.</div>
		</fieldset>
		{SUBMITROW}
	</div>
</form>