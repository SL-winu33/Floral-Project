<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

   <style>
      body {
         background: url('images/d7.jpg') no-repeat center center fixed;
         background-size: cover;
      }
   </style>

</head>
<body>
   
<?php @include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title">dashboard</h1>

   <div class="box-container">

      <div class="box">
         <?php
            $total_pendings = 0;
            $select_pendings = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
            while($fetch_pendings = mysqli_fetch_assoc($select_pendings)){
               $total_pendings += $fetch_pendings['total_price'];
            };
         ?>
         <h3>RS.<?php echo $total_pendings; ?></h3>
         <p>total pendings</p>
      </div>

      <div class="box">
         <?php
            $total_completes = 0;
            $select_completes = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'completed'") or die('query failed');
            while($fetch_completes = mysqli_fetch_assoc($select_completes)){
               $total_completes += $fetch_completes['total_price'];
            };
         ?>
         <h3>RS.<?php echo $total_completes; ?></h3>
         <p>completed payments</p>
      </div>

      <div class="box">
         <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
         ?>
         <h3><?php echo $number_of_orders; ?></h3>
         <p>orders placed</p>
      </div>

      <div class="box">
         <?php
            $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            $number_of_products = mysqli_num_rows($select_products);
         ?>
         <h3><?php echo $number_of_products; ?></h3>
         <p>products added</p>
      </div>

      <div class="box">
         <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
            $number_of_users = mysqli_num_rows($select_users);
         ?>
         <h3><?php echo $number_of_users; ?></h3>
         <p>normal users</p>
      </div>

      <div class="box">
         <?php
            $select_admin = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
            $number_of_admin = mysqli_num_rows($select_admin);
         ?>
         <h3><?php echo $number_of_admin; ?></h3>
         <p>admin users</p>
      </div>

      <div class="box">
         <?php
            $select_account = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
            $number_of_account = mysqli_num_rows($select_account);
         ?>
         <h3><?php echo $number_of_account; ?></h3>
         <p>total accounts</p>
      </div>

      <div class="box">
         <?php
            $select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
            $number_of_messages = mysqli_num_rows($select_messages);
         ?>
         <h3><?php echo $number_of_messages; ?></h3>
         <p>new messages</p>
      </div>
      <div class="box">
   <?php
      $select_subscribers = mysqli_query($conn, "SELECT * FROM `subscriptions`") or die('query failed');
      $number_of_subscribers = mysqli_num_rows($select_subscribers);
   ?>
   <h3><?php echo $number_of_subscribers; ?></h3>
   <p>total subscribers</p>
</div>


      <?php
         // Show product count by category name using JOIN
         $categories = [
            'Birthday', 'Anniversary', 'Wedding Bouquets', 'Event Planning', 'Love & Romance', 'Congratulations', 'Flower Bouquets'
         ];

         foreach ($categories as $category) {
            $category_escaped = mysqli_real_escape_string($conn, $category);
            $query = "
               SELECT COUNT(*) AS count 
               FROM `products` 
               JOIN `categories` ON products.category_id = categories.id 
               WHERE categories.name = '$category_escaped'
            ";
            $result = mysqli_query($conn, $query) or die('query failed');
            $row = mysqli_fetch_assoc($result);
            $count = $row['count'];
      ?>
      <div class="box">
         <h3><?php echo $count; ?></h3>
         <p><?php echo $category; ?></p>
      </div>
      <?php } ?>

   </div>

</section>

<script src="js/admin_script.js"></script>

</body>
</html>
