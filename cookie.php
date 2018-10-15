<?php
   error_reporting(0);
   session_start();
   $username = $_SESSION['username'];
   $password = $_SESSION['password'];
   $id = $_SESSION['id'];
   setcookie('username',$username,time() + (86400 * 30));
   setcookie('password',$password,time() + (86400 * 30));
?>