<?php

class FileManager {

    static function uploadFile($targetDir, $fileName)
    {
        $response = new Response();
        $targetDir = "/images/avatars/";
        $file = basename($_FILES[$fileName]['name']);
        $targetFilePath = $_SERVER['DOCUMENT_ROOT'] .$targetDir . $file;
        $fileType = pathinfo($file,PATHINFO_EXTENSION);

        $allowTypes = array('jpg','png','jpeg','gif');
        if(in_array($fileType, $allowTypes)) {
            if(move_uploaded_file($_FILES[$fileName]['tmp_name'], $targetFilePath)) {
                $response->isSuccess = true;
                $response->data = $file;
            } else {
                $response->isSuccess = false;
                $response->data = "Sorry, there was an error uploading your file";
            }
        } else {
            $response->isSuccess = false;
            $response->data = "Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload";
        }

        return $response;
    }
}