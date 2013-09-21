<form name="addgalleryimageform" action="?sid={SID}&page={PAGE}&offset={OFFSET}&pageposition={PAGEPOSITION}&noofimages={NOOFIMAGES}&action=editcontents" method="post">
  <!-- BEGIN switch SHOWALL -->
  <input type="hidden" name="showall" value="true">
  <!-- END switch SHOWALL -->
  <table>
    <tr>
      <td class="bodyline">
        <table>
          <tr>
            <th class="thHead" colspan="2">Add Image to end of gallery</th>
          </tr>
          <tr>
            <td class="table" valign="top">
              <span class="gen">Filename:</span>
              <br /><span class="gensmall">
                <a href="../editimagelist.php?sid={SID}" target="_blank">View files</a>
              </span>
            </td>
            <td class="table" valign="top">
              <input type="text" name="imagefilename" size="50" maxlength="255" value="" />
              <br /><input type="submit" name="addgalleryimage" value="Add Image" class="mainoption" />
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
