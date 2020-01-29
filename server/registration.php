<?php
session_start();

define('PASSWORD_LENGTH', 6);

function autoloader($class) {
    include 'classes/' . $class . '.php';
}

spl_autoload_register('autoloader');

if (isset($_POST)) {
    DatabaseManager::addUser($_POST);
}

