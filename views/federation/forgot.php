<div id="content" class="container-fluid dark-opac-bg">
  <h4>Enter email address:</h4>
  <form method='post'>
      <input type='text' name='email' placeholder='email address'>
      <input type='submit' name='submit' value='Send reset email' >
  </form>
  <?php
    if ($success === true) {
      echo "<br/>Sent reset email!";
    } else if ($success === false) {
      echo "<br/><div class=\"red\">That email does not exist in our database!</div>";
    }
  ?>
</div>
