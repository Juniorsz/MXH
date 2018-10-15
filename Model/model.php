<?php
   if(!isset($_SESSION)) 
   { 
       session_start();
   }
   error_reporting(0);
   include("../inc/connect.php");
   include("../cookie.php");
   /* Start Model */
   class Model extends Connection
   {
       public function checkData($data)
       {
           return $data = addslashes(htmlspecialchars($data));
       }
       public function postStatus($content,$time)
       {
           if(!trim($content) || $content == ''){
               return false;
           }
           else{
               global $id;
               $stsm = $this->connect->prepare("INSERT INTO post(content,time_post,user_id) VALUES('{$content}','$time',$id)");
               $stsm->execute();
           }
       }
       public function uploadPhoto($path,$time){
           global $id;
           $stsm = $this->connect->prepare("INSERT INTO photo(photo,user_id,time_post) VALUES('{$path}',$id,'{$time}')");
           $stsm->execute();
       }
       public function showData($limit)
       {
           global $id;
           global $username;
           $newsfeed = $this->connect->prepare("SELECT * FROM post ORDER BY id DESC LIMIT $limit");
           $newsfeed->execute();
           foreach($newsfeed->fetchAll(PDO::FETCH_OBJ) as $news){
               $check = $this->connect->prepare("SELECT * FROM follow WHERE id_follow=$id AND id_followed=$news->user_id");
               $check->execute();
               if($check->rowCount() == 1){
                $account = $this->connect->prepare("SELECT * FROM account WHERE id=$id");
                $account->execute();
                foreach($account->fetchAll(PDO::FETCH_OBJ) as $show){
                    $show->avatar;
                }
                $_SESSION['avatar'] = $show->avatar;
                $author = $this->connect->prepare("SELECT * FROM account WHERE id=$news->user_id");
                $author->execute();
                ?>
                     <div class="uk-card uk-card-default lazy uk-card-body uk-main uk-news">
                       <div class="up-status">
                         <div class="news author-infor">
                            <div class="action" onclick="postAction(<?php echo $news->id ?>)">
                               <i href="" class="action-icon" uk-icon="more"></i>
                               <ul class="action-sub uk-iconnav uk-iconnav-vertical <?php echo 'menu-sub-'.$news->id ?>">
                                  <li><button type='submit' value="<?php echo $news->id; ?>"><i uk-icon="icon: file-edit"></i></button></li>
                                  <li><button type="submit" value="<?php echo $news->id; ?>" onclick="deletePost(this)"><i uk-icon="icon: trash"></i></button></li>
                               </ul>
                            </div>
                            <?php
                              foreach($author->fetchAll(PDO::FETCH_OBJ) as $authorInfor){
                                  ?>
                                     <img class="circle small-avatar" src="<?php echo $authorInfor->avatar ?>">
                                     <a href="../account?u=<?php echo $authorInfor->username ?>"><p class="author-name"><?php echo $authorInfor->username ?><?php echo $authorInfor->verify == 1 ? "<span class='verify'> <i class='fas fa-check-circle' uk-tooltip='Tài khoản đã xác minh'></i></span>" : ''; ?></p></a>
                                  <?php
                              }
                            ?>
                            <a class='redirect-posts' href="../sess?id=<?php echo $news->id ?>"><small class="author-name"> <?php echo getTimeAgo($news->time_post);?> <i class="fas fa-globe-americas"></i></small></a>
                         </div>
                      <div class="news-container">
                      <p class="news-title">
                         <?php 
                            $ex = explode("\n",$news->content); 
                            foreach ($ex as $value) {
                               $value = icon($value);
                               echo "<p class='news-content' style='margin:0px 0px 10px 5px !important;'>". hashtag($value) ."</p>";
                            }
                            $checkPhoto = $this->connect->prepare("SELECT * FROM photo WHERE user_id=$authorInfor->id AND time_post='$news->time_post' LIMIT 1");
                            $checkPhoto->execute();
                            if($checkPhoto->rowCount() == 1){
                              foreach ($checkPhoto->fetchAll(PDO::FETCH_OBJ) as $photo) {
                                  ?>
                                    <div class="uk-text-center">
                                       <div class="uk-inline-clip uk-transition-toggle" tabindex="0" style="width:100%;height:100%">
                                        <div uk-lightbox>
                                          <a style='width:100%;padding:0;border:none;' class='uk-button uk-button-default' href='<?php echo "../media/$photo->photo"; ?>' data-caption='Photo of <?php echo $authorInfor->username ?>'>
                                             <img class='photo-uploaded uk-transition-scale-up uk-transition-opaque' src='<?php echo "../media/$photo->photo"; ?>'>
                                           </a>
                                        </div>
                                       </div>
                                     </div>
                                  <?php
                               }
                            }
                            $comment = $this->connect->prepare("SELECT * FROM comment WHERE post_id=$news->id");
                            $comment->execute();
                            $limitComment = $this->connect->prepare("SELECT * FROM(SELECT * FROM comment WHERE post_id=$news->id ORDER BY id DESC LIMIT 5) comment ORDER BY id ASC");
                            $limitComment->execute();
                            $checkReact = $this->connect->prepare("SELECT * FROM react WHERE post_id=$news->id AND user_id=$id");
                            $checkReact->execute();
                          ?>
                     </p>
                     <div class="button-react">
                       <span class="soical btn-react"><button type="submit" name="love" value="<?php echo $news->id; ?>" onclick="react(this)" class="love-btn animated <?php echo $checkReact->rowCount() == 1 ? 'reacted' : '';  ?>"><i class="fas fa-heart"></i></button><span class="countLoves"><?php echo number_format($news->loves,0,',','.'); ?> Thích</span></span>
                       <span class="social btn-cmt"><button value="<?php echo $news->id; ?>" onclick="showComment(this)" type="submit" name="comment" class="comment-btn animated"><i class="fas fa-comment-alt"></i></button> <span class="countComments"><?php echo $comment->rowCount(); ?> Bình luận</span></span>
                     </div>
                     <div class="show-comment <?php echo "cmt-$news->id"; ?>" style="margin-top:8px;">
                        <?php 
                           if($comment->rowCount() == 0){
                               echo "<p class='null-comment' style='margin:6px !important;'>Chưa có bình luận nào :(</p>";
                           }
                           else{
                                foreach ($limitComment->fetchAll(PDO::FETCH_OBJ) as $key) {
                                   $authorComment = $this->connect->prepare("SELECT * FROM account WHERE id=$key->user_id");
                                   $authorComment->execute();
                                   foreach($authorComment->fetchAll(PDO::FETCH_OBJ) as $authorCommentInfor){
                                       $key->content = icon($key->content);
                                       ?>
                                          <li class="object-comment"><img class="small-avatar circle float" src="<?php echo $authorCommentInfor->avatar; ?>"><a href="../account?u=<?php echo $authorCommentInfor->username ?>"><p class="comment-name"><?php echo $authorCommentInfor->username; ?><?php echo $authorCommentInfor->verify == 1 ? "<span class='verify'> <i class='fas fa-check-circle' uk-tooltip='Tài khoản đã xác minh'></i></span>" : ''; ?></a><small class="time-details"> ( <?php echo  getTimeAgo($key->time_post); ?> )</small></p>
                                            <div class="comments"><?php echo $key->content; ?></div>
                                          </li>
                                       <?php
                                   }
                                 }
                               if($comment->rowCount() > 5){
                                   $count = $comment->rowCount() - $limitComment->rowCount();
                                   echo "<button value='$news->id' class='more-comment' onclick='moreComment(this)'>Xem các bình luận trước ( $count )</button>";
                               }
                           }
                        ?>
                     </div>
                     <div class="<?php echo "insertcmt-$news->id"; ?> comment-area">
                        <img class="small-avatar circle float" src="<?php echo $show->avatar; ?>">
                        <input type="text" class="<?php echo $news->id; ?> comment-value" placeholder=" Viết bình luận..." name="comment-value">
                        <button type="submit" value="<?php echo $news->id; ?>" name="insertComment" class="insert-comment" onclick="upComment(this)"><i class="fas fa-angle-right"></i></button>
                     </div>
                   </div>
                </div>
              </div>
                  <?php
                }
             }
       }
       public function showDataId($postId)
       {
           ob_flush();
           global $id;
           global $username;
           $newsfeed = $this->connect->prepare("SELECT * FROM post WHERE id=$postId ORDER BY id DESC");
           $newsfeed->execute();
           if($newsfeed->rowCount() == 0){
               ?>
               <div class="uk-card uk-card-default lazy uk-card-body uk-main uk-news">
                  <img src="../media/404.png" width="100%">
                </div>
               <?php
           }
           foreach($newsfeed->fetchAll(PDO::FETCH_OBJ) as $news){
               $check = $this->connect->prepare("SELECT * FROM follow WHERE id_follow=$id AND id_followed=$news->user_id");
               $check->execute();
               if($check->rowCount() == 1){
                $account = $this->connect->prepare("SELECT * FROM account WHERE id=$id");
                $account->execute();
                foreach($account->fetchAll(PDO::FETCH_OBJ) as $show){
                    $show->avatar;
                }
                $_SESSION['avatar'] = $show->avatar;
                $author = $this->connect->prepare("SELECT * FROM account WHERE id=$news->user_id");
                $author->execute();
                ?>
                     <div class="uk-card uk-card-default lazy uk-card-body uk-main uk-news">
                       <div class="up-status">
                         <div class="news author-infor">
                            <div class="action">
                               <i href="" class="action-icon" uk-icon="more"></i>
                               <ul class="action-sub uk-iconnav uk-iconnav-vertical">
                                  <li><button type='submit' value="<?php echo $news->id; ?>"><i uk-icon="icon: file-edit"></i></button></li>
                                  <li><button type="submit" value="<?php echo $news->id; ?>" onclick="deletePost(this)"><i uk-icon="icon: trash"></i></button></li>
                               </ul>
                            </div>
                            <?php
                              foreach($author->fetchAll(PDO::FETCH_OBJ) as $authorInfor){
                                  ?>
                                     <img class="circle small-avatar" src="<?php echo $authorInfor->avatar ?>">
                                     <a href="../account?u=<?php echo $authorInfor->username ?>"><p class="author-name"><?php echo $authorInfor->username ?><?php echo $authorInfor->verify == 1 ? "<span class='verify'> <i class='fas fa-check-circle' uk-tooltip='Tài khoản đã xác minh'></i></span>" : ''; ?></p></a>
                                  <?php
                              }
                            ?>
                            <small class="author-name"> <?php echo getTimeAgo($news->time_post);?> <i class="fas fa-globe-americas"></i></small>
                         </div>
                      <div class="news-container">
                      <p class="news-title">
                         <?php 
                            $ex = explode("\n",$news->content); 
                            foreach ($ex as $value) {
                               echo "<p class='news-content' style='margin:0px 0px 10px 5px !important;'>" .hashtag($value) ."</p>";
                            }
                            $checkPhoto = $this->connect->prepare("SELECT * FROM photo WHERE user_id=$authorInfor->id AND time_post='$news->time_post' LIMIT 1");
                            $checkPhoto->execute();
                            if($checkPhoto->rowCount() == 1){
                              foreach ($checkPhoto->fetchAll(PDO::FETCH_OBJ) as $photo) {
                                  ?>
                                    <div class="uk-text-center">
                                       <div class="uk-inline-clip uk-transition-toggle" tabindex="0" style="width:100%;height:100%">
                                        <div uk-lightbox>
                                          <a style='width:100%;padding:0;border:none;' class='uk-button uk-button-default' href='<?php echo "../media/$photo->photo"; ?>' data-caption='Photo of <?php echo $authorInfor->username ?>'>
                                             <img class='photo-uploaded uk-transition-scale-up uk-transition-opaque' src='<?php echo "../media/$photo->photo"; ?>'>
                                           </a>
                                        </div>
                                       </div>
                                     </div>
                                  <?php
                               }
                            }
                            $comment = $this->connect->prepare("SELECT * FROM comment WHERE post_id=$news->id");
                            $comment->execute();
                            $limitComment = $this->connect->prepare("SELECT * FROM(SELECT * FROM comment WHERE post_id=$news->id ORDER BY id DESC LIMIT 20) comment ORDER BY id ASC");
                            $limitComment->execute();
                            $checkReact = $this->connect->prepare("SELECT * FROM react WHERE post_id=$news->id AND user_id=$id");
                            $checkReact->execute();
                          ?>
                     </p>
                     <div class="button-react">
                       <span class="soical btn-react"><button type="submit" name="love" value="<?php echo $news->id; ?>" onclick="reactId(this)" class="love-btn animated <?php echo $checkReact->rowCount() == 1 ? 'reacted' : '';  ?>"><i class="fas fa-heart"></i></button><span class="countLoves"><?php echo number_format($news->loves,0,',','.'); ?> Thích</span></span>
                       <span class="social btn-cmt"><button value="<?php echo $news->id; ?>" onclick="showComment(this)" type="submit" name="comment" class="comment-btn animated"><i class="fas fa-comment-alt"></i></button> <span class="countComments"><?php echo $comment->rowCount(); ?> Bình luận</span></span>
                     </div>
                     <div class="show-comment-list <?php echo "cmt-$news->id"; ?>" style="margin-top:8px;">
                        <?php 
                           if($comment->rowCount() == 0){
                               echo "<p class='null-comment' style='margin:6px !important;'>Chưa có bình luận nào :(</p>";
                           }
                           else{
                                foreach ($limitComment->fetchAll(PDO::FETCH_OBJ) as $key) {
                                   $authorComment = $this->connect->prepare("SELECT * FROM account WHERE id=$key->user_id");
                                   $authorComment->execute();
                                   foreach($authorComment->fetchAll(PDO::FETCH_OBJ) as $authorCommentInfor){
                                       $key->content = icon($key->content);
                                       ?>
                                          <li class="object-comment" id="comment<?php echo $key->id ?>"><img class="small-avatar circle float" src="<?php echo $authorCommentInfor->avatar; ?>"><a href="../account?u=<?php echo $authorCommentInfor->username ?>"><p class="comment-name"><?php echo $authorCommentInfor->username; ?><?php echo $authorCommentInfor->verify == 1 ? "<span class='verify'> <i class='fas fa-check-circle' uk-tooltip='Tài khoản đã xác minh'></i></span>" : ''; ?></a><small class="time-details"> ( <?php echo  getTimeAgo($key->time_post); ?> )</small></p>
                                            <div class="comments"><?php echo $key->content; ?></div>
                                          </li>
                                       <?php
                                   }
                                 }
                               if($comment->rowCount() > 20){
                                   $count = $comment->rowCount() - $limitComment->rowCount();
                                   echo "<button value='$news->id' class='more-comment' onclick='moreComment(this)'>Xem các bình luận trước ( $count )</button>";
                               }
                           }
                        ?>
                     </div>
                     <div class="comment-area-list">
                        <img class="small-avatar circle float" src="<?php echo $show->avatar; ?>">
                        <input type="text" class="<?php echo $news->id; ?> comment-value" placeholder=" Viết bình luận..." name="comment-value">
                        <button type="submit" value="<?php echo $news->id; ?>" name="insertComment" class="insert-comment" onclick="upCommentPost(this)"><i class="fas fa-angle-right"></i></button>
                     </div>
                   </div>
                </div>
              </div>
                  <?php
                }
                else{
                    ?>
                    <div class="uk-card uk-card-default lazy uk-card-body uk-main uk-news">
                      <img src="../media/404.png" width="100%">
                    </div>
                    <?php
                }
            }
       }
       public function comment($content,$postId,$time)
       {
           if(!trim($content) || $content == ''){
               return false;
           }
           else{
              global $id;
              $stsm = $this->connect->prepare("INSERT INTO comment(content,post_id,user_id,time_post) VALUES('{$content}',$postId,$id,'{$time}')");
              if($stsm->execute()){
                  $comment = $this->connect->prepare("SELECT * FROM post WHERE id=$postId");
                  $comment->execute();
                  foreach ($comment->fetchAll(PDO::FETCH_OBJ) as $rows) {
                      $rows->user_id;
                  }
                  if($id == $rows->user_id){
                      return false;
                  }
                  else{
                     $checkNoti = $this->connect->prepare("SELECT * FROM noti WHERE id_action=$id AND id_receive=$rows->user_id AND post_id=$postId AND type=1");
                     $checkNoti->execute();
                     if($checkNoti->rowCount() > 0)
                     {
                         $updateNoti = $this->connect->prepare("UPDATE noti SET status=0 WHERE id_action=$id AND id_receive=$rows->user_id AND post_id=$postId AND type=1 ");
                         $updateNoti->execute();
                     }
                     else
                     {
                         $sendNoti = $this->connect->prepare("INSERT INTO noti(id_action,id_receive,content,post_id,time_post,type) VALUES($id,$rows->user_id,'đã bình luận bài viết của bạn',$postId,'$time',1)");
                         $sendNoti->execute();   
                     }
                  }
              }
           }
       }
       public function noti(){
           global $id;
           $stsm = $this->connect->prepare("SELECT * FROM noti WHERE id_receive=$id ORDER BY id DESC LIMIT 10");
           $stsm->execute();
           $countNoti = $this->connect->prepare("SELECT * FROM noti WHERE id_receive=$id AND status=0 ORDER BY id DESC");
           $countNoti->execute();
           ?>
           <li class="menu-noti pointer"><ion-icon name="heart"></ion-icon><span class="uk-badge"><?php echo $countNoti->rowCount(); ?></span>
             <ul class="menu-noti-child">
               <li class="menu-noti-sub">
                  <?php
                     if($stsm->rowCount() == 0){
                        echo "<p class='null-noti'>Không có thông báo !</p>";
                     }
                     else{
                        foreach ($stsm->fetchAll(PDO::FETCH_OBJ) as $rows) {
                           $getInfor = $this->connect->prepare("SELECT * FROM account WHERE id=$rows->id_action");
                           $getInfor->execute();
                           foreach ($getInfor->fetchAll(PDO::FETCH_OBJ) as $key) {
                             if($rows->type == 2){
                                 $check = $this->connect->prepare("SELECT * FROM follow WHERE id_follow=$id AND id_followed=$rows->id_action");
                                 $check->execute();
                                ?>
                                    <a href="">
                                       <div class="sub-noti">
                                       <img src="<?php echo $key->avatar ?>">
                                       <a href="../account?u=<?php echo $key->username ?>"><strong><?php echo $key->username  ?></strong></a>
                                    </a>
                                    <?php echo $rows->content; ?><small> ( <?php echo getTimeAgo($rows->time_post) ?> ) </small>
                                    <?php 
                                       if($check->rowCount() == 1){
                                          ?>
                                            <button value="<?php echo $rows->id_action ?>" type="submit" onclick="unfollow(this)" class="follow-btn noti-follow noti-follow" name="follow"> Đang theo dõi</button>
                                          <?php
                                       } 
                                       else{
                                           ?>
                                             <button value="<?php echo $rows->id_action ?>" type="submit" onclick="follow(this)" class="follow-btn noti-follow noti-follow" name="follow"> Theo dõi</button>
                                           <?php
                                       }
                                    ?>
                                    </div>
                                <?php
                             }
                             else{
                                ?>
                                   <a href="../sess?id=<?php echo $rows->post_id ?>">
                                    <div class="sub-noti">
                                        <img src="<?php echo $key->avatar ?>">
                                        <strong><?php echo $key->username . ' '?></strong><?php echo $rows->content; ?><small> ( <?php echo getTimeAgo($rows->time_post) ?> ) </small>
                                    </div>
                                   </a>
                                <?php
                             }
                           }
                        }
                     }
                  ?>
                </li>
             </ul>
           </li>
           <?php
       }
       public function clearNoti(){
           global $id;
           $stsm = $this->connect->prepare("UPDATE noti SET status=1 WHERE id_receive=$id");
           $stsm->execute();
       }
       public function fullComment($id){
           $stsm = $this->connect->prepare("SELECT * FROM comment WHERE post_id=$id");
           $stsm->execute();
           foreach ($stsm->fetchAll(PDO::FETCH_OBJ) as $rows) {
               $authorComment = $this->connect->prepare("SELECT * FROM account WHERE id=$rows->user_id");
               $authorComment->execute();
               foreach($authorComment->fetchAll(PDO::FETCH_OBJ) as $authorCommentInfor){
                   ?>
                     <li class="object-comment"><img class="small-avatar circle float" src="<?php echo $authorCommentInfor->avatar ?>"><p class="comment-name"><?php echo $authorCommentInfor->username; ?><?php echo $authorCommentInfor->verify == 1 ? "<span class='verify'> <i class='fas fa-check-circle' uk-tooltip='Tài khoản đã xác minh'></i></span>" : ''; ?><small class="time-details"> ( <?php echo getTimeAgo($rows->time_post) ?> ) </small></p>
                        <div class="comments"><?php echo $rows->content; ?></div>
                     </li>
                   <?php
               }
           }
       }
       public function login(){
           ?>
              <form method="POST" style="background:#fff;padding:60px;border-radius:10px;">
                <div class="uk-margin">
                  <div class="uk-inline uk-width-1-1">
                    <span class="uk-form-icon" uk-icon="icon: user"></span>
                    <input class="uk-input" type="text" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['username'] : ''; ?>" name="username" style="border:none;border-radius:10px;background:#f2f2f2;color:#999;">
                  </div>
               </div>
               <div class="uk-margin">
                 <div class="uk-inline uk-width-1-1">
                   <span class="uk-form-icon" uk-icon="icon: lock"></span>
                   <input class="uk-input" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; ?>" type="text" name='password' style="border:none;border-radius:10px;background:#f2f2f2;color:#999;">
                </div>
               </div>
               <button style="background:#ff5e3a;" type="submit" name='login' data-scroll href="#items" class="btn btn-purple btn-nm uk-width-1-1">ĐĂNG NHẬP</button>
               <p class="login-message">Bạn chưa tài khoản? <a href="../signup">Đăng kí ngay</a>
            <?php
               include("../inc/cookie.php");
               if(isset($_COOKIE['username']) && isset($_COOKIE['password'])){
                   $username = $_COOKIE['username'];
                   $password = $_COOKIE['password'];
                   if(!trim($username) || !trim($password) || $username == '' || $password == ''){
                       return false;
                   }
                   else{
                       $stsm = $this->connect->prepare("SELECT * FROM account WHERE username='{$username}' AND password='{$password}'");
                       $stsm->execute();
                       if($stsm->rowCount() == 1){
                          $account = $this->connect->prepare("SELECT * FROM account WHERE username='$username'");
                          $account->execute();
                          foreach ($account->fetchAll(PDO::FETCH_OBJ) as $rows) {
                              $_SESSION['username'] = $rows->username;
                              $_SESSION['password'] = $password;
                              $_SESSION['id'] = $rows->id;
                          }
                          $followSelf = $this->connect->prepare("SELECT * FROM follow WHERE id_follow=$rows->id AND id_followed=$rows->id");
                          $followSelf->execute();
                          if($followSelf->rowCount() == 0){
                            $insertFollowSelf = $this->connect->prepare("INSERT INTO follow(id_follow,id_followed) VALUES($rows->id,$rows->id)");
                            $insertFollowSelf->execute();
                          }
                          header('Location: ../newsfeed');
                       }
                   }
               }
               else{
                 if(isset($_POST['login'])){
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    if(!trim($username) || !trim($password) || $username == '' || $password == ''){
                        return false;
                    }
                    else{
                        $stsm = $this->connect->prepare("SELECT * FROM account WHERE username='{$username}' AND password='{$password}'");
                        $stsm->execute();
                        if($stsm->rowCount() == 1){
                            $account = $this->connect->prepare("SELECT * FROM account WHERE username='$username'");
                            $account->execute();
                            foreach ($account->fetchAll(PDO::FETCH_OBJ) as $rows) {
                                $_SESSION['username'] = $rows->username;
                                $_SESSION['password'] = $password;
                                $_SESSION['id'] = $rows->id;
                            }
                            $followSelf = $this->connect->prepare("SELECT * FROM follow WHERE id_follow=$rows->id AND id_followed=$rows->id");
                            $followSelf->execute();
                            if($followSelf->rowCount() == 0){
                               $insertFollowSelf = $this->connect->prepare("INSERT INTO follow(id_follow,id_followed) VALUES($rows->id,$rows->id)");
                               $insertFollowSelf->execute();
                            }
                            setcookie('username',$username,time() + (86400 * 30));
                            setcookie('password',$password,time() + (86400 * 30));
                            header('Location: ../newsfeed');
                        }
                        else{
                            echo "<p class='login-message'>Sai tài khoản hoặc mật khẩu</p>";
                        }
                    }
                }
            }
       }
       public function signup(){
        ?>
        <form method="POST" style="background:#fff;padding:60px;border-radius:10px;">
          <div class="uk-margin">
            <div class="uk-inline uk-width-1-1">
              <span class="uk-form-icon" uk-icon="icon: user"></span>
              <input class="uk-input" type="text" name="username" style="border:none;border-radius:10px;background:#f2f2f2;color:#999;font-size:14px;" placeholder="Nhập tên tài khoản">
            </div>
         </div>
         <div class="uk-margin">
           <div class="uk-inline uk-width-1-1">
             <span class="uk-form-icon" uk-icon="icon: lock"></span>
             <input class="uk-input" type="text" name='password' style="border:none;border-radius:10px;background:#f2f2f2;color:#999;font-size:14px;" placeholder="Nhập mật khẩu">
          </div>
          <div class="uk-margin">
           <div class="uk-inline uk-width-1-1">
             <span class="uk-form-icon" uk-icon="icon: lock"></span>
             <input class="uk-input" type="text" name='repassword' style="border:none;border-radius:10px;background:#f2f2f2;color:#999;font-size:14px;" placeholder="Nhập lại mật khẩu">
          </div>
         </div>
         <center><div class="g-recaptcha" style="width: 100%;margin-bottom:10px" data-sitekey="6LccQnEUAAAAABNb2nR1r6RucjwkkUpmBTMomCku"></div></center>
         <button style="background:#ff5e3a;" type="submit" name='signup' data-scroll href="#items" class="btn btn-purple btn-nm uk-width-1-1">ĐĂNG KÝ</button>
         <?php
         if(isset($_POST['signup'])){
            $captcha = $_POST['g-recaptcha-response'];
            $username = addslashes(htmlspecialchars($_POST['username']));
            $password = addslashes(htmlspecialchars($_POST['password']));
            $repassword = addslashes(htmlspecialchars($_POST['repassword']));
            $pattern = '/^[a-zA-Z0-9_.]+$/';
            $subject = $username;
            $checkChar = preg_match($pattern, $subject,$matches);
            $stsm = $this->connect->prepare("SELECT * FROM account WHERE username='$username'");
            $stsm->execute();
            if($stsm->rowCount() == 1){
                echo "<p class='login-message'>Tên người dùng này không khả dụng. Vui lòng thử tên khác.</p>";
            }
            elseif(!trim($username) || !trim($password) || !trim($repassword) || $username == '' || $password == '' || $repassword == ''){
                echo "<p class='login-message'>Không được để trống.</p>";
            }
            elseif(!$checkChar){
                echo "<p class='login-message'>Tên người dùng này không khả dụng. Vui lòng thử tên khác.</p>";
            }
            elseif(!$captcha){
                echo "<p class='login-message'>Vui lòng xác nhận Captcha</p>";
            }
            elseif($password !== $repassword){
                echo "<p class='login-message'>Mật khẩu không trùng khớp</p>";
            }
            elseif(strlen($username) > 16 && strlen($username) < 4){
                echo "<p class='login-message'>Tên tài khoản phải dài từ 4-16 kí tự</p>";
            }
            else{
                $signup = $this->connect->prepare("INSERT INTO account(username,password,avatar) VALUES('$username','$password','../media/default-user.png')");
                if($signup->execute()){
                    echo "<p class='login-message'>Đăng ký thành công ! <a href='../login'>Đăng nhập ngay</a></p>";
                }
            }
         }
       }
       public function getAvatar(){
           global $id;
           $stsm = $this->connect->prepare("SELECT avatar FROM account WHERE id=$id");
           $stsm->execute();
           foreach ($stsm->fetchAll(PDO::FETCH_OBJ) as $rows) {
               echo $rows->avatar;
           }
       }
       public function followTable(){
           global $id;
           $stsm = $this->connect->prepare("SELECT * FROM account WHERE id !=$id LIMIT 5");
           $stsm->execute();
           foreach ($stsm->fetchAll(PDO::FETCH_OBJ) as $rows) {
               $check = $this->connect->prepare("SELECT * FROM follow WHERE id_followed=$rows->id AND id_follow =$id");
               $check->execute();
               if($check->rowCount() == 0){
                   ?>
                        <li class="account-suggest">
                            <img src="<?php echo $rows->avatar ?>" class="small-avatar circle avt-margin">
                            <a href="../account?u=<?Php echo $rows->username ?>"><span class="account-name"><?php echo $rows->username ?><?php echo $rows->verify == 1 ? "<span class='verify'> <i class='fas fa-check-circle' uk-tooltip='Tài khoản đã xác minh'></i></span>" : ''; ?></span></a>
                            <span class="follow-button">
                            <button value="<?php echo $rows->id ?>" type="submit" onclick="follow(this)" class="follow-btn" name="follow">Theo dõi</button>
                            </span>
                        <li>
                    <?php
                }
            }
       }
       public function follow($id_followed,$time){
           global $id;
           $stsm = $this->connect->prepare("SELECT * FROM follow WHERE id_followed=$id_followed AND id_follow=$id LIMIT 5");
           $stsm->execute();
           if($stsm->rowCount() == 1){
               return false;
           }
           else{
               $follow = $this->connect->prepare("INSERT INTO follow(id_follow,id_followed) VALUES($id,$id_followed)");
               if($follow->execute()){
                if($id == $id_followed){
                    return false;
                }
                else{
                   $sendNoti = $this->connect->prepare("INSERT INTO noti(id_action,id_receive,content,time_post,type) VALUES($id,$id_followed,'đã theo dõi bạn','$time',2)");
                   $sendNoti->execute();
                }
              }
           }
       }
       public function unfollow($id_followed){
           global $id;
           $stsm = $this->connect->prepare("SELECT * FROM follow WHERE id_followed=$id_followed AND id_follow=$id");
           $stsm->execute();
           if($stsm->rowCount() == 1){
               if($id_followed == 1){
                   return false;
               }
               else{
                  $unfollow = $this->connect->prepare("DELETE FROM follow WHERE id_followed=$id_followed");
                  if($unfollow->execute()){
                      $deleteNoti = $this->connect->prepare("DELETE FROM noti WHERE id_action=$id AND id_receive=$id_followed");
                      $deleteNoti->execute();
                  }
               }
           }
           else{
               return false;
           }
       }
       public function moreFollow(){
          global $id;
          $stsm = $this->connect->prepare("SELECT * FROM account WHERE id !=$id ORDER BY RAND() LIMIT 5");
          $stsm->execute();
          foreach ($stsm->fetchAll(PDO::FETCH_OBJ) as $rows) {
             $check = $this->connect->prepare("SELECT * FROM follow WHERE id_followed=$rows->id AND id_follow=$id");
             $check->execute();
             if($check->rowCount() == 0){
                ?>
                <li class="account-suggest">
                  <img src="<?php echo $rows->avatar ?>" class="small-avatar circle avt-margin">
                  <a href="../account?u=<?php echo $rows->username ?>"><span class="account-name"><?php echo $rows->username ?><?php echo $rows->verify == 1 ? "<span class='verify'> <i class='fas fa-check-circle' uk-tooltip='Tài khoản đã xác minh'></i></span>" : ''; ?></span></a>
                  <span class="follow-button">
                  <button value="<?php echo $rows->id ?>" type="submit" onclick="follow(this)" class="follow-btn" name="follow">Theo dõi</button>
                  </span>
                <li>
             <?php
             }
          }
       }
       public function react($postId,$time){
           global $id;
           $stsm = $this->connect->prepare("SELECT * FROM react WHERE post_id=$postId AND user_id=$id");
           $stsm->execute();
           if($stsm->rowCount() == 1){
               $delete = $this->connect->prepare("DELETE FROM react WHERE post_id=$postId AND user_id=$id");
               $delete->execute();
               $updatePost = $this->connect->prepare("UPDATE post SET loves=loves-1 WHERE id=$postId");
               $updatePost->execute();
               $get = $this->connect->prepare("SELECT * FROM post WHERE id=$postId");
               $get->execute();
               foreach ($get->fetchAll(PDO::FETCH_OBJ) as $key) {
                   $deleteNoti = $this->connect->prepare("DELETE FROM noti WHERE id_action=$id AND id_receive=$key->user_id AND type=0 AND post_id=$postId");
                   $deleteNoti->execute();
               }
           }
           else{
               $updatePost = $this->connect->prepare("UPDATE post SET loves=loves+1 WHERE id=$postId");
               $updatePost->execute();
               $react = $this->connect->prepare("INSERT INTO react(post_id,user_id) VALUES($postId,$id)");
               if($react->execute()){
                  $getInfor = $this->connect->prepare("SELECT * FROM post WHERE id=$postId");
                  $getInfor->execute();
                  foreach ($getInfor->fetchAll(PDO::FETCH_OBJ) as $rows) {
                      $rows->user_id;
                  }
                  if($id == $rows->user_id){
                      return false;
                  }
                  else{
                     $sendNoti = $this->connect->prepare("INSERT INTO noti(id_action,id_receive,content,post_id,time_post,type) VALUES($id,$rows->user_id,'đã thích bài viết của bạn',$postId,'$time',0)");
                     $sendNoti->execute();
                  }
               }
           }
       }
       public function exploreData(){
           $stsm = $this->connect->prepare("SELECT * FROM photo ORDER BY RAND()");
           $stsm->execute();
           if($stsm->rowCount() > 0)
           {
                foreach ($stsm->fetchAll(PDO::FETCH_OBJ) as $rows) {
                    ?>
                        <div>
                            <a class="uk-inline" href="../media/<?php echo $rows->photo ?>"data-caption="FSocial Explore">
                            <img class="uk-height-small" src="../media/<?php echo $rows->photo ?>" alt="" width="100%" height="100%">
                            </a>
                        </div>
                    <?php
                }
           }
           else
           {
               echo "<p>Không có khám phá nào !</p>";
           }
       }
       public function search($query){
           $stsm = $this->connect->prepare("SELECT * FROM account WHERE username LIKE '%$query%' LIMIT 5");
           $stsm->execute();
           ?>
           <button type="submit" onclick='closeSearch()' class='close-btn'><i href="" uk-icon="close"></i></button>
           <?php
           if($stsm->rowCount() >= 1){
            foreach ($stsm->fetchAll(PDO::FETCH_OBJ) as $rows) {
                ?>
                    <a href="../account?u=<?php echo $rows->username ?>"><li class='result-object'>
                      <img class='small-avatar circle' src="<?php echo $rows->avatar; ?>">
                      <?php echo $rows->username;?><?php echo $rows->verify == 1 ? "<span class='verify'> <i class='fas fa-check-circle' uk-tooltip='Tài khoản đã xác minh'></i></span>" : ''; ?>
                    </li></a>
                <?php
             }
           }
           else{
               echo "<img src='../media/no_result.jpg'>";
           }
       }
       public function hashtag($query){
        $stsm = $this->connect->prepare("SELECT * FROM hashtag WHERE hashtag LIKE '%$query%' LIMIT 5");
        $stsm->execute();
        ?>
        <button type="submit" onclick='closeSearch()' class='close-btn'><i href="" uk-icon="close"></i></button>
        <?php
        if($stsm->rowCount() >= 1){
         foreach ($stsm->fetchAll(PDO::FETCH_OBJ) as $rows) {
             ?>
                 <a href='../search?hashtag=<?php echo $rows->hashtag ?>'><li class='result-object'>
                   <?php echo "#$rows->hashtag";?>
                 </li></a>
             <?php
          }
        }
        else{
            echo "<img src='../media/no_result.jpg'>";
        }
       }
       public function searchHashtag($hashtag){
        global $id;
        global $username;
        $newsfeed = $this->connect->prepare("SELECT * FROM post WHERE content LIKE '%#$hashtag%' ORDER BY id DESC LIMIT 50");
        $newsfeed->execute();
        if($newsfeed->rowCount() > 0){
            $checkHashtag = $this->connect->prepare("SELECT * FROM hashtag WHERE hashtag='{$hashtag}'");
            $checkHashtag->execute();
            if($checkHashtag->rowCount() == 1){
                $updateHashtag = $this->connect->prepare("UPDATE hashtag SET views=views+1");
                $updateHashtag->execute();
            }
            else{
                $record = $this->connect->prepare("INSERT INTO hashtag(hashtag,views) VALUES('{$hashtag}',1)");
                $record->execute();
            }
            foreach($newsfeed->fetchAll(PDO::FETCH_OBJ) as $news){
                $check = $this->connect->prepare("SELECT * FROM follow WHERE id_follow=$id AND id_followed=$news->user_id");
                $check->execute();
                 $account = $this->connect->prepare("SELECT * FROM account WHERE id=$id");
                 $account->execute();
                 foreach($account->fetchAll(PDO::FETCH_OBJ) as $show){
                     $show->avatar;
                 }
                 $_SESSION['avatar'] = $show->avatar;
                 $author = $this->connect->prepare("SELECT * FROM account WHERE id=$news->user_id");
                 $author->execute();
                 ?>
                      <div class="uk-card uk-card-default lazy uk-card-body uk-main uk-news">
                        <div class="up-status">
                          <div class="news author-infor">
                             <div class="action">
                                <i href="" class="action-icon" uk-icon="more"></i>
                                <ul class="action-sub uk-iconnav uk-iconnav-vertical">
                                   <li><button type='submit' value="<?php echo $news->id; ?>"><i uk-icon="icon: file-edit"></i></button></li>
                                   <li><button type="submit" value="<?php echo $news->id; ?>" onclick="deletePost(this)"><i uk-icon="icon: trash"></i></button></li>
                                </ul>
                             </div>
                             <?php
                               foreach($author->fetchAll(PDO::FETCH_OBJ) as $authorInfor){
                                   ?>
                                      <img class="circle small-avatar" src="<?php echo $authorInfor->avatar ?>">
                                      <p class="author-name"><?php echo $authorInfor->username ?><?php echo $authorInfor->verify == 1 ? "<span class='verify'> <i class='fas fa-check-circle' uk-tooltip='Tài khoản đã xác minh'></i></span>" : ''; ?></p>
                                   <?php
                               }
                             ?>
                             <a class='redirect-posts' href="../sess?id=<?php echo $news->id ?>"><small class="author-name"> <?php echo getTimeAgo($news->time_post);?> <i class="fas fa-globe-americas"></i></small></a>
                          </div>
                       <div class="news-container">
                       <p class="news-title">
                          <?php 
                             $ex = explode("\n",$news->content); 
                             foreach ($ex as $value) {
                                echo "<p class='news-content' style='margin:0px 0px 10px 5px !important;'>". hashtag($value) ."</p>";
                             }
                             $checkPhoto = $this->connect->prepare("SELECT * FROM photo WHERE user_id=$authorInfor->id AND time_post='$news->time_post' LIMIT 1");
                             $checkPhoto->execute();
                             if($checkPhoto->rowCount() == 1){
                               foreach ($checkPhoto->fetchAll(PDO::FETCH_OBJ) as $photo) {
                                   ?>
                                     <div class="uk-text-center">
                                        <div class="uk-inline-clip uk-transition-toggle" tabindex="0" style="width:100%;height:100%">
                                         <div uk-lightbox>
                                           <a style='width:100%;padding:0;border:none;' class='uk-button uk-button-default' href='<?php echo "../media/$photo->photo"; ?>' data-caption='Photo of <?php echo $authorInfor->username ?>'>
                                              <img class='photo-uploaded uk-transition-scale-up uk-transition-opaque' src='<?php echo "../media/$photo->photo"; ?>'>
                                            </a>
                                         </div>
                                        </div>
                                      </div>
                                   <?php
                                }
                             }
                             $comment = $this->connect->prepare("SELECT * FROM comment WHERE post_id=$news->id");
                             $comment->execute();
                             $limitComment = $this->connect->prepare("SELECT * FROM(SELECT * FROM comment WHERE post_id=$news->id ORDER BY id DESC LIMIT 5) comment ORDER BY id ASC");
                             $limitComment->execute();
                             $checkReact = $this->connect->prepare("SELECT * FROM react WHERE post_id=$news->id AND user_id=$id");
                             $checkReact->execute();
                           ?>
                      </p>
                      <div class="button-react">
                        <span class="soical btn-react"><button type="submit" name="love" value="<?php echo $news->id; ?>" onclick="reactHash(this)" class="love-btn animated <?php echo $checkReact->rowCount() == 1 ? 'reacted' : '';  ?>"><i class="fas fa-heart"></i></button><span class="countLoves"><?php echo number_format($news->loves,0,',','.'); ?> Thích</span></span>
                        <span class="social btn-cmt"><button value="<?php echo $news->id; ?>" onclick="showComment(this)" type="submit" name="comment" class="comment-btn animated"><i class="fas fa-comment-alt"></i></button> <span class="countComments"><?php echo $comment->rowCount(); ?> Bình luận</span></span>
                      </div>
                      <div class="show-comment <?php echo "cmt-$news->id"; ?>" style="margin-top:8px;">
                         <?php 
                            if($comment->rowCount() == 0){
                                echo "<p class='null-comment' style='margin:6px !important;'>Chưa có bình luận nào :(</p>";
                            }
                            else{
                                 foreach ($limitComment->fetchAll(PDO::FETCH_OBJ) as $key) {
                                    $authorComment = $this->connect->prepare("SELECT * FROM account WHERE id=$key->user_id");
                                    $authorComment->execute();
                                    foreach($authorComment->fetchAll(PDO::FETCH_OBJ) as $authorCommentInfor){
                                        $key->content = icon($key->content);
                                        ?>
                                           <li class="object-comment"><img class="small-avatar circle float" src="<?php echo $authorCommentInfor->avatar; ?>"><p class="comment-name"><?php echo $authorCommentInfor->username; ?><?php echo $authorCommentInfor->verify == 1 ? "<span class='verify'> <i class='fas fa-check-circle' uk-tooltip='Tài khoản đã xác minh'></i></span>" : ''; ?><small class="time-details"> ( <?php echo  getTimeAgo($key->time_post); ?> )</small></p>
                                             <div class="comments"><?php echo $key->content; ?></div>
                                           </li>
                                        <?php
                                    }
                                  }
                                if($comment->rowCount() > 5){
                                    $count = $comment->rowCount() - $limitComment->rowCount();
                                    echo "<button value='$news->id' class='more-comment' onclick='moreComment(this)'>Xem các bình luận trước ( $count )</button>";
                                }
                            }
                         ?>
                      </div>
                      <div class="<?php echo "insertcmt-$news->id"; ?> comment-area">
                         <img class="small-avatar circle float" src="<?php echo $show->avatar; ?>">
                         <input type="text" class="<?php echo $news->id; ?> comment-value" placeholder=" Viết bình luận..." name="comment-value">
                         <button type="submit" value="<?php echo $news->id; ?>" name="insertComment" class="insert-comment" onclick="upCommentHash(this)"><i class="fas fa-angle-right"></i></button>
                      </div>
                    </div>
                 </div>
               </div>
                   <?php
            }
         }
         else{
            ?>
                <div class="uk-card uk-card-default lazy uk-card-body uk-main uk-news">
                  <img src="../media/404.png" width="100%">
                </div>
            <?php
            $deleteHashtags = $this->connect->prepare("DELETE FROM hashtag WHERE hashtag='$hashtag'");
            $deleteHashtags->execute();
          }
       }
       public function trending(){
           $stsm = $this->connect->prepare("SELECT * FROM hashtag ORDER BY views DESC LIMIT 7");
           $stsm->execute();
           foreach ($stsm->fetchAll(PDO::FETCH_OBJ) as $rows) {
               echo "<a href='../search?hashtag=$rows->hashtag'><p class='trend-object'>#$rows->hashtag</p></a>";
           }
       }
       public function user($username){
           global $id;
           $stsm = $this->connect->prepare("SELECT * FROM account WHERE username='{$username}'");
           $stsm->execute();
           if($stsm->rowCount() == 0)
           {
                ?>
                    <div class="uk-card uk-card-default lazy uk-card-body uk-main uk-news">
                    <img src="../media/404.png" width="100%">
                    </div>
                <?php
           }
           foreach ($stsm->fetchAll(PDO::FETCH_OBJ) as $rows) {
                $photo = $this->connect->prepare("SELECT * FROM photo WHERE user_id=$rows->id ORDER BY id DESC");
                $photo->execute();
                $followLimit = $this->connect->prepare("SELECT * FROM follow WHERE id_follow=$rows->id LIMIT 10");
                $followLimit->execute();
                $followedLimit = $this->connect->prepare("SELECT * FROM follow WHERE id_followed=$rows->id AND id_follow!=$rows->id LIMIT 10");
                $followedLimit->execute();
                $follow = $this->connect->prepare("SELECT * FROM follow WHERE id_follow=$rows->id AND id_followed!=$rows->id");
                $follow->execute();
                $followed = $this->connect->prepare("SELECT * FROM follow WHERE id_followed=$rows->id AND id_follow!=$rows->id");
                $followed->execute();
              ?>
                <div class="uk-card uk-card-default lazy uk-card-body uk-main uk-news uk-no-padding">
                  <div class="account-infor">
                     <div class="account-wallpaper"><img class="content-wallpaper" src="https://theme.crumina.net/html-olympus/img/top-header4.png"></div>
                     <center><img class="account-avatar" src="<?php echo $rows->avatar ?>"><p class="account-username">@<?php echo $rows->username; ?> <?php echo $rows->verify == 1 ? "<span class='verify'> <i class='fas fa-check-circle' uk-tooltip='Tài khoản đã xác minh'></i></span>" : ''; ?></p></center>
                  </div>
                  <ul class="uk-subnav uk-subnav-pill uk-flex uk-flex-center" uk-switcher>
                    <li><a href="#" class="radius-20">Giới thiệu</a></li>
                    <li><a href="#" class="radius-20">Ảnh tải lên <?php echo $photo->rowCount() ?></a></li>
                    <li><a href="#" class="radius-20"><?php echo $followed->rowCount(); ?> người theo dõi</a></li>
                    <li><a href="#" class="radius-20">Đang theo dõi <?php echo $follow->rowCount(); ?> người</a></li>
                 </ul>
                 <ul class="uk-switcher uk-margin uk-flex uk-flex-center">
                    <li><p class='intro'><?php echo $rows->bio ?></p></li>
                    <li>
                        <div class="uk-position-relative uk-visible-toggle uk-light" uk-slider>
                          <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m">
                            <?php
                               if($photo->rowCount() == 0){
                                   echo 'Không có ảnh nào được hiển thị';
                               }
                               else{

                                foreach ($photo->fetchAll(PDO::FETCH_OBJ) as $photos) {
                                   ?>
                                     <li>
                                       <div uk-lightbox>
                                          <a style='width:100%;padding:0;border:none;' class='uk-button uk-button-default' href='<?php echo "../media/$photos->photo"; ?>'>
                                             <img class='uk-height-small' src='<?php echo "../media/$photos->photo"; ?>'>
                                           </a>
                                        </div>
                                      </li>
                                   <?php
                                 }
                               }
                            ?>
                          </ul>
                        <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                        <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>
                        </div>
                    </li>
                    <li>
                        <?php
                           foreach($followedLimit->fetchAll(PDO::FETCH_OBJ) as $followeds){
                               $getInforFollower = $this->connect->prepare("SELECT * FROM account WHERE id=$followeds->id_follow");
                               $getInforFollower->execute();
                               foreach ($getInforFollower->fetchAll(PDO::FETCH_OBJ) as $infor) {
                                   ?>
                                      <img uk-tooltip="<?php echo $infor->username ?>" class="small-avatar circle uk-height-small" src="<?php echo $infor->avatar ?>">
                                   <?php
                               }
                           }
                        ?>
                    </li>
                    <li>
                    <?php
                        foreach($followLimit->fetchAll(PDO::FETCH_OBJ) as $follows){
                               $getInforFollower = $this->connect->prepare("SELECT * FROM account WHERE id=$follows->id_followed AND id!=$rows->id");
                               $getInforFollower->execute();
                               foreach ($getInforFollower->fetchAll(PDO::FETCH_OBJ) as $infor) {
                                   ?>
                                      <img uk-tooltip="<?php echo $infor->username ?>" class="small-avatar circle uk-height-small" src="<?php echo $infor->avatar ?>">
                                   <?php
                               }
                           }
                        ?>
                    </li>
                  </ul></center>
                </div>
              <?php
              $newsfeed = $this->connect->prepare("SELECT * FROM post WHERE user_id=$rows->id ORDER BY id DESC");
              $newsfeed->execute();
              foreach($newsfeed->fetchAll(PDO::FETCH_OBJ) as $news){
                   ?>
                        <div class="uk-card uk-card-default lazy uk-card-body uk-main uk-news">
                          <div class="up-status">
                            <div class="news author-infor">
                               <div class="action">
                                  <i href="" class="action-icon" uk-icon="more"></i>
                                  <ul class="action-sub uk-iconnav uk-iconnav-vertical">
                                     <li><button type='submit' value="<?php echo $news->id; ?>"><i uk-icon="icon: file-edit"></i></button></li>
                                     <li><button type="submit" value="<?php echo $news->id; ?>" onclick="deletePost(this)"><i uk-icon="icon: trash"></i></button></li>
                                  </ul>
                               </div>
                                <img class="circle small-avatar" src="<?php echo $rows->avatar ?>">
                                <p class="author-name"><?php echo $rows->username ?><?php echo $rows->verify == 1 ? "<span class='verify'> <i class='fas fa-check-circle' uk-tooltip='Tài khoản đã xác minh'></i></span>" : ''; ?></p>
                               <a class='redirect-posts' href="../sess?id=<?php echo $news->id ?>"><small class="author-name"> <?php echo getTimeAgo($news->time_post);?> <i class="fas fa-globe-americas"></i></small></a>
                            </div>
                         <div class="news-container">
                         <p class="news-title">
                            <?php 
                               $ex = explode("\n",$news->content); 
                               foreach ($ex as $value) {
                                  $value = icon($value);
                                  echo "<p class='news-content' style='margin:0px 0px 10px 5px !important;'>". hashtag($value) ."</p>";
                               }
                               $checkPhoto = $this->connect->prepare("SELECT * FROM photo WHERE user_id=$rows->id AND time_post='$news->time_post' LIMIT 1");
                               $checkPhoto->execute();
                               if($checkPhoto->rowCount() == 1){
                                 foreach ($checkPhoto->fetchAll(PDO::FETCH_OBJ) as $photo) {
                                     ?>
                                       <div class="uk-text-center">
                                          <div class="uk-inline-clip uk-transition-toggle" tabindex="0" style="width:100%;height:100%">
                                           <div uk-lightbox>
                                             <a style='width:100%;padding:0;border:none;' class='uk-button uk-button-default' href='<?php echo "../media/$photo->photo"; ?>' data-caption='Photo of <?php echo $authorInfor->username ?>'>
                                                <img class='photo-uploaded uk-transition-scale-up uk-transition-opaque' src='<?php echo "../media/$photo->photo"; ?>'>
                                              </a>
                                           </div>
                                          </div>
                                        </div>
                                     <?php
                                  }
                               }
                               $comment = $this->connect->prepare("SELECT * FROM comment WHERE post_id=$news->id");
                               $comment->execute();
                               $limitComment = $this->connect->prepare("SELECT * FROM(SELECT * FROM comment WHERE post_id=$news->id ORDER BY id DESC LIMIT 5) comment ORDER BY id ASC");
                               $limitComment->execute();
                               $checkReact = $this->connect->prepare("SELECT * FROM react WHERE post_id=$news->id AND user_id=$id");
                               $checkReact->execute();
                             ?>
                        </p>
                        <div class="button-react">
                          <span class="soical btn-react"><button type="submit" name="love" value="<?php echo $news->id; ?>" onclick="reactAccount(this)" class="love-btn animated <?php echo $checkReact->rowCount() == 1 ? 'reacted' : '';  ?>"><i class="fas fa-heart"></i></button><span class="countLoves"><?php echo number_format($news->loves,0,',','.') ?> Thích</span></span>
                          <span class="social btn-cmt"><button value="<?php echo $news->id; ?>" onclick="showComment(this)" type="submit" name="comment" class="comment-btn animated"><i class="fas fa-comment-alt"></i></button> <span class="countComments"><?php echo $comment->rowCount(); ?> Bình luận</span></span>
                        </div>
                        <div class="show-comment <?php echo "cmt-$news->id"; ?>" style="margin-top:8px;">
                           <?php 
                              if($comment->rowCount() == 0){
                                  echo "<p class='null-comment' style='margin:6px !important;'>Chưa có bình luận nào :(</p>";
                              }
                              else{
                                   foreach ($limitComment->fetchAll(PDO::FETCH_OBJ) as $key) {
                                      $authorComment = $this->connect->prepare("SELECT * FROM account WHERE id=$key->user_id");
                                      $authorComment->execute();
                                      foreach($authorComment->fetchAll(PDO::FETCH_OBJ) as $authorCommentInfor){
                                          $key->content = icon($key->content);
                                          ?>
                                             <li class="object-comment"><img class="small-avatar circle float" src="<?php echo $authorCommentInfor->avatar; ?>"><p class="comment-name"><?php echo $authorCommentInfor->username; ?><?php echo $authorCommentInfor->verify == 1 ? "<span class='verify'> <i class='fas fa-check-circle' uk-tooltip='Tài khoản đã xác minh'></i></span>" : ''; ?><small class="time-details"> ( <?php echo  getTimeAgo($key->time_post); ?> )</small></p>
                                               <div class="comments"><?php echo $key->content; ?></div>
                                             </li>
                                          <?php
                                      }
                                    }
                                  if($comment->rowCount() > 5){
                                      $count = $comment->rowCount() - $limitComment->rowCount();
                                      echo "<button value='$news->id' class='more-comment' onclick='moreComment(this)'>Xem các bình luận trước ( $count )</button>";
                                  }
                              }
                              $me = $this->connect->prepare("SELECT * FROM account WHERE id=$id");
                              $me->execute();
                              foreach($me->fetchAll(PDO::FETCH_OBJ) as $mine);
                           ?>
                        </div>
                        <div class="<?php echo "insertcmt-$news->id"; ?> comment-area">
                           <img class="small-avatar circle float" src="<?php echo $mine->avatar; ?>">
                           <input type="text" class="<?php echo $news->id; ?> comment-value" placeholder=" Viết bình luận..." name="comment-value">
                           <button type="submit" value="<?php echo $news->id; ?>" name="insertComment" class="insert-comment" onclick="upCommentAccount(this)"><i class="fas fa-angle-right"></i></button>
                        </div>
                      </div>
                   </div>
                 </div>
                 <?php
                }
           }
       }
       public function deletePost($postId)
       {
           global $id;
           $stsm = $this->connect->prepare("SELECT * FROM post WHERE id=$postId AND user_id=$id");
           $stsm->execute();
           if($stsm->rowCount() == 1)
           {
               foreach($stsm->fetchAll(PDO::FETCH_OBJ) as $rows)
               {
                    $delete = $this->connect->prepare("DELETE FROM post WHERE id=$postId AND user_id=$id");
                    $delete3 = $this->connect->prepare("DELETE FROM noti WHERE post_id=$postId");
                    $delete3->execute();
                    $check = $this->connect->prepare("SELECT * FROM photo WHERE user_id=$id AND time_post='$rows->time_post'");
                    $check->execute();
                    foreach($check->fetchAll(PDO::FETCH_OBJ) as $checked)
                    {
                        $delete2 = $this->connect->prepare("DELETE FROM photo WHERE photo='$checked->photo'");
                        unlink("../media/$checked->photo");
                    }
                    if($delete->execute() || $delete2->execute())
                    {
                        echo 'Xóa bài viết thành công !';
                    }
                    else
                    {
                        echo 'Đã có lỗi xảy ra, xin vui lòng thử lại !';
                    }
               }
           }
           else
           {
               echo 'Bài viết không tồn tại hoặc bạn không có quyền thực hiện hành động này !';
           }
       }
   }
?>
