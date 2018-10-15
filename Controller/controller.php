<?php
   session_start();
   include("../Model/model.php");
   date_default_timezone_set('Asia/Ho_Chi_Minh');
   $time = date("Y-m-d H:i:s");
   $data = new Model;
   switch ($_POST['action']) {
       case 'post':
          $content = $data->checkData($_POST['content']);
          $data->postStatus($content,$time);
       break;
       case 'displayData':
          $data->showData($_SESSION['page']);
       break;
       case 'displayDataAccount':
          $data->user('admin');
       break;
       case 'displayDataHash':
          $hash = $_POST['hashtag'];
          $data->searchHashtag("$hash");
       break;
       case 'displayDataId':
          $postId = $_POST['postId'];
          $data->showDataId($postId);
       break;
       case 'react':
          $postId = $_POST['id'];
          $data->react($postId,$time);
       break;
       case 'comment':
          $content = $data->checkData($_POST['content']);
          $postId = $_POST['id'];
          $data->comment($content,$postId,$time);
       break;
       case 'fullComment':
          $id = $_POST['id'];
          $data->fullComment($id);
       break;
       case 'follow':
          $idFollowed = $_POST['id_followed'];
          $data->follow($idFollowed,$time);
       break;
       case 'updateFollow':
          $data->followTable();
       break;
       case 'moreFollow':
          $data->moreFollow();
       break;
       case 'unfollow':
          $idFollowed = $_POST['id_followed'];
          $data->unfollow($idFollowed);
       break;
       case 'noti':
          $data->noti();
       break;
       case 'clearNoti':
          $data->clearNoti();
       break;
       case 'search':
          $query = $data->checkData($_POST['query']);
          $data->search($query);
       break;
       case 'hashtag':
          $query = $data->checkData($_POST['query']);
          $hash = explode("#",$query);
          $data->hashtag($hash[1]);
       break;
       case 'deletePost':
          $data->deletePost($_POST['id']);
       break;
       case 'morePosts':
          $page = $_SESSION['page']+=1;
          $data->showData($page*20);
       break;
   }
?>