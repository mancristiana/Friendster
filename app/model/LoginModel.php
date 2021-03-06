<?php

/**
 * Created by PhpStorm.
 * User: mancr
 * Date: 14-May-17
 * Time: 13:27
 */
class LoginModel {
    private $id;
    private $email;
    private $password;
    public $emailError;
    public $passwordError;
    public $recaptchaError;
    public $loginError;

    public function __construct(){
        $this->id = '';
        $this->email = '';
        $this->password = '';
        $this->emailError = '';
        $this->passwordError = '';
        $this->recaptchaError = '';
        $this->loginError = '';
    }

    public function validateEmail()
    {
        $this->emailError = ValidationManager::validateRequired($this->email);
        return ValidationManager::isValid($this->emailError);
    }

    public function validatePassword()
    {
        $this->passwordError = ValidationManager::validateRequired($this->password);
        return ValidationManager::isValid($this->passwordError);
    }

    public function validateRecaptcha($value)
    {
        $this->recaptchaError = ValidationManager::validateRecaptcha($value);
        return ValidationManager::isValid($this->recaptchaError);
    }

    public function verifyLogin() {
        $db_user = Database::getUser($this->email);
        $hashed_pw = $db_user->password;
        $this->id = $db_user->id;

        $is_valid = !empty($hashed_pw);
        $is_valid = $is_valid && password_verify($this->password, base64_decode($hashed_pw));

        if (!$is_valid) {
            $this->loginError = 'Username or password is wrong';
        }

        return $is_valid;

    }

    public function getProfile() {
        return Database::getProfile($this->id);
    }

    public function getEmail() {
        return htmlentities($this->email);
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return htmlentities($this->password);
    }

    public function setPassword($password) {
        $this->password = $password;
    }




}