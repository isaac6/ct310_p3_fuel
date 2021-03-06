<?php

use Model\Ormattraction;
use Model\Ormcomments;

class Controller_Federation extends Controller {

  /**
  * index
  */
  public function action_index() {
      //load tables
      $this->load_tables();
      // init views array
      $views = array();
      // load index view into content
      $views['content'] = View::forge('federation/index');
      // return final view
      return View::forge('federation/layout', $views);
  }

  /**
  * status JSON
  */
  public function action_status() {
    // create json array
    $json = array('status'=>'open');
    // create new response
    $response = new Response();
    // encode array as a json and set it to the body
    $response->body(json_encode($json, true));
    // set headers to application/json
    $response->set_header('Content-Type', 'application/json');
    // return
    return parent::after($response);
  }

  /**
  * all status - federation
  */
  public function action_allstatus() {
    /*
    // init rows array
    $rows = array();
    // send request to get master json
    $request = Request::forge('https://www.cs.colostate.edu/~ct310/yr2018sp/master.json', 'curl');
    // set request type and mime type that we want back
    $request->set_method('get')->set_mime_type('json');
    // execute it and get response
    $response = $request->execute()->response();
    // create json object
    $json = Format::forge($response, 'json')->to_array();
    // loop through it and get build rows for each element
    foreach ($json as $element) {
      // get the store status
      $status = $this->getStatus($element['eid']);
      // build the row element
      $row = '<tr><td scope="row">' . $element['eid'] . '</td>
        <td>' . $element['team'] . '</td>
        <td>' . $element['nameShort'] . '</td>
        <td>' . $element['nameLong'] . '</td>';
      // set some colors for the status element
      if ($status === 'open') {
        $row = $row . '<td class="status-green outline-dark">' . $status . '</td>
        </tr>';
      } else if ($status === 'closed') {
        $row = $row . '<td class="status-red outline-dark">' . $status . '</td>
        </tr>';
      } else {
        $row = $row . '<td class="status-yellow outline-dark">' . $status . '</td>
        </tr>';
      }
      // push to the back of the rows array (in order)
      array_push($rows, $row);
    }
    // load allstatus view into content
    $views['content'] = View::forge('federation/allstatus', $rows)->set('rows', $rows, false);
    */
    // setup array for final views
    $views = array();
    // load attractions view into content
    $views['content'] = View::forge('federation/allstatus');
    // return final view
    return View::forge('federation/layout', $views);
  }

  /**
  * get status of a specific eid's store
  * @param eid
  * this method can probably be deleted but it might be good for testing in case something breaks
  */
  public function action_getstatus($eid) {
    // just grab the output from private getStatus()
    return $this->getStatus($eid);
  }

  /**
  * get status of an eid's store
  * @param eid
  * returns the status in raw format
  */
  private function getStatus($eid) {
    //url page of the given eid
    $url = 'https://www.cs.colostate.edu/~' . $eid . '/ct310/index.php/federation/status';
    // set default value for status to error
    $status = 'error';
    // try to get the string from the url
    try {
        //decode to a json at the same time
        $json = json_decode(file_get_contents($url));
        // set status to the status
        $status = $json->status;
    } catch (Exception $e) {} // ignore exception
    // return the status
    return $status;
  }

  /**
  * action attraction image
  * @param img
  */
  public function action_attrimage($img) {
    try {
      // get ORM object
      $attraction = Ormattraction::find($img);
      // get the image name
      $img_name = $attraction['img'];
      // get the image path
      $img_path = Asset::find_file($img_name, 'img');
      // get the image data
      $img_data = file_get_contents($img_path);
      // create new response
      $response = new Response();
      // get the image data
      $response->body($img_data);
      // set headers to image/jpeg
      $response->set_header('Content-Type', 'image/jpeg');
      // return
      return parent::after($response);
    } catch (Exception $e) {
      // create new respone object with error and return it
      return Response::forge('Invalid image ID');
    }
  }

