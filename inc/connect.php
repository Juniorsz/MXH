<?php
  class Connection
  {

      protected $servername = 'localhost';
      protected $username = 'root';
      protected $password = '';
      protected $dbname = 'olymus';
      protected $connect;
      
      function __construct(){
          try{
              $this->connect = new PDO("mysql:host=$this->servername;dbname=$this->dbname",$this->username,$this->password);
              $this->connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
              $this->connect->exec('SET NAMES UTF8MB4');
          }
          catch(PDOException $e){
              echo $e->getMessage();
          }
      }

      public function Disconect(){
          $this->connect = NULL;
      }
  }
  function getTimeAgo($timestamp){
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $time_ago        = strtotime($timestamp);
    $current_time    = time();
    $time_difference = $current_time - $time_ago;
    $seconds         = $time_difference;
    $minutes = round($seconds / 60); // value 60 is seconds  
    $hours   = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec  
    $days    = round($seconds / 86400); //86400 = 24 * 60 * 60;  
    $weeks   = round($seconds / 604800); // 7*24*60*60;  
    $months  = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60  
    $years   = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60
                  
    if ($seconds <= 60){
  
      return "Vừa xong";
  
    } else if ($minutes <= 60){
  
      if ($minutes == 1){
  
        return "1 phút trước";
  
      } else {
  
        return "$minutes phút trước";
  
      }
  
    } else if ($hours <= 24){
  
      if ($hours == 1){
  
        return "1 giờ trước";
  
      } else {
  
        return "$hours giờ trước";
  
      }
  
    } else if ($days <= 7){
  
      if ($days == 1){
  
        return "Hôm qua";
  
      } else {
  
        return "$days ngày trước";
  
      }
  
    } else if ($weeks <= 4.3){
  
      if ($weeks == 1){
  
        return "1 tuần trước";
  
      } else {
  
        return "$weeks tuần trước";
  
      }
  
    } else if ($months <= 12){
  
      if ($months == 1){
  
        return "1 tháng trước";
  
      } else {
  
        return "$months tháng trước";
  
      }
  
    } else {
      
      if ($years == 1){
  
        return "1 năm trước";
  
      } else {
  
        return "$years năm trước";
  
      }
    }
  }
  function hashtag($string){  
    $expression = "/#+([a-zA-Z0-9_]+)/";  
    $string = preg_replace($expression, '<a href="../search?hashtag=$1">$0</a>', $string);  
    return $string;  
  }
  function icon($object){
    $subject = array( '&lt;3', ':)', ':D', ':(',':|','-_-',':3','8)');
    $icon = array('❤️','🙂','😂','🙁','😐','😑','😗','😎');
    return $object = str_replace($subject,$icon,$object);
  }
?>