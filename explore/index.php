<?php  
   //include('../cookie.php');
   include("../Model/model.php"); 
   $data = new Model;
   if(empty($_SESSION)){
      header('Location: ../login');
   }
   error_reporting(0);
   session_start();
   $username = $_SESSION['username'];
   $password = $_SESSION['password'];
   $id = $_SESSION['id'];
   setcookie('username',$username,time() + (86400 * 30));
   setcookie('password',$password,time() + (86400 * 30));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FSocial Explore</title>
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.6/css/uikit.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="../Public/css/core.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
</head>
<body>
  <div class="wrapper">
  <!-- Header -->
  <?php include('../inc/template/header.php'); ?>
  <!-- End Header -->

  <!-- Container -->
  <div class="main-container">
     <div class="uk-text-center" uk-grid>
        <div class="uk-width-expand@m">
          <!-- News -->
          <div class="main-newsfeed">
             <div class="uk-card uk-card-default lazy uk-card-body uk-main uk-news">
                <div class="uk-child-width-1-3@m" uk-grid uk-lightbox="animation: slide">
                  <?php $data->exploreData(); ?>
                </div>
             </div>
          </div>
          <!-- End news -->
        </div>
        <?php include('../inc/template/follow_table.php'); ?>
  </div>
</div>
</body>
    <!-- UIkit JS -->
    <script src="https://unpkg.com/ionicons@4.2.2/dist/ionicons.js"></script>
    <script src="../public/js/uikit.min.js"></script>
    <script src="../public/js/uikit-icons.min.js"></script>
    <script src="../public/js/jquery.min.js"></script>
</div>
</html>
