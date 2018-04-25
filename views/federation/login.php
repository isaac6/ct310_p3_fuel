<div id="content" class="container-fluid dark-opac-bg">
  <h2>Login</h2>
  <br/>
  <?php echo $login_form; ?>
  <?php
  $datetime = date('D M j h:i:s a');
  if($auth_success === false){
      echo "<div id=\"loginMsg\" class=\"red\">Login failed. Please try again!<br/>Time: " . $datetime . "</div>";
  } else if ($auth_success === true) {
      echo "<div id=\"loginMsg\">Login successful. Welcome, " . Auth::get('username') . "!";
      echo " Logged in at: "  . $datetime;
      echo "<br/> Last login at: " . date('D M j h:i:s a', Auth::get('last_login')) . "</div>";
      Auth::update_user(array('current_login' => $datetime));
  }
  ?>
  <br/>
  <a href= '<?=Uri::create('index.php/travelco/forgot/'); ?>'>Forgot Password?</a>
</div>
