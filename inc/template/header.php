<div class="header" uk-sticky="offset: 0; bottom: #top">
    <div class="nav-menu ">
        <center><ul>
           <a href="../newsfeed"><li class="menu-logo pointer"><img src="../media/logo.png"><span class="brand"> FSOCIAL</span></li></a>
           <li class="menu-search">
             <div class="control-search">
               <input type="text" name="search-ipt" class="search-ipt" placeholder="" autocomplete="off">
               <button type="submit" name="search-btn" class="search-btn pointer"><i class="fas fa-search"></i></button>
             </div>
             <div class="result" style='display:none'>
               <ul class='result-list'>
               </ul>
             </div>
           </li>
           <a href="../newsfeed"><li class="menu-home pointer" uk-tooltip="Trang chủ"><ion-icon name="home"></ion-icon></li></a>
           <span class="notifis" onclick="showNoti()"><?php $data->noti(); ?></span>
           <a href="../explore"><li class="explore pointer" uk-tooltip="Khám phá"><ion-icon name="planet"></ion-icon></li></a>
           <a href="../account?u=<?php echo $username ?>"><li class="account pointer" uk-tooltip="Tài khoản"><ion-icon name="person"></ion-icon></li></a>
           <a href="../logout.php" class="logout-btn"><li class="account pointer" uk-tooltip="Đăng xuất"><ion-icon name="log-out"></ion-icon></li></a>
        </ul></center>
    </div>
  </div>