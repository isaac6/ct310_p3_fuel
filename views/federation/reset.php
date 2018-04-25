<div id="content" class="container-fluid dark-opac-bg">
  <div id="resetForm">
    <form method='post'>
      <p>Enter old password: <input type='password' name='old_pass' placeholder='old password'></p>
      <p>Enter new password: <input type='password' name='new_pass' placeholder='new password'></p>
      <p>Repeat new password: <input type='password' name='new_pass_repeat' placeholder='new password'></p>
      <p><input type='submit' name='submit' value='Reset Password' ></p>
    </form>
    <?php echo $msg; ?>
  </div>
</div>