  /**
  * action listing
  * returns our attractions as a json in the format:
  * [{"id":"1","name":"Yosemite","state":"CA"}]
  */
  public function action_listing(){
      // get ORM objects
      $attractions = Ormattraction::find('all');
      $json = array();
      // remove details
      foreach ($attractions as $attr) {
          $json_data = array(
              'id'=>$attr['attractionID'],
              'name'=>$attr['name'],
              'state'=>$attr['state']
          );
          array_push($json, $json_data);
      }
      // create new response
      $response = new Response();
      // encode array as a json and set it to the body
      $response->body(json_encode($json, true));
      // set headers to application/json
      $response->set_header('Content-Type', 'application/json');
      // return the status
      return parent::after($response);
  }

  /**
  * action listing
  * returns our attractions as a json in the format:
  * {"id":"2","name":"Mt. Rushmore","desc":"Look, those rocks looks like the faces of presidents!","state":"SD"}
  */
  public function action_attraction($id){
      // get ORM object
      $attraction = Ormattraction::find($id);
      if($attraction !== null){
          //create json
          $json = array(
              'id'=>$attraction['attractionID'],
              'name'=>$attraction['name'],
              'desc'=> $attraction['details'],
              'state'=>$attraction['state']
          );
          // create new response
          $response = new Response();
          // encode array as a json and set it to the body
          $response->body(json_encode($json, true));
          // set headers to application/json
          $response->set_header('Content-Type', 'application/json');
          // return the status
          return parent::after($response);
      } else {
          return $this->defaultAttractionJSON();
      }

  }

  /**
  * returns the default json for listing
  */
  private function defaultAttractionJSON(){
      // create json
      $json = array('id'=>'null','name'=>'null','desc'=>'null','state'=>'null');
      // create new response
      $response = new Response();
      // encode array as a json and set it to the body
      $response->body(json_encode($json, true));
      // set headers to application/json
      $response->set_header('Content-Type', 'application/json');
      // return
      return parent::after($response);
  }
  
  /**
   * view external attraction
   * @param eid
   * @param id
   */
  public function action_view_external_attraction($eid, $id){
    // build the request url
    $request = Request::forge('http://cs.colostate.edu/~' . $eid . '/ct310/index.php/federation/attraction/' . $id, 'curl');
    // set request type and mime type that we want back
    $request->set_method('get');
    // execute it and get response
    $response = $request->execute()->response();
    // create json object
    $json = Format::forge($response, 'json')->to_array();
    // try to get the json from it
    $data = array();
    $data['eid'] = $eid;
    $data['id'] = $id;
    $data['name'] = $json['name'];
    $data['state'] = $json['state'];
    $data['details'] = $json['desc'];
    // pass data array to view
    $views = array();
    $views['content'] = View::forge('federation/view_external_attraction', $data);
    // build final view and return
    return View::forge('federation/layout', $views);
  }
  
  /**
  * login
  */
  public function action_login() {
      // setup array for final views
      $views = array();
      // setup array for initial views
      $loginViews = array();
      // load the login_form view
      $loginViews['login_form'] = View::forge('federation/login_form');
      // set default value for auth_success
      $loginViews['auth_success'] = "";
      // load login view into content
      $views['content'] = View::forge('federation/login', $loginViews);
      // return final view
      return View::forge('federation/layout', $views);
  }

  /**
  * login POST
  */
  public function post_login() {
    // setup array for final views
    $views = array();
    // setup array for initial views
    $loginViews = array();
    // grab login form view
    $loginViews['login_form'] = View::forge('federation/login_form');
    // set to default
    $loginViews['auth_success'] = false;
    // sanitize
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    // try to login
    if (Auth::login($username, $password) || Auth::login($username, md5($password))) {
      $loginViews['auth_success'] = true;
    }
    // render layout
    // load login view into content
    $views['content'] = View::forge('federation/login', $loginViews);
    // return final view
    return View::forge('federation/layout', $views);
  }

  /**
  * logout GET
  */
  public function get_logout() {
    // logout
    Auth::logout();
    // load index
    Response::redirect('index.php/federation/index');
  }

  /**
  * my account
  */
  public function action_account() {
      // setup array for final views
      $views = array();
      // load account view into content
      $views['content'] = View::forge('federation/account');
      // return final view
      return View::forge('federation/layout', $views);
  }

