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
    <title>FSocial 404</title>
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
  <div class="header" uk-sticky="offset: 0; bottom: #top">
    <div class="nav-menu ">
        <center><ul>
           <a href=""><li class="menu-logo pointer"><img src="https://theme.crumina.net/html-olympus/img/logo.png"><span class="brand"> OLYMUS</span></li></a>
           <li class="menu-search">
             <form method="GET" class="control-search">
               <input type="text" name="search-ipt" class="search-ipt" autocomplete="off">
               <button type="submit" name="search-btn" class="search-btn pointer"><i class="fas fa-search"></i></button>
             </form>
           </li>
           <a href=""><li class="menu-home pointer" uk-tooltip="Newsfeed"><ion-icon name="home"></ion-icon></li></a>
           <a href=""><li class="explore pointer" uk-tooltip="Friends"><ion-icon name="person-add"></ion-icon></li></a>
           <span class="notifis" onclick="showNoti()"><?php $data->noti(); ?></span>
           <a href=""><li class="explore pointer" uk-tooltip="Explore"><ion-icon name="planet"></ion-icon></li></a>
           <a href=""><li class="account pointer" uk-tooltip="Account"><ion-icon name="person"></ion-icon></li></a>
           <a href="../logout.php" class="logout-btn"><li class="account pointer" uk-tooltip="Logout"><ion-icon name="log-out"></ion-icon></li></a>
        </ul></center>
    </div>
  </div>
  <!-- End Header -->

  <!-- Container -->
  <div class="main-container">
     <div class="uk-text-center" uk-grid>
        <div class="uk-width-expand@m">
          <!-- News -->
          <div class="main-newsfeed">
            <div class="uk-card uk-card-default lazy uk-card-body uk-main uk-news">
               <img src="../media/404.png">
            </div>
          </div>
          <!-- End news -->
        </div>
        <div class="uk-width-1-3@m">
           <div class="uk-card uk-card-default uk-card-body uk-clone uk-fixed" uk-sticky="offset: 10; bottom: #top">
             <h5>Có thể bạn biết?</h5>
             <div class="suggest-follow">
                <?php $data->followTable(); ?>
             </div>
             <button class="view-more-suggest" onclick="moreFollow()" style="border:none;background:transparent;color:#888da8;float:right;">Xem thêm <i class="fas fa-redo-alt"></i></button>
           </div>
           <div class="uk-card uk-card-default uk-card-body uk-clone uk-fixed" uk-sticky="offset: 340; bottom: #top">
             <h5>Top thịnh hành</h5>
             <p>#pewpew</p>
        </div>
     </div>
  </div>
</div>
</body>
    <!-- UIkit JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.6/js/uikit.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.6/js/uikit-icons.min.js"></script>
    <script src="https://unpkg.com/ionicons@4.2.2/dist/ionicons.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="../Public/js/main.js"></script>
    <button class="uk-button uk-button-default full-cmt-btn" style="display:none;" href="#modal-overflow" uk-toggle>Open</button>
    <div id="modal-overflow" uk-modal>
    <div class="uk-modal-dialog" style='border-radius:10px'>
    <button class="uk-modal-close-default close-comment-box" type="button" uk-close></button>
        <div class="uk-modal-body full-comment-list" uk-overflow-auto>
          <p class="show-commented">
            <div class="sk-cube-grid">
              <div class="sk-cube sk-cube1"></div>
              <div class="sk-cube sk-cube2"></div>
              <div class="sk-cube sk-cube3"></div>
              <div class="sk-cube sk-cube4"></div>
              <div class="sk-cube sk-cube5"></div>
              <div class="sk-cube sk-cube6"></div>
              <div class="sk-cube sk-cube7"></div>
              <div class="sk-cube sk-cube8"></div>
              <div class="sk-cube sk-cube9"></div>
            </div>
          </p>
        </div>
    </div>
    </div>
    <button class="uk-button uk-button-default upload-photo" style='display:none;' href="#modal-center" uk-toggle>Open</button>
    <div id="modal-center" class="uk-flex-top" uk-modal>
       <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <p>
          <input id="file" type="file" name="sortpic" onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])">
        </p>
    </div>
</div>
</html>
