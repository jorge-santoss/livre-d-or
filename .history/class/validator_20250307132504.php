<?php
class Validator {
    public static function testInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validatePassword($password) {
        return strlen($password) >= 8 && 
            preg_match('/[A-Z]/', $password) &&
            preg_match('/[a-z]/', $password) &&
            preg_match('/[0-9]/', $password) &&
            preg_match('/[^\w]/', $password);
    }

    public static function validateUsername($nom) {
        return preg_match('/^[a-zA-Z0-9_]*$/', $nom) && strlen($nom <= 10;
    }
}
?>