<script type="text/javascript">
<!--
function assignSelectValue(selectbox, textfield) {
  cat=selectbox.options[selectbox.selectedIndex].text;
  textfield.value=cat.substring(cat.lastIndexOf(String.fromCharCode(160))+1);
}
// -->
</script>
<form name="editcategoryform" action="{ACTIONVARS}" method="post" accept-charset="UTF-8">
	<div class="highlight" style="float:left;padding-right:2em;">{CATEGORYSELECTION}</div>
	<div  style="float:left;">
		<fieldset>
			<legend class="highlight">Add a category below the selected category</legend>
			<input type="text" name="addsubtext" value="{ADDSUBTEXT}" size="30" />
			<input type="submit" name="addsub" value="Add Subcategory" />
			<div class="formexplain">Do not add upload dates as categories; a search by date is implemented into the software.</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Rename the selected category</legend>
			<input type="text" name="editcattext" value="{EDITCATTEXT}" size="30" />
			<input type="submit" name="editcat" value="Rename selected" />
			<div class="formexplain">If you rename a category, its articles, newsitems and images will still be assigned to it.</div>
		</fieldset>
		<fieldset>
			<legend class="highlight">Delete the selected category</legend>
			<input type="submit" name="delcat" value="Delete selected" />
			{DELETECONFIRM}
			<div class="formexplain">You can only delete a category if it has no subcategories.</div>
		</fieldset>
	</div>
</form>
<div class="newline"></div>
