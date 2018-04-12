<?php

class Controller_Federation extends Controller {
  /**
  * index
  */
  public function action_index() {
      // init views array
      $views = array();
      // load index view into content
      $views['content'] = View::forge('canoe/index');
      // return final view
      return View::forge('canoe/layout', $views);
  }

  /**
  * status JSON
  */
  public function action_status() {
    $json = "{\"status\":\"closed\"}";
    return $json;
  }
}
