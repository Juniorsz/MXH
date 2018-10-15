<?php  
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
    <title>FSocial Việt Nam</title>
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.6/css/uikit.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="../public/css/core.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
</head>
<body>
  <div class="wrapper">
  <!-- Header -->
    <?php include('../inc/template/header.php');?>
  <!-- End Header -->

  <!-- Container -->
  <div class="main-container">
     <div class="uk-text-center" uk-grid>
        <div class="uk-width-expand@m">
          <!-- Post status -->
           <div class="uk-card uk-card-default uk-card-body uk-main">
             <div class="up-status">
                <div class="status inline"><textarea name="status-posts" style="padding-right:0px !important;" class="status-posts emojiable-option" placeholder="Bạn đang nghĩ gì <?php echo $username; ?>?"></textarea><img id="preview" width="100" height="100" /></div>
                <button class="uk-button uk-button-primary uk-button-small up-posts" onclick="post()">ĐĂNG <i class="fas fa-paper-plane"></i></button>
                <div class="inline-sub edit upload-photo" uk-tooltip="Tải lên các hình ảnh của bạn"><span class="icon-up"><ion-icon name="image"></ion-icon> Hình ảnh </span></div>
                <div class="inline-sub edit icon" uk-tooltip="Icon"><ion-icon name="happy"></ion-icon> Icon</div>
                <div class="inline-sub edit" uk-tooltip="Thêm mới một video"><ion-icon name="videocam"></ion-icon> Video</div>
                <div class="inline-sub edit" uk-tooltip="Thêm mới một đoạn ghi âm"><ion-icon name="mic"></ion-icon> Ghi âm</div>
             </div>
           </div>
          <!-- End post status -->

          <!-- News -->
          <div class="main-newsfeed">
             <?php 
                if(isset($_SESSION['page'])){
                  $page = $_SESSION['page'];
                }
                else{
                  $page = 1;
                }
                $data->showData($page*20); 
             ?>
          </div>
          <button style='margin-bottom:20px !important' uk-tooltip='Xem thêm bài viết' class="more-posts" value="1" onclick="morePosts()"><i class="fas fa-ellipsis-h"></i></button>
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
    <input id="file" type="file" name="sortpic" onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])">
</div>
<link rel="stylesheet" type="text/css" href="http://wedgies.github.io/jquery-emoji-picker/css/jquery.emojipicker.css">
  <script type="text/javascript" src="http://wedgies.github.io/jquery-emoji-picker/js/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" src="http://wedgies.github.io/jquery-emoji-picker/js/jquery.emojipicker.js"></script>
  <!-- Emoji Data -->
  <link rel="stylesheet" type="text/css" href="http://wedgies.github.io/jquery-emoji-picker/css/jquery.emojipicker.a.css">
  <script type="text/javascript" src="http://wedgies.github.io/jquery-emoji-picker/js/jquery.emojipicker.a.js"></script>
  <script type="text/javascript">
    $(document).ready(function(e) {
      $('.emojiable-option').emojiPicker({
        width: '300px',
        height: '300px'
      });
    });
    function morePosts(){
      page = $('.more-posts').val();
      $.ajax({
          url:'../Controller/controller.php',
          method:'POST',
          data:{action:'morePosts',page:page-1+2},
          success:function(data){
              $('.main-newsfeed').html(data);
              $('.more-posts').val(page-1+2);
              $('html, body').animate({scrollTop: ($(window).scrollTop() + 300) + 'px'}, 300);
          }
      });
    }
  </script>
</html>
