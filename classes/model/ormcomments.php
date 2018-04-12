<?php

namespace Model;

class Ormcomments extends \Orm\Model
{
    protected static $_properties = array('commentID', 'userID', 'username', 'attractionID', 'content', 'time');
    protected static $_table_name = 'comments';
    protected static $_primary_key = array('commentID');
    protected static $_foreign_keys = array('userID, attractionID');
}
