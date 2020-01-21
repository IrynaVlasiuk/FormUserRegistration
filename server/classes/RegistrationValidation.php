<?php

class RegistrationValidation extends Validation
{
    private static  $error = '';

    private static function checkIfValueEmpty($key, $value)
    {
        if(empty($value)) {
            $key = ucfirst(str_replace('-', ' ', $key));
            self::$error = $key.' is empty';
        }
        return self::$error;
    }

    private static function checkIfEmail($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            self::$error = "Invalid email format";
        }
        return self::$error;
    }

    private static function checkPasswordLength($key, $value)
    {
        if(strlen($value) < PASSWORD_LENGTH) {
            self::$error = ucfirst($key).' is less than '. PASSWORD_LENGTH.' characters';
        }
        return self::$error;
    }

    private static function checkIfEqualPassword($key, $password, $confirmPassword)
    {
        if($password !== $confirmPassword) {
            self::$error = ucfirst($key).' should be the same as password';
        }
        return self::$error;
    }

    public static function validate($data)
    {
        $response = new Response();
        foreach ($data as $key => $value) {
            $value = trim($value);
            $value = strip_tags($value);
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            $value =  addslashes($value);
            array_push(self::$errList, self::checkIfValueEmpty($key, $value));

            if($key == 'email') {
                array_push(self::$errList, self::checkIfEmail($value));
            }
            if($key == 'password') {
                array_push(self::$errList, self::checkPasswordLength($key, $value));
            }
            if($key == 'confirm-password') {
                array_push(self::$errList, self::checkIfEqualPassword($key, $data['password'], $value));
            }

            $data[$key] = $value;
        }

        if(!array_filter(self::$errList)) { //check error message array
            $response->isSuccess = true;
            $response->data = $data;
        } else {
            $response->isSuccess = false;
            $response->message =  self::getErrorList();
        }

        return $response;
    }
}
