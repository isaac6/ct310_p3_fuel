<div id="content" class="container-fluid dark-opac-bg">
  <form method='post' enctype='multipart/form-data'>
    <div id="attractionForm">Attraction title: <input type='text' name='title' placeholder='title'></div>
    <div id="attractionForm">Main content: <textarea name='content'>main article content</textarea></div>
    <div id="imgForm"><div id="imgFormTag">Attraction image: </div><div id="imgFormUpload"><input type='file' name='file' id='file'></div></div>
    <input type='submit' name='submit' value='Add Attraction'>
  </form>
  <?php
  if ($success === true) {
    echo "<p id=\"attractionResultMsg\">Successfully added new attraction to database!</p>";
  } else if ($success === false) {
    echo "<p id=\"attractionResultMsg\" class=\"red\">The following errors occured when saving attraction: " . $error . "</p>";
  }
  ?>
</div>
