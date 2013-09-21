<tr>
  <td>
    <table border="0" cellpadding="5" cellspacing="1" width="100%" class="bodyline">
      <tr>
        <th colspan="2" class="thHead">{FILENAME}</th>
        <th class="thHead">Categories</th>
      </tr>
      <tr>
        <td class="table" valign="top">
          {IMAGE}
        </td>
        <td class="table" valign="top">
          <p><span class="highlight">Replace Image:</span>
            <form name="replaceimageform" action="editimagelist.php{ACTIONVARSREPLACE}" enctype="multipart/form-data" method="post">
              {HIDDENVARS}
              <input type="file" name="newfilename" size="30" maxlength="255" />
              <br>
              <input type="submit" name="replaceimage" value="Replace Image" class="liteoption">
              <input type="button" value="Preview" onClick="previewImage(newfilename.value)" class="liteoption">
            </form>
          </p>
          <!-- BEGIN switch ADVANCED_MODE -->
          <!-- BEGIN switch PAGELINKS -->
          <p class="highlight">This image is used in the following page(s):</p>
          {PAGELINKS}
          <!-- END switch PAGELINKS -->
          <!-- BEGIN switch NEWSITEMLINKS -->
          <p class="highlight">And in the following Newsitem(s):</p>
          {NEWSITEMLINKS}
          <!-- END switch NEWSITEMLINKS -->
          <!-- BEGIN switch NOT_USED -->
          </form>
          <!-- END switch NOT_USED -->
          <!-- END switch ADVANCED_MODE -->
          <p>&nbsp;<p><span class="highlight">Delete Image:</span>
          <form name="deleteform" action="{ACTIONVARSDELETE}" method="post">
            {HIDDENVARS}
            <input type="submit" name="delete" value="Delete this image" class="liteoption" />
          </form>
        </td>
        <td class="table" rowspan="3" valign="top">
          {CATEGORYLIST}
          <p>
            <form name="catform" action="editimagelist.php{ACTIONVARSCAT}" method="post">
              <br />
              {CATEGORYSELECTION}
              {HIDDENVARS}
              <br /><input type="submit" name="addcat" value="Add Categories" class="liteoption" />
              <input type="submit" name="removecat" value="Remove" class="liteoption" />
            </form>
          </p>
        </td>
      </tr>
      <tr>
        <td class="table">
          <form name="renameimage" action="editimagelist.php{ACTIONVARSRENAME}" method="post">
            <p class="highlight">Caption:</p>
            <p>
              {HIDDENVARS}
              <input type="text" name="caption" value="{CAPTION}" size="30" />
              <br>
              <input type="submit" name="rename" value="Change Caption" class="liteoption" />
            </p>
          </form>
        </td>
        <td class="table" valign="bottom">
          <!-- BEGIN switch NO_THUMBNAIL -->
          <form name="addthumbform" action="{ACTIONVARSADDTHUMB}" enctype="multipart/form-data" method="post">
            <p class="highlight">Thumbnail:</p>
            <p>
               <input type="file" name="thumbnail" size="30" maxlength="255" />
               {HIDDENVARS}
               <br><input type="submit" name="addthumb" value="Add Thumbnail" class="liteoption" />
               <input type="button" value="Preview" onClick="previewImage(thumbnail.value)" class="liteoption">
            </p>
          </form>
          <!-- END switch NO_THUMBNAIL -->
          <!-- BEGIN switch THUMBNAIL -->
          <form name="replacethumbform" action="{ACTIONVARSREPLACETHUMB}" enctype="multipart/form-data" method="post">
            <p class="highlight">Thumbnail:</p>
            <p>
              <input type="file" name="thumbnail" size="30" maxlength="255" />
              {HIDDENVARS}
              <br><input type="submit" name="replacethumb" value="Replace Thumbnail" class="liteoption" />
              <input type="button" value="Preview" onClick="previewImage(thumbnail.value)" class="liteoption">
            </p>
          </form>
          <p>&nbsp;<br />
            <form name="deletethumbform" action="{ACTIONVARSDELETETHUMBNAIL}" enctype="multipart/form-data" method="post">
              {HIDDENVARS}
              <input type="submit" name="deletethumb" value="Delete Thumbnail" class="liteoption" />
            </form>
          </p>
          <!-- END switch THUMBNAIL -->
        </td>
      </tr>
      <tr>
        <td class="table">
          <form name="renamesource" action="{ACTIONVARSSOURCE}" method="post">
            <p class="highlight">Source:</p>
            <p>
              {HIDDENVARS}
              <input type="text" name="source" value="{SOURCE}" size="30" maxlength="255" />
              <br>
              <input type="submit" name="rename" value="Change Source" class="liteoption" />
            </p>
          </form>
        </td>
        <td class="table">
          <form name="renamesourcelink" action="{ACTIONVARSSOURCELINK}" method="post">
            <p class="highlight">Sourcelink:</p>
            <p>
              {HIDDENVARS}
              <input type="text" name="sourcelink" value="{SOURCELINK}" size="30" maxlength="255" />
              <br>
              <input type="submit" name="rename" value="Change Sourcelink" class="liteoption" />
            </p>
          </form>
        </td>
      </tr>
      <tr>
        <td class="table">
          <form name="copyrightform" action="{ACTIONVARSCOPYRIGHT}" method="post">
            <p class="highlight">Copyright Holder:</p>
            <p>
              {HIDDENVARS}
              <input type="text" name="copyright" value="{COPYRIGHT}" size="30" maxlength="255" />
              <br>
              <input type="submit" value="Change Copyright" class="liteoption" />
            </p>
          </form>
        </td>
        <td class="table" colspan="2">
          <form name="permissionform" action="{ACTIONVARSPERMISSION}" method="post">
            <p class="highlight">Permissions:</p>
            <p>
              {HIDDENVARS}
              <p class="gen">
                <input type="radio" name="permission" value="{PERMISSION_GRANTED}" class="gen" {PERMISSION_GRANTED_CHECKED} />
                Permission granted
                <input type="radio" name="permission" value="{NO_PERMISSION}" class="gen" {NO_PERMISSION_CHECKED} />
                No Permission
                <input type="radio" name="permission" value="{PERMISSION_REFUSED}" class="gen" {PERMISSION_REFUSED_CHECKED} />
                Permission Refused
              </p>
              <br>
              <input type="submit" value="Change Permission" class="liteoption" />
            </p>
          </form>
        </td>
      </tr>
    </table>
  </td>
</tr>
<tr><td class="bodyline" colspan="2"></td></tr>
