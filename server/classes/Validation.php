<?php

abstract class Validation
{
    protected static $errList = [];

    protected static function getErrorList(){
        print_r(self::$errList);
    }
}
