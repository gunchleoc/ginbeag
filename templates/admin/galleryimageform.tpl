<form name="galleryimageform" action="?sid={SID}&page={PAGE}&offset={OFFSET}&pageposition={PAGEPOSITION}&noofimages={NOOFIMAGES}&action=editcontents" method="post">
  <!-- BEGIN switch SHOWALL -->
  <input type="hidden" name="showall" value="true" />
  <!-- END switch SHOWALL -->
  <input type="hidden" name="galleryitemid" value="{IMAGEID}" />
  <table>
    <tr>
      <td class="bodyline">
        <table>
          <tr>
            <th class="thHead" colspan="3">Image</th>
          </tr>
          <tr>
            <td class="table" valign="top">
              <span class="gen">Filename:</span>
              <br /><span class="gensmall">
                <a href="../editimagelist.php?sid={SID}" target="_blank">View files</a>
              </span>
            </td>
            <td class="table" valign="top">
              <input type="text" name="imagefilename" size="50" maxlength="255" value="{IMAGEFILENAME}" />
              <p>
                <input type="submit" name="changegalleryimage" value="Change Image" class="mainoption" />
                &nbsp;&nbsp;<input type="reset" value="Reset" class="liteoption" />
              </p>
              <!-- BEGIN switch NO_THUMBNAIL -->
              <p class="highlight">{NO_THUMBNAIL}</p>
              <!-- END switch NO_THUMBNAIL -->
              <p>
                <input type="submit" name="moveimageup" value="move up" class="liteoption" />
                &nbsp;&nbsp;&nbsp;<input type="text" name="positions" size="2" maxlength="3" value="1" />
                &nbsp;&nbsp;&nbsp;<input type="submit" name="moveimagedown" value="move down" class="liteoption" />
              </p>
            </td>
            <td rowspan="3" class="table" valign="top">
              {IMAGE}
              <br>
              <a href="{IMAGELINKPATH}" target="_blank" class="gensmall">View full size</a>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <p>
    <input type="submit" name="removegalleryimage" value="Remove image from this gallery" class="liteoption" />
    <input type="checkbox" name="removeconfirm" value="Confirm remove" class="gen" />
    <span class="gen">Confirm remove</span>
  </p>
</form>
<hr>

