<?php

namespace Model;

class Ormattraction extends \Orm\Model
{
    protected static $_properties = array('attractionID', 'name', 'details', 'img');
    protected static $_table_name = 'attractions';
    protected static $_primary_key = array('attractionID');
}