  /**
   * view Store Page
   * @access  public
   * @return  View
   */
  public function action_store()
  {
      //make parameters
      $data = array();
      $data['success'] = '';
      //load the layout
      $layout = View::forge('federation/layout');
      //load the view
      $content = View::forge('federation/store', $data);
      //forge inner view
      $layout->content = Response::forge($content);
      return $layout;
  }

  public function post_store()
  {
      //make parameters
      $data = array();
      $data['success'] = '';

      $username = Auth::get('username');
      $email = Auth::get('email');
      $amount = filter_var($_POST['Order_Amount'], FILTER_SANITIZE_STRING);

      if($amount >= 1) {
          $success = true;
          $this->email_order($amount, $username, $email);
      } else {
          $success = false;
      }

      //load the layout
      $layout = View::forge('federation/layout');
      //load the view
      $content = View::forge('federation/store', $data);
      //forge inner view
      $layout->content = Response::forge($content);
      return $layout;
  }

  private function email_order($amount, $username, $email) {

      $sending_email = Email::forge();
      $sending_email->from('jack.searl@colostate.edu', 'Definitely Not Kayak.com LLC');
      $sending_email->to($email, $username);
      $sending_email->subject('Brochure Order Confirmation.');
      $sending_email->body('Dear ' . $username . ',

      You have submitted a request for ' . $amount . ' free brochure(s).

      Your order is processing and you should excpect the shipment within 1-2 weeks.

      If you did not request these brochures, please contact us at help@definitelynotkayak.com as soon as possible.'
      );
      try{
          $sending_email->send();
          //email admins of the order
          $this->email_admins_order($amount, $username, $email);
      } catch(\EmailValidationFailedException $e) {
          echo 'validation failed';
      } catch(\EmailSendingFailedException $e) {
          echo 'the driver could not send the email';
      }
  }

  private function email_admins_order($amount, $username, $email){
      $sending_email = Email::forge();
      $sending_email->from('jack.searl@colostate.edu', 'Definitely Not Kayak.com LLC');
      $sending_email->to(array(
          'Aaron.Pereira@colostate.edu',
          'ct310@cs.colostate.edu',
          'jack.searl@colostate.edu',
          'isaac.hall@colostate.edu',
          ));
      $sending_email->subject('Brochure Order Confirmation.');
      $sending_email->body('User: '.$username.'
      Email: '.$email.'
      Amount Ordered: '.$amount.'

      This has been ordered successfully.'
      );
      try{
          $sending_email->send();
      } catch(\EmailValidationFailedException $e) {
          echo 'validation failed';
      } catch(\EmailSendingFailedException $e) {
          echo 'the driver could not send the email';
      }
  }

  /**
  * attractions
  */
  public function action_attractions() {
      // setup array for final views
      $views = array();
      // setup data array
      $data = array();
      // load attractions from database
      $data['attractions'] = Ormattraction::find('all');
      // load attractions view into content
      $views['content'] = View::forge('federation/attractions', $data);
      // return final view
      return View::forge('federation/layout', $views);
  }

  /**
  * attractions POST
  */
  public function post_attractions() {
    // sanitize and get id to delete
    $id = filter_var($_POST['delete_id'], FILTER_SANITIZE_STRING);
    // find the Orm object to delete
    $attraction = Ormattraction::find($id);
    // delete it
    $attraction -> delete();
    // delete the accompanying image
    File::delete(DOCROOT . 'assets/img/' . $attraction->img);
    // redirect to attractions page
    Response::redirect('index.php/federation/attractions');
  }

  /**
  * add attraction
  */
  public function action_add_attraction() {
      // setup array for final views
      $views = array();
      // setup data array for some messages / values
      $data = array();
      // set some values to null
      $data['error'] = '';
      $data['success'] = '';
      // load add_attraction view into content
      $views['content'] = View::forge('federation/add_attraction', $data);
      // return final view
      return View::forge('federation/layout', $views);
  }

