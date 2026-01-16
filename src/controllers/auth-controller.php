<?php
require_once __DIR__ . '/../config/connect.php';

class AuthController {
    public function showLogin() {
        $pageTitle = "Login Page";
        include __DIR__ . '/../views/login-page.php';
    }
}
