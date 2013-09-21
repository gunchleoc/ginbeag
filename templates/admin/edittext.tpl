{HEADER}
<h2 class="pagetitle">Page: {PAGETITLE}</h2>
<table width="80%">
  <tr>
    <td class="bodyline">
      <table cellpadding="5">
        <tr>
          <td class="table"><span class="gen">{TEXT}</span></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<!-- BEGIN switch EDITTEXT -->
<br />
<script type="text/javascript" charset="utf-8">
<!--
//http://aktuell.de.selfhtml.org/artikel/javascript/bbcode/

function addTags(opentag,closetag)
{
  var txtarea = document.edittext.text;

  /* fr Internet Explorer */
  if(typeof document.selection != 'undefined') {
    var selection = document.selection.createRange().text;

    if(selection)
    {
      document.selection.createRange().text = opentag + selection + closetag;
		  txtarea.focus();
    }
    else
    {
      txtarea.value += opentag + closetag;
      txtarea.focus();
    }

    // Insert at Claret position. Code from
    // http://www.faqts.com/knowledge_base/view.phtml/aid/1052/fid/130
  	if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
  }
  /* fr neuere auf Gecko basierende Browser */
  else if(typeof txtarea.selectionStart != 'undefined')
  {
    /* Einfgen des Formatierungscodes */
    var start = txtarea.selectionStart;
    var end = txtarea.selectionEnd;
    var insText = txtarea.value.substring(start, end);
    txtarea.value = txtarea.value.substr(0, start) + opentag + insText + closetag + txtarea.value.substr(end);
    /* Anpassen der Cursorposition */
    var pos;
    if (insText.length == 0) {
      pos = start + opentag.length;
    } else {
      pos = start + opentag.length + insText.length + closetag.length;
    }
    txtarea.selectionStart = pos;
    txtarea.selectionEnd = pos;
  }
  /* fr die brigen Browser */
  else
  {
    /* Abfrage der Einfgeposition */
    var pos;
    var re = new RegExp('^[0-9]{0,3}$');
    while(!re.test(pos)) {
      pos = prompt("Insert at position (0.." + txtarea.value.length + "):", "0");
    }
    if(pos > txtarea.value.length) {
      pos = txtarea.value.length;
    }
    /* Einfgen des Formatierungscodes */
    var insText = prompt("Please insert the text to be formatted:");
    txtarea.value = txtarea.value.substr(0, pos) + opentag + insText + closetag + txtarea.value.substr(pos);
  }
}
//-->
</script>
<form name="edittext" action="edittext.php?sid={SID}&page={PAGE}&item={ITEM}&elementtype={ELEMENTTYPE}&action=edittext" method="post" enctype="multipart/form-data;charset=UTF-8">
  <table>
    <tr>
      <td class="bodyline">
        <table cellpadding="5"
          <tr>
            <th class="thHead" width="100%">Edit text</th>
          </tr>
          <tr>
            <td class="table" valign="top">
              <table width="500" border="0" cellspacing="0" cellpadding="2">
                <tr align="center" valign="middle">
                  <td>
                    <input type="button" class="button" accesskey="b" name="bold" value=" B " style="font-weight:bold; width: 30px" onClick="addTags('[b]','[/b]')" />
                  </td>
                  <td>
                    <input type="button" class="button" accesskey="i" name="italic" value=" i " style="font-style:italic; width: 30px" onClick="addTags('[i]','[/i]')" />
                  </td>
                  <td>
                    <input type="button" class="button" accesskey="u" name="underline" value=" u " style="text-decoration: underline; width: 30px" onClick="addTags('[u]','[/u]')" />
                  </td>
                  <td>
                    <input type="button" class="button" accesskey="l" name="ul" value="List" style="width: 40px" onClick="addTags('[list]','[/list]')" />
                  </td>
                  <td>
                    <input type="button" class="button" accesskey="o" name="ol" value="List=" style="width: 40px" onClick="addTags('[list=]','[/list]')" />
                  </td>
                  <td>
                    <input type="button" class="button" accesskey="p" name="img" value="Img" style="width: 40px"  onClick="addTags('[img]','[/img]')" />
                  </td>
                  <td>
                    <input type="button" class="button" accesskey="w" name="url" value="URL" style="text-decoration: underline; width: 40px" onClick="addTags('[url]','[/url]')" />
                  </td>
                  <td>
                    <input type="button" class="button" accesskey="w" name="url=" value="URL=" style="text-decoration: underline; width: 40px" onClick="addTags('[url=]','[/url]')" />
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td class="table" valign="top">
              <textarea name="text2" rows="15" cols="80" class="post">{EDITTEXT}</textarea>
            </td>
          </tr>
          <tr><td class="spacer"></td></tr>
          <tr>
		        <td class="buttonRow" align="center">
              <input type="submit" name="submit" value="Submit" class="mainoption" />
              &nbsp;&nbsp;
              <input type="submit" name="preview" value="Preview" class="mainoption" />
              &nbsp;&nbsp;
              <input type="button" name="close" value="Cancel" onClick="window.close()" class="liteoption">
		        </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<!-- END switch EDITTEXT -->

<!-- BEGIN switch SUBMIT -->
<p class="highlight">saved text</p>
<form>
  <input type="button" name="close" value="Close" onClick="window.close()" class="mainoption" />
</form>
<!-- END switch SUBMIT -->
{FOOTER}