  /**
  * add attraction POST
  */
  public function post_add_attraction() {
    // sanitize
    $name = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
    $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
    // setup data array for parameters
    $data = array();
    // set all values to default/null
    $data['error'] = '';
    $data['success'] = '';
    // setup upload process
    Upload::process(array(
      'path' => DOCROOT . 'assets/img/',
      'file_chmod' => 0755,
      'auto_rename' => true,
      'randomize' => true,
      'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
    ));
    // check if upload is valid
    if (Upload::is_valid()) {
      // save file
      Upload::save();
      // check file is ok
      if (!Upload::get_files()) {
        // loop and get errors, put them in array with newline separators
        foreach (Upload::get_errors() as $file) {
          foreach ($file['errors'] as $error=>$message)
            foreach ($message as $msg) {
              $data['error'] = $data['error'] . $msg . "\n";
            }
        }
        // update data array for view
        $data['success'] = false;
        // check if errors are still empty (unset)
        if ($data['error'] === '') {
          $data['error'] = 'Failed to save image!';
        }
      } else {
        // create the attraction
        $img_data = Upload::get_files(0)['saved_as'];
        $attraction = new Ormattraction();
        $attraction->name = $name;
        $attraction->details = $content;
        $attraction->state = $state;
        $attraction->img = $img_data;
        // save it
        $attraction->save();
        // set parameter
        $data['success'] = true;
      }
    } else {
      // loop and get errors, put them in array with newline separators
      foreach (Upload::get_errors() as $file) {
        foreach ($file['errors'] as $error=>$message)
          foreach ($message as $msg) {
            $data['error'] = $data['error'] . $msg . "\n";
          }
      }
      // set success to false
      $data['success'] = false;
      // check if errors are still empty (unset)
      if ($data['error'] === '') {
        $data['error'] = 'Image upload failed!';
      }
    }
    // setup views and return them
    $views = array();
    // load add_attraction view into view
    $views['content'] = View::forge('federation/add_attraction', $data);
    // return final view
    return View::forge('federation/layout', $views);
  }

  /**
  * attraction view
  * @param id
  */
  public function action_view_attraction($id) {
    // setup data array
    $data = array();
    // find the attraction object
    $data['attraction'] = Ormattraction::find($id);
    $data['comments'] = Ormcomments::find('all');
    // spool up the views array
    $views = array();
    // set the content and give it the attraction object
    $views['content'] = View::forge('federation/view_attraction', $data);
    // return final view
    return View::forge('federation/layout', $views);
  }

  /**
  * attraction view POST
  */
  public function post_view_attraction() {
    // sanitize
    $id = filter_var($_POST['save_id'], FILTER_SANITIZE_STRING);
    $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
    // find existing ORM object and update it
    $comment = Ormcomments::find($id);
    // update it
    $comment->content = $content;
    $comment->time = Date::time();
    // save it
    $comment->save();
    // redirect back to comments
    Response::redirect_back('index.php/federation/attractions/', 'refresh');
  }

  /**
  * add comment POST
  */
  public function post_add_comment() {
    // sanitize
    $newcontent = filter_var($_POST['new_content'], FILTER_SANITIZE_STRING);
    $attractionid = filter_var($_POST['add_id'], FILTER_SANITIZE_STRING);
    // create new ORM object for comment
    $comment = new Ormcomments();
    $comment->attractionID = $attractionid;
    $comment->userID = Auth::get('id');
    $comment->username = Auth::get('username');
    $comment->content = $newcontent;
    $comment->time = Date::time();
    // save it
    $comment->save();
    // redirect back to comments
    Response::redirect_back('index.php/federation/attractions/', 'refresh');
  }

  /**
  * delete comment POST
  */
  public function post_delete_comment() {
    // sanitize
    $deleteid = filter_var($_POST['delete_id'], FILTER_SANITIZE_STRING);
    // find comment
    $comment = Ormcomments::find($deleteid);
    // delete it
    $comment->delete();
    // redirect back to comments
    Response::redirect_back('index.php/federation/attractions/', 'refresh');
  }

  /**
  * forgot password
  */
  public function action_forgot() {
      // setup array for final views
      $views = array();
      // setup data array and initialize success to null
      $data = array();
      $data['success'] = '';
      // load forgot view into content
      $views['content'] = View::forge('federation/forgot', $data);
      // return final view
      return View::forge('federation/layout', $views);
  }

