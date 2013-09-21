{HEADER}
<br />
<hr>
<table width="100%">
  <tr>
    <td valign="middle">
      {BACKBUTTONS}
    </td>
    <td valign="middle" align="right">
      {PAGEMENU}
    </td>
  </tr>
</table>
<!-- BEGIN switch DELETEPAGE -->
<form action="?sid={SID}&page={PAGE}&articlepage={ARTICLEPAGE}&action=editcontents" method="post">
  <input type="submit" name="deletelastarticlepage" value="{DELETEPAGE}" class="mainoption">
</form>
<!-- END switch DELETEPAGE -->
<!-- BEGIN switch ARTICLESECTIONFORM -->
{ARTICLESECTIONFORM}
<!-- END switch ARTICLESECTIONFORM -->
<hr>
<table cellpadding="5">
  <tr>
    <td valign="middle">
      <form name="articlepages" action="?sid={SID}&page={PAGE}&articlepage={ARTICLEPAGE}&action=editcontents" method="post">
        <input type="submit" name="addarticlesection" value="Add Section" class="mainoption" />
      </form>
    </td>
  </tr>
</table>
<hr>
<table width="100%">
  <tr>
    <td valign="middle">
      {BACKBUTTONS}
    </td>
    <td valign="middle" align="right">
      {PAGEMENU}
    </td>
  </tr>
</table>
{FOOTER}
