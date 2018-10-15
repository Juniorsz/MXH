<?php
  session_start();
  session_destroy();
  setcookie('username','',time() + 0);
  setcookie('password','',time() + 0);
  header('Location: login');
?>