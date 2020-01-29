<?php


class App
{
   private static $locale;

   public static function getLocale()
   {
       self::$locale = 'en'; // default value

       if(isset($_SESSION['lang'])) {
           self::$locale = $_SESSION['lang'];
       }

       if(isset($_GET['lang'])) {
           self::$locale = $_GET['lang'];
           self::setSession();
       }

       return self::$locale;
   }

   private static function setSession()
   {
       $_SESSION['lang'] = self::$locale;
   }

   public static function lang()
   {
      include($_SERVER['DOCUMENT_ROOT'].'/lang/'.self::getLocale() . '.php'); // include lang file
      return $lang;
   }
}