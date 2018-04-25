<div id="content" class="container-fluid dark-opac-bg">
  <h3>
    <?php
      $usr = Auth::get('username');
      $email = Auth::get('email');
      if ($usr != 'guest') {
        $time = Auth::get('updated_at');
        echo "Welcome, ";
        echo $usr;
        echo "!<br/>Logged in since: " . date('D M j h:i:s a',$time);
        echo "<br/>Current email: ";
        echo $email;
      } else {
        echo "Not logged in.";
      }
    ?>
  </h3>
  <h4><a href="<?=Uri::Create('index.php/travelco/reset') ?>">Click here to reset your password</a></h4>
</div>