  /**
  * forgot password POST
  */
  public function post_forgot() {
    // sanitize
    $emailaddress = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    // query database to get some more info about the user
    $result = DB::select('username','email')->from('users')->where('email', $emailaddress)->execute();
    $username = $result[0]['username'];
    $useremail = $result[0]['email'];
    // setup a data array for our forgot view
    $data = array();
    $data['success'] = false;
    // verify email and try to send it
    if ($emailaddress === $useremail && $emailaddress != null) {
      // send email
      $this->reset_password_email($username, $useremail);
      // set success parameter true
      $data['success'] = true;
    }
    // setup views
    // setup array for final views
    $views = array();
    // load forgot view into content
    $views['content'] = View::forge('federation/forgot', $data);
    // return final view
    return View::forge('federation/layout', $views);
  }

  /**
  * reset password
  */
  public function action_reset() {
      // setup array for final views
      $views = array();
      // setup data array
      $data = array();
      // set message display to blank
      $data['msg'] = '';
      // load forgot view into content
      $views['content'] = View::forge('federation/reset', $data);
      // return final view
      return View::forge('federation/layout', $views);
  }

  private function reset_password_email($username, $useremail) {
    // reset password
    $newpass = Auth::reset_password($username);
    // create email
    $email = Email::forge();
    $email->from('ct310p2@cs.colostate.edu', 'CT310 P2');
    $email->to($useremail, $username);
    $email->subject('Reset your password');
    $email->body('Hello, ' . $username . '
    You have requested a password reset.' . '
    Your temporary password is: ' . $newpass . '
    Please visit ' . Uri::Create('index.php/federation/login') . ' and login using your temporary password to reset your password.'
    );
    // try to send the email
    try{
        $email->send();
    } catch(\EmailValidationFailedException $e) {
        //validation failed
    } catch(\EmailSendingFailedException $e) {
        // the driver could not send the email
    }
  }

  /**
  * reset POST
  */
  public function post_reset() {
    // sanitize
    $oldpass = filter_var($_POST['old_pass'], FILTER_SANITIZE_STRING);
    $newpass = filter_var($_POST['new_pass'], FILTER_SANITIZE_STRING);
    $newpassrepeat = filter_var($_POST['new_pass_repeat'], FILTER_SANITIZE_STRING);
    // setup data array for view
    $data = array();
    // set success to false
    $resetsuccess = false;
    // check that passwords match
    if ($newpass === $newpassrepeat) {
      $resetsuccess = Auth::change_password($oldpass, $newpass, Auth::get('username'));
    } else {
      $data['msg'] = "<div class=\"red\">Passwords do not match!</div>";
    }
    // setup messages for the view
    if ($resetsuccess) {
      $data['msg'] = "Password successfully reset!";
    } else {
      $data['msg'] = "<div class=\"red\">Failed to reset password!</div>";
    }
    // setup some views
    $views = array();
    // load reset view into content
    $views['content'] = View::forge('federation/reset', $data);
    // return final view
    return View::forge('federation/layout', $views);
  }

  /**
  * load tables
  * check if SQL tables exist, if not; create them
  */
  private function load_tables() {
    // check users table
    if (!DBUtil::table_exists('users')) {
      // User table
      DBUtil::create_table('users', array(
        'id' => array('constraint' => 12, 'type' => 'int', 'auto_increment' => true),
        'username' => array('constraint' => 64, 'type' => 'varchar'),
        'password' => array('constraint' => 124, 'type' => 'varchar'),
        'group' => array('constraint' => 64, 'type' => 'varchar'),
        'email' => array('constraint' => 64, 'type' => 'varchar'),
        'last_login' => array('constraint' => 12, 'type' => 'int'),
        'current_login' => array('type' => 'text'),
        'login_hash' => array('constraint' => 64, 'type' => 'varchar'),
        'profile_fields' => array('constraint' => 64, 'type' => 'varchar'),
        'created_at' => array('constraint' => 12, 'type' => 'int'),
        'updated_at' => array('constraint' => 12, 'type' => 'int'),
        'recovery_key' => array('constraint' => 64, 'type' => 'varchar')
      ), array('id', 'username'));
      $this->createUsers();
    }
    //Attractions Table
    if (!DBUtil::table_exists('attractions'))
    {
        DBUtil::create_table('attractions', array(
            'attractionID' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
            'name' => array('constraint' => 125, 'type' => 'varchar'),
            'state' => array('constraint' => 125, 'type' => 'varchar'),
            'details' => array('type' => 'text'),
            'img' => array('type' => 'text')
        ), array('attractionId'));
        //Default Attractions
        $this->defaultAttractions();
    }

    // check comments table
    if (!DBUtil::table_exists('comments')) {
      DBUtil::create_table('comments', array(
        'commentID' => array('constraint' => 12, 'type' => 'int', 'auto_increment' => true),
        'userID' => array('constraint' => 12, 'type' => 'int'),
        'username' => array('constraint' => 64, 'type' => 'varchar'),
        'attractionID' => array('constraint' => 12, 'type' => 'int'),
        'content' => array('type' => 'text'),
        'time' => array('type' => 'text')
      ), array('commentID'));
      DBUtil::add_foreign_key('comments', array(
        'constraint' => 'fk_user',
        'key' => 'userID',
        'reference' => array(
          'table' => 'users',
          'column' => 'id'
        ),
        'on_delete' => 'CASCADE'
      ));
      DBUtil::add_foreign_key('comments', array(
        'constraint' => 'fk_attraction',
        'key' => 'attractionID',
        'reference' => array(
          'table' => 'attractions',
          'column' => 'attractionID'
        ),
        'on_delete' => 'CASCADE'
      ));
    }
  }

  /**
  * createUsers
  * create all of the default users if they don't already exist
  * admin = 10
  * customer = 1
  */
  private function createUsers() {
    Auth::create_user('aaronper', '449a36b6689d841d7d27f31b4b7cc73a', 'aaronper@cs.colostate.edu', 1, array());
    Auth::create_user('aaronperadmin', 'd31bfd85d0a81046f70304ebfecdffbf', 'Aaron.Pereira@colostate.edu     ', 10, array());
    Auth::create_user('bsay', '790f6b6cf6a6fbead525927d69f409fe', 'bsay@cs.colostate.edu    ', 1, array());
    Auth::create_user('ct310', 'a6cebbf02cc311177c569525a0f119d7', 'ct310@cs.colostate.edu  ', 10, array());
    Auth::create_user('isaac', 'admin', 'isaac.hall@colostate.edu', 10, array());
    Auth::create_user('customer', 'test', 'iyzik@aol.com', 1, array());
    Auth::create_user('jack', 'admin', 'jack.searl@colostate.edu', 10, array());
  }

  /**
  * creates default attractions upon creation of attractions table
  */
  private function defaultAttractions() {
      //attraction 1, set variables
      $name = 'International Peace Garden';
      $details = 'Since 1932, nestled in the Turtle Mountains of North Dakota and Manitoba, the International Peace Garden
                    is one of the continent\'s most symbolic and scenic attractions. Thousand of tourists flock to this unique
                    tribute to peace and friendship between the people of the United States of America and the people of Canada.';
      $img = 'garden.jpg';
      $state = 'ND';
      //create attraction
      $attraction1 = new Ormattraction();
      $attraction1->name = $name;
      $attraction1->details = $details;
      $attraction1->img = $img;
      $attraction1->state = $state;
      $attraction1->save();
      //attraction 2, set variables
      $name2 = 'Theodore Roosevelt National Park';
      $details2 = 'Theodore Roosevelt National Park lies in western North Dakota, where the Great Plains meet the rugged Badlands.
                    A habitat for bison, elk and prairie dogs, the sprawling park has 3 sections linked by the Little Missouri River.
                    The park is known for the South Unit’s colorful Painted Canyon and the Maltese Cross Cabin, where President
                    Roosevelt once lived. The Scenic Loop Drive winds past several overlooks and trails.';
      $img2 = 'buffalo.jpg';
      $state2 = 'ND';
      //create attraction
      $attraction2 = new Ormattraction();
      $attraction2->name = $name2;
      $attraction2->details = $details2;
      $attraction2->state = $state2;
      $attraction2->img = $img2;
      $attraction2->save();
  }
}
