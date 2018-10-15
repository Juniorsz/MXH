<?php
  session_start();
  $_SESSION['postID'] = $_GET['id'];
  header("Location: ../posts");
?>