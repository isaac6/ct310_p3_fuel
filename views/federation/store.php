<div class="container-fluid dark-opac-bg" style="padding:15px">
  <?php
    if(Auth::check()):
      if($success === true) {
          echo "Thank you for your order! You will recieve a confirmation email soon. <br/>" ;
      }
      ?>
      Order some of our free travel brochures! <br /><br />
      <div class="container">
        <form method='post' id="storeform">
          <label>First Name</label>
          <input type='text' name='First Name' placeholder='First Name' style='width: 100%; text-align: left' /> <br/>
          <label>Last Name</label>
          <input type='text' name='Last Name' placeholder='Last Name' style='width: 100%'/> <br/>
          <label>Street Address</label>
          <input type='text' name='Street Address' placeholder='Street Address' style='width: 100%'/> <br/>
          <label>City</label>
          <input type='text' name='City' placeholder='City' style='width: 100%'/> <br/>
          <label>State</label>
          <input type='text' name='State' placeholder='State' style='width: 100%'/> <br/>
          <label for=>Zipcode</label>
          <input type='number' name='Zipcode' placeholder='Zipcode' style='width: 100%'/> <br/>

          <label>(For the purposes of this project, the above does nothing and is only for style)</label>
           <br/>
          <label for=>Order Amount</label>
          <input type='number' id='Order Amount' name='Order_Amount' placeholder='0' required style='width: 100%' min='1' step='1'/>
          <?php if($success === false) {
              echo "\'Order Amount\' must be a non-negative non-zero number. Please try again.";
          } ?>
          <br/><br/>
          <input type='submit' name='submit'/>
        </form>
      </div>
    <?php else: ?>
      You are not currently logged in. Please log in and try again.
    <?php endif;
  ?>
</div>
