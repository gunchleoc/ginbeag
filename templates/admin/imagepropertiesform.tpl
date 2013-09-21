<form name="imagepropertiesform" action="?sid={SID}&page={PAGE}&offset={OFFSET}&articlepage={ARTICLEPAGE}{PARAMS}&action=editcontents<!-- BEGIN switch ANCHOR -->#{ANCHOR}<!-- END switch ANCHOR -->" method="post">
  <tr>
    <th class="thHead" colspan="3">
      {HEADER} Image
    </th>
  </tr>
  <tr>
    <td class="table">
      <span class="gen">Filename:</span>
      <br /><span class="gensmall">
        <a href="{IMAGELISTPATH}?sid={SID}" target="_blank">View files</a>
      </span>
    </td>
    <td class="table">
      <input type="text" name="imagefilename" size="50" maxlength="255" value="{IMAGEFILENAME}" />
    </td>
    <td rowspan="3" class="table">
      <!-- BEGIN switch IMAGE -->
      <!-- BEGIN switch IMAGEPATH -->
      {IMAGE}
      <br />
      <a href="{IMAGEPATH}" target="_blank" class="gensmall">View full size</a>
      <!-- END switch IMAGEPATH -->
      <!-- BEGIN switch NO_IMAGEPATH -->
      <span class="highlight">{NO_IMAGEPATH}</span>
      <!-- END switch NO_IMAGEPATH -->
      <!-- END switch IMAGE -->
      <!-- BEGIN switch NO_IMAGE -->
      &nbsp;
      <!-- END switch NO_IMAGE -->
    </td>
  </tr>
  <tr>
    <td class="gen">Horizontal:</td>
    <td class="gen">
      <input type="radio" name="imagealign" value="left" class="gen" {LEFT_ALIGN_CHECKED} />
      Left
      <input type="radio" name="imagealign" value="center" class="gen" {CENTER_ALIGN_CHECKED} />
      Center
      <input type="radio" name="imagealign" value="right" class="gen" {RIGHT_ALIGN_CHECKED} />
      Right
    </td>
  </tr>
  <tr>
    <td class="gen">Vertical:</td>
    <td class="gen">
      <input type="radio" name="imagevalign" value="top" class="gen" {TOP_VALIGN_CHECKED} />
      Top
      <input type="radio" name="imagevalign" value="middle" class="gen" {MIDDLE_VALIGN_CHECKED} />
      Center
      <input type="radio" name="imagevalign" value="bottom" class="gen"  {BOTTOM_VALIGN_CHECKED} />
      Bottom
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center" class="table">
      <input type="submit" name="{SUBMITNAME}" value="Save Image Properties" class="mainoption" />
      &nbsp;&nbsp;<input type="reset" value="Reset" class="liteoption" />
    </td>
  </tr>
  <tr><td class="spacer" colspan="3"></td></tr>
</form>
