<?php

class DatabaseManager
{
    static function addUser($data)
    {
        $responseValidation = RegistrationValidation::validate($data);
        $responseAjax = new Response();
        if($responseValidation->isSuccess) {
            $data = $responseValidation->data;
            $fileName = null;
            if(!empty($_FILES['avatar']['name'])) {
                $uploadedFile = FileManager::uploadFile("images/avatars/", 'avatar');
                if($uploadedFile->isSuccess) {
                    $fileName = $uploadedFile->data;
                } else {
                    array_push($responseAjax->message, $uploadedFile->data);
                }
            }
            $user = new User($data['first-name'], $data['last-name'], $data['email'], $data['password'], $fileName);
            $emailExist = self::checkEmailUnique($user->getEmail());
            if($emailExist) {
                $responseAjax->isSuccess = false;
                $responseAjax->message = "A user with the same email already exists";
            } else {
                $mysqlQuery = self::saveUser($user->getFirstName(), $user->getLastName(), $user->getEmail(), $user->getPassword(), $fileName);
                if(!$mysqlQuery) {
                    $responseAjax->isSuccess = true;
                    array_push($responseAjax->message, "You are successfully registered");
                } else {
                    $responseAjax->isSuccess = false;
                    array_push($responseAjax->message, "Database error. Please try again");
                }
            }

            $responseValidation = $responseAjax;
        }

        print_r(json_encode($responseValidation));
    }

    private static function checkEmailUnique($email)
    {
        return Repository::query("SELECT * FROM users WHERE email = '$email' LIMIT 1");
    }

    private static function saveUser($firstName, $lastName, $email, $password, $fileName)
    {
        return Repository::query("INSERT INTO users (first_name, last_name, email, password, avatar) VALUES ('$firstName', '$lastName', '$email', '$password', '$fileName')");
    }
}
