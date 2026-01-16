<?php

session_start();      // start the session first
$_SESSION = [];        // clear all session variables
session_unset();       // optional, ensures session variables cleared
session_destroy();     // destroy the session
setcookie(session_name(), '', time() - 3600, '/'); // delete session cookie

header("Location: ../../public/login-page.php");
exit();
?>
