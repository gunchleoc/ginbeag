<div class="contentoutline" style="width:60em;text-align:center;">
		<div class="contentheader">{TITLE}</div>
		<div class="contentsection">
			<div class="editorcodebuttonrow">
				<div class="leftalign editorcodebutton"><input id="{JSID}bold" type="button" class="button" accesskey="b" name="bold" value=" B " style="font-weight:bold; width: 30px" /></div>
				<div class="leftalign editorcodebutton"><input id="{JSID}italic" type="button" class="button" accesskey="i" name="italic" value=" i " style="font-style:italic; width: 30px" /></div>
				<div class="leftalign editorcodebutton"><input id="{JSID}underline" type="button" class="button" accesskey="u" name="underline" value=" u " style="text-decoration: underline; width: 30px" /></div>

				<div class="leftalign editorlineleft"><input id="{JSID}ul" type="button" class="button" accesskey="l" name="ul" value="List" style="width: 40px" /></div>
				<div class="leftalign editorcodebutton"><input id="{JSID}ol" type="button" class="button" accesskey="o" name="ol" value="List=" style="width: 40px" /></div>
				<div class="leftalign editorcodebutton"><input id="{JSID}li" type="button" class="button" accesskey="e" name="li" value="*" style="width: 20px" /></div>

				<div class="leftalign editorlineleft"><input id="{JSID}img" type="button" class="button" accesskey="p" name="img" value="Image" style="width: 50px"  /></div>
				<div class="leftalign editorcodebutton"><input id="{JSID}url" type="button" class="button" accesskey="w" name="url" value="Link" style="text-decoration: underline; width: 40px" /></div>

				<div class="leftalign editorlineleft"><input id="{JSID}table" type="button" class="button" accesskey="t" name="table" value="Table" style="width: 50px"  /></div>

				<div class="leftalign editorlineleft">{STYLEFORM}</div>
			</div>
			<div class="newline">
				<textarea id="{JSID}edittext" name="edittext" rows="15" cols="80" class="post">{TEXT}</textarea>
			</div>

			<div class="editorbuttonrow">
				<input type="button" id="{JSID}savebutton" name="savebutton" value="Save" class="mainoption" />
				&nbsp;&nbsp;
				<input type="button"  id="{JSID}previewbutton" name="previewbutton" value="Preview" class="mainoption"  />
				&nbsp;&nbsp;
				<input type="reset" id="{JSID}resetbutton" name="reset" value="Reset" />
				&nbsp;&nbsp;
				<input type="button" id="{JSID}hideeditorbutton" name="hideeditorbutton" value="Hide Editor" />
				{HIDDENVARS}
			</div>

		</div>
	</div>
