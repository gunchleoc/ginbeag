<form name="addimageform" action="editimagelist.php{ACTIONVARS}" enctype="multipart/form-data" method="post">
  <table border="0" cellpadding="4" cellspacing="1" width="100%" class="bodyline">
    <tr>
      <th class="thHead" colspan="3">
        Add Image
      </th>
    </tr>
    <tr>
      <td colspan="3" class="gensmall">
        Upload a file to <i>{IMAGELINKPATH}</i>.
        Thumbnails, Captions and Categories are optional.
        Thumbnails should be 175 pixels in width.
        <br>&nbsp;
      </td>
    </tr>
    <tr>
      <td class="table" valign="top">
        <span class="gen">Image file:</span>
        <br />
        <span class="gensmall">required</span>
      </td>
      <td class="table" valign="top">
        <input type="file" name="filename" size="30" maxlength="255" />
        <br />
        <input type="button" value="Preview image" onClick="previewImage(filename.value)" class="mainoption" />
      </td>
      <td rowspan="9" class="table" valign="top">
        <span class="gen">Select categories:<br />&nbsp;<br /></span>
        {CATEGORYSELECTION}
      </td>
    </tr>
    <tr>
      <td class="table" valign="top">
        <span class="gen">Rename Image:</span>
        <br>
        <span class="gensmall">optional</span>
      </td>
      <td class="table" valign="top">
        <p><input type="text" name="newname" size="30" maxlength="255" />
      </td>
    </tr>
    <tr>
      <td class="table" valign="top">
        <span class="gen">Thumbnail:</span>
        <br>
        <span class="gensmall">optional</span>
      </td>
      <td class="table" valign="top">
        <input type="file" name="thumbnail" size="30" maxlength="255" />
        <br>
        <input type="button" value="Preview thumbnail" onClick="previewImage(thumbnail.value)" class="mainoption" />
      </td>
    </tr>
    <tr>
      <td class="spacer" valign="top"></td>
      <td class="spacer" valign="top"></td>
    </tr>
    <tr>
      <td class="table" valign="top">
        <span class="gen">Caption:</span>
        <br>
        <span class="gensmall">optional</span>
      </td>
      <td class="table" valign="top">
        <input type="text" name="caption" value="{CAPTION}" size="30" maxlength="200" />
      </td>
    </tr>
    <tr>
      <td class="table" valign="top">
        <span class="gen">Source:</span>
        <br>
        <span class="gensmall">optional</span>
      </td>
      <td class="table" valign="top">
        <input type="text" name="source" value="{SOURCE}" size="30" maxlength="255" />
      </td>
    </tr>
    <tr>
      <td class="table" valign="top">
        <span class="gen">Sourcelink:</span>
        <br>
        <span class="gensmall">optional</span>
      </td>
      <td class="table" valign="top">
        <input type="text" name="sourcelink" value="{SOURCELINK}" size="30" maxlength="255" />
      </td>
    </tr>

    <tr>
      <td class="table" valign="top">
        <span class="gen">Copyright Holder:</span>
        <br>
        <span class="gensmall">optional</span>
      </td>
      <td class="table" valign="top">
        <input type="text" name="copyright" value="{COPYRIGHT}" size="30" maxlength="255" />
      </td>
    </tr>
    <tr>
      <td class="table">
        <span class="gen">Permissions:</span>
      </td>
      <td class="table">
        <p class="gen">
          <input type="radio" name="permission" value="{PERMISSION_GRANTED}" class="gen" {PERMISSION_GRANTED_CHECKED} />
          Permission granted
          <input type="radio" name="permission" value="{NO_PERMISSION}" class="gen" {NO_PERMISSION_CHECKED} />
          No Permission
          <input type="radio" name="permission" value="{PERMISSION_REFUSED}" class="gen" {PERMISSION_REFUSED_CHECKED} />
          Permission Refused
        </p>
      </td>
    </tr>
    <tr><td class="spacer" colspan="3"></td></tr>
    <tr>
      <td colspan="3" align="left">
        <input type="submit" name="addimage" value="Add New Image" class="mainoption" />
        &nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>
