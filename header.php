<?php
session_start();
@include 'config.php';
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

    <div class="flex">

        <a href="home.php" class="logo">Floral Bloom</a>
        <nav class="navbar">
            <ul>
                <li><a href="home.php">home</a></li>
                <li><a href="#">pages +</a>
                    <ul>
                        <li><a href="about.php">about</a></li>
                        <li><a href="contact.php">contact</a></li>
                    </ul>
                </li>
                <li><a href="shop.php">shop</a></li>
                <li><a href="orders.php">orders</a></li>
                <li><a href="#">account +</a>
                    <ul>
                        <li><a href="login.php">login</a></li>
                        <li><a href="register.php">register</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
            <?php
                $user_id = $_SESSION['user_id'] ?? 0;
                $select_wishlist_count = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE user_id = '$user_id'") or die('query failed');
                $wishlist_num_rows = mysqli_num_rows($select_wishlist_count);
            ?>
            <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?php echo $wishlist_num_rows; ?>)</span></a>
            <?php
                $select_cart_count = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                $cart_num_rows = mysqli_num_rows($select_cart_count);
            ?>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?php echo $cart_num_rows; ?>)</span></a>
        </div>

        <div class="account-box">
        <?php
        if(isset($_SESSION['user_id'])){
            $user_id = $_SESSION['user_id'];
            $select_user = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
            if(mysqli_num_rows($select_user) > 0){
                $fetch_user = mysqli_fetch_assoc($select_user);
                $image_file = $fetch_user['image'];

                // Fix: use full path for file_exists, but show relative path in <img src>
                $server_path = __DIR__ . '/' . $image_file;

                if(!empty($image_file) && file_exists($server_path)){
                    echo '<img src="'.$image_file.'" alt="profile" style="width:80px; height:80px; border-radius:50%; object-fit:cover; margin-bottom:10px;">';
                } else {
                    echo '<img src="default-avatar.png" alt="default profile" style="width:80px; height:80px; border-radius:50%; object-fit:cover; margin-bottom:10px;">';
                }
                echo '<p>username : <span>'.$_SESSION['user_name'].'</span></p>';
                echo '<p>email : <span>'.$_SESSION['user_email'].'</span></p>';
            }
            echo '<a href="logout.php" class="delete-btn">logout</a>';
        } else {
            echo '<p><a href="login.php">Login</a> or <a href="register.php">Register</a></p>';
        }
        ?>
        </div>

    </div>

</header>
