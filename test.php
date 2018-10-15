<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
    <li>
        <form method='POST'>
           <input type='number' name='sl' value='1'>
           <button type='submit' value="Giày Vans" name='add'>Thêm</button>
        </form>
        <form method='POST'>
            <input type='number' name='sl' value='1'>
           <button type='submit' value="Áo Hoodie" name='add'>Thêm</button>
        </form>
        <form method='POST'>
            <input type='number' name='sl' value='1'>
           <button type='submit' value="Quần Bò" name='add'>Thêm</button>
        </form>
    </li>
    <?php
       error_reporting(0);
       session_start();
       if(isset($_POST['add']))
       {
           if(isset($_SESSION['product']))
           {
               $checkItems = array_column($_SESSION['product'],'item_id');
               if(!in_array($_POST['add'],$checkItems))
               {
                   $count = count($_SESSION['product']);
                   $item = array(
                       'order' => $count,
                       'item_id' => $_POST['add'],
                       'amount' => $_POST['sl']
                   );
                   $_SESSION['product'][$count] = $item;
               }
               else
               {
                   foreach($_SESSION['product'] as $rows)
                   {
                       if($rows['item_id'] ==  $_POST['add'])
                       {
                           $item = array(
                               'order' => $rows['order'],
                               'item_id' => $_POST['add'],
                               'amount' => $_POST['sl'] + $rows['amount']
                           );
                           $count = $rows['order'];
                           $_SESSION['product'][$count] = $item;
                       }
                   }
               }
           }
           else
           {
               $item = array(
                   'order' => 0,
                   'item_id' => $_POST['add'],
                   'amount' => $_POST['sl']
               );
               $_SESSION['product'][0] = $item;
           }
       }
       ?>
       <table style="width:100%">
        <caption>Cart</caption>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Xóa</th>
            </tr>
            <?php
               $i = 1;
               foreach($_SESSION['product'] as $data)
               {
                   ?>
                      <tr>
                        <th><?php echo $i++ ?></th>
                        <th><?php echo $data['item_id'] ?></th>
                        <th><?php echo $data['amount'] ?></th>
                        <th><a href="?action=delete&id=<?php echo $data['item_id'] ?>"><button>Xóa</button></th>
                     </tr>
                   <?php
               }
            ?>
      </table>
       <?php
       /* session_destroy(); */
       if(isset($_GET['action']) && $_GET['action'] == 'delete')
       {
           $i = 0;
           foreach($_SESSION['product'] as $data => $values)
           {
               if($values['item_id'] == $_GET['id'])
               {
                   unset($_SESSION['product'][$data]);
                   $_SESSION['product'] = array_values($_SESSION['product']);
                   header('Location: /MXH/test.php');
               }
           }
       }
    ?>
</body>
<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 5px;
    text-align: left;
}
</style>
</html>