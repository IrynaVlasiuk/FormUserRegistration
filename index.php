<?php
session_start();

function autoloader($class) {
    include 'server/classes/' . $class . '.php';
}

spl_autoload_register('autoloader');

$locale = App::getLocale();
$lang = App::lang();

include('views/registration.html');



