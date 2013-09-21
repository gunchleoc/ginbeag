{HEADER}
<table width="100%">
	<tr>
		<td valign="top" width="33%">{NEWSITEMADDFORM}</td>

		<td align="center" valign="top" width="33%">&nbsp;</td>

		<td align="right" valign="top" width="33%">{NEWSITEMARCHIVEFORM}
		</td>
	</tr>
</table>

<hr>
<table width="100%">
		<tr>
		<td align="left" valign="top" width="33%">{NEWSITEMRSSFORM}</td>
		<td align="right" valign="top" colspan="2" width="33%">
		{NEWSITEMDISPLAYORDERFORM}</td>
	</tr>
</table>
<hr>
<table width="100%">
<tr>
<td>
<!-- BEGIN switch RSSBUTTON -->
{RSSBUTTON}
<!-- END switch RSSBUTTON -->
</td>
<td align="right">
{PAGEMENU}
</td>
</tr>
</table>
<!-- BEGIN switch HASNEWSITEMS -->

<div align="right">{JUMPTOPAGEFORM} {NEWSITEMSEARCHFORM}</div>

<hr>
<table width="100%">
	<tr>
		<td>
		<p class="pagetitle">{NEWSITEMTITLE}</p>
		</td>
		<td align="right">
		<p class="articledate" align="right"><span class="gen">Added
		by {AUTHORNAME} </span></p>
		</td>
	</tr>
	<tr>
		<td valign="bottom">{NEWSITEMTITLEFORM}</td>
		<td align="right" valign="bottom" width="*">{NEWSITEMDELETEFORM}
		</td>
	</tr>
</table>

<hr>
<table width="100%">
	<tr>
		<td>
		<p class="pagetitle">Publishing &amp; Copyright</p>
		<p>{NEWSITEMPUBLISHFORM}</p>
		</td>
	</tr>
</table>
<p>{NEWSITEMPERMISSIONSFORM}</p>
<hr>
<p>{NEWSITEMSYNOPSISFORM}</p>
<hr>
<p class="pagetitle">Sections</p>
<!-- BEGIN switch NEWSITEMSECTIONFORM -->
{NEWSITEMSECTIONFORM}
<!-- END switch NEWSITEMSECTIONFORM -->

<hr>
{INSERTNEWSITEMSECTIONFORM}

<hr>
<p>{NEWSITEMSOURCEFORM}</p>
<p>{FAKETHEDATEFORM}</p>
<form name="catform"
	action="?sid={SID}&page={PAGE}&offset={OFFSET}&newsitem={NEWSITEM}&action=editcontents"
	method="post">{CATEGORYLIST}

<p>{CATEGORYSELECTION} <br>
<input type="submit" name="addcat" value="Add Categories"
	class="liteoption" /> <input type="submit" name="removecat"
	value="Remove" class="liteoption" /></p>
</form>
<hr>

<div align="right">{PAGEMENU}</div>
<div align="right">{JUMPTOPAGEFORM}</div>

<!-- END switch HASNEWSITEMS -->
{BACKBUTTONS}
{FOOTER}