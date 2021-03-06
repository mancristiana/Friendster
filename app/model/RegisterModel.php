<?php

/**
 * Created by PhpStorm.
 * User: mancr
 * Date: 14-May-17
 * Time: 19:46
 */
class RegisterModel {
    private $email;
    private $password;
    private $passwordVerify;

    private $emailError;
    private $passwordError;
    private $passwordVerifyError;

    private $recaptchaError;

    public function __construct() {
        $this->email = '';
        $this->password = '';
        $this->passwordVerify = '';
        $this->emailError = '';
        $this->passwordError = '';
        $this->passwordVerifyError = '';
        $this->recaptchaError = '';
    }

    public function registerUser() {
        $options = [
            'cost' => 11
        ];
        $hashed_pw = base64_encode(password_hash($this->password, PASSWORD_BCRYPT, $options));
        Database::createUser($this->email, $hashed_pw);
        $user_id = Database::getUser($this->email)->id;
        return $user_id;
    }

    public function validateEmail() {
        $this->emailError = ValidationManager::validateEmail($this->email);
        return ValidationManager::isValid($this->emailError);
    }

    public function validatePassword() {
        $this->passwordError = ValidationManager::validatePassword($this->password);
        return ValidationManager::isValid($this->passwordError);
    }

    public function verifyPasswords() {

        $this->passwordVerifyError = ValidationManager::validateRequired($this->passwordVerify);

        // Checks if the two user password input match
        if(ValidationManager::isValid($this->passwordVerifyError)
            && $this->password !== $this->passwordVerify) {
            $this->passwordVerifyError = " must match password";
        }

        return ValidationManager::isValid($this->passwordVerifyError);
    }

    public function validateRecaptcha($value)
    {
        $is_valid = RecaptchaManager::isRecaptchaValid($value);
        $this->recaptchaError = $is_valid ? '' : 'Please validate recapcha';

        return $is_valid;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return htmlentities($this->email);
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword() {
        return htmlentities($this->password);
    }

    /**
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPasswordVerify() {
        return htmlentities($this->passwordVerify);
    }

    /**
     * @param string $passwordVerify
     */
    public function setPasswordVerify($passwordVerify) {
        $this->passwordVerify = $passwordVerify;
    }

    /**
     * @return string
     */
    public function getEmailError() {
        return htmlentities($this->emailError);
    }

    /**
     * @param string $emailError
     */
    public function setEmailError($emailError) {
        $this->emailError = $emailError;
    }

    /**
     * @return string
     */
    public function getPasswordError() {
        return htmlentities($this->passwordError);
    }

    /**
     * @param string $passwordError
     */
    public function setPasswordError($passwordError) {
        $this->passwordError = $passwordError;
    }

    /**
     * @return string
     */
    public function getPasswordVerifyError() {
        return htmlentities($this->passwordVerifyError);
    }

    /**
     * @param string $passwordVerifyError
     */
    public function setPasswordVerifyError($passwordVerifyError) {
        $this->passwordVerifyError = $passwordVerifyError;
    }

    /**
     * @return string
     */
    public function getRecaptchaError() {
        return htmlentities($this->recaptchaError);
    }

    /**
     * @param string $recaptchaError
     */
    public function setRecaptchaError($recaptchaError) {
        $this->recaptchaError = $recaptchaError;
    }


}