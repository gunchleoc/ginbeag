<p>
  <form action="{LOCATION}<!-- BEGIN switch ANCHOR -->#{ANCHOR}<!-- END switch ANCHOR -->" method="post">
  <!-- BEGIN switch TEXT -->
    <table width="100%">
      <tr>
        <td class="bodyline">
          <table width="100%">
            <tr>
              <td class="table">
                <div class="gen">
                  {TEXT}
                </div>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <br />
  <!-- END switch TEXT -->
    <input type="button" name="edittext" value="{BUTTONTITLE}"
      onClick="window.open('{EDITTEXTLINK}?sid={SID}&page={PAGE}&item={ITEM}&elementtype={ELEMENTTYPE}{PARAMS}&action=edittext')" class="mainoption" />
    &nbsp;&nbsp;
    <input type="submit" name="update" value="Update Text" class="liteoption" />
    <br />
    <a href="{EDITTEXTLINK}?sid={SID}&page={PAGE}&item={ITEM}&elementtype={ELEMENTTYPE}{PARAMS}&action=edittext" target="_blank" class="gensmall">{BUTTONTITLE}</a>
    <div class="genmed">
      After editing text, the page will not update automatically.
      So, please use the "Update Text"-button
      to view your changes
    </div>
  </form>
</p>
