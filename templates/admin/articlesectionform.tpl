<a name="section{ARTICLESECTION}"><hr></a>
<p class="sectiontitle">{SECTIONHEADER}</p>
<table>
  <tr>
    <td class="bodyline">
      <table cellpadding="5">
        <form name="articlepages" action="?sid={SID}&page={PAGE}&articlepage={ARTICLEPAGE}&articlesection={ARTICLESECTION}&action=editcontents#section{ARTICLESECTION}" method="post">
          <tr>
            <th class="thHead" colspan="3">Title</th>
          </tr>
          <tr>
            <td class="table" colspan="3">
              <span class="genmed">Section titles and images are optional.</span>
            </td>
          </tr>
          <tr>
            <td class="table" colspan="3">
              <input type="text" name="sectiontitle" value="{SECTIONTITLE}" />
              <input type="submit" name="editsectiontitle" value="Edit Section Title" class="liteoption" />
            </td>
          </tr>
        </form>
        {SECTIONCONTENTS}
      </table>
    </td>
  </tr>
</table>

<form name="movearticlesection" action="?sid={SID}&page={PAGE}&articlepage={ARTICLEPAGE}&articlesection={ARTICLESECTION}&action=editcontents#section{ARTICLESECTION}" method="post">
  <p><input type="submit" name="movesectionup" value="{MOVEUP}" class="liteoption">
  &nbsp;&nbsp;&nbsp;
  <input type="submit" name="movesectiondown" value="{MOVEDOWN}" class="liteoption">
  </p>
</form>

<p>
<form name="deletesection" action="?sid={SID}&page={PAGE}&articlepage={ARTICLEPAGE}&articlesection={ARTICLESECTION}&action=editcontents#section{ARTICLESECTION}" method="post">
<input type="submit" name="deletesection" value="Delete This Section" class="mainoption">
</form>
