<a name="section{NEWSITEMSECTION}"><hr></a>
<table>
  <tr>
    <td class="bodyline">
      <table cellpadding="5">
        <form name="newsitemsection" action="?sid={SID}&page={PAGE}&newsitem={NEWSITEM}&newsitemsection={NEWSITEMSECTION}&offset={OFFSET}&action=editcontents#section{NEWSITEMSECTION}" method="post">
          <tr>
            <th class="thHead" colspan="3">{SECTIONHEADER}</th>
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


<p>
<form action="?sid={SID}&page={PAGE}&newsitem={NEWSITEM}&newsitemsection={NEWSITEMSECTION}&offset={OFFSET}&action=editcontents" method="post">
  <input type="submit" name="deletesection" value="Delete This Section" class="liteoption">
</form>
{INSERTNEWSITEMSECTIONFORM}