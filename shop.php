<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;
if(isset($_POST['add_to_wishlist'])){

   if(!$user_id){
      // User not logged in
      echo "<script>alert('Please login first to add to wishlist');</script>";
   } else {
      $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
      $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
      $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
      $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);

      $check_wishlist = mysqli_query($conn, "SELECT * FROM wishlist WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
if(mysqli_num_rows($check_wishlist) == 0){
   mysqli_query($conn, "INSERT INTO wishlist(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
}
// Redirect back to shop.php to avoid resubmission and no alert popup
header("Location: shop.php");
exit();

   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
      .filter-frame {
         background: url('images/cate.jpeg') no-repeat center center/cover;
         width: 100%;
         height: 300px;
         display: flex;
         align-items: center;
         justify-content: center;
         position: relative;
         margin-top: 3rem;
      }
      .filter-icon {
         position: absolute;
         top: 20px;
         left: 20px;
         font-size: 24px;
         background: rgba(0, 0, 0, 0.5);
         color: white;
         padding: 10px;
         border-radius: 50%;
         cursor: pointer;
      }
      .filter-frame p {
         color: black;
         font-size: 3rem;
         text-shadow: 1px 1px 2px black;
         text-align: center;
      }
      .filter-box {
         max-width: 1200px;
         margin: 2rem auto;
         padding: 1rem;
         border: 1px solid #ccc;
         border-radius: 10px;
         background: #f9f9f9;
         display: none;
      }
      .filter-box select, .filter-box input[type="text"] {
         padding: 10px;
         margin: 0.5rem;
         border-radius: 5px;
         border: 1px solid #aaa;
      }
      .category-label {
         font-size: 1.5rem;
         margin-bottom: 1rem;
      }
      .back-btn {
         display: inline-block;
         margin: 1rem 0;
         padding: 10px 20px;
         background: #333;
         color: #fff;
         border-radius: 5px;
         text-decoration: none;
      }
      #category-frame .title {
         text-align: center !important;
         margin-left: auto !important;
         margin-right: auto !important;
      }
      #category-frame img {
         display: block;
         margin: 0 auto 2rem auto;
         max-width: 200px;
      }
   </style>
</head>
<body>

<?php @include 'header.php'; ?>

<section class="heading">
    <h3>our shop</h3>
    <p> <a href="home.php">home</a> / shop </p>
</section>

<section class="products">
   <h1 class="title">latest products</h1>
   <div style="text-align:center; margin: 2rem 0;">
      <img src="images/aa.png" alt="Decorative Divider" style="max-width:200px;">
   </div>
  
   <div class="box-container">

      <?php
     
   // Get latest products category id
   $latest_cat = mysqli_query($conn, "SELECT id FROM categories WHERE name = 'Latest Products' LIMIT 1");
   if(mysqli_num_rows($latest_cat) > 0){
      $latest_cat_row = mysqli_fetch_assoc($latest_cat);
      $latest_cat_id = $latest_cat_row['id'];
   } else {
      $latest_cat_id = 0;
   }

   $select_products = mysqli_query($conn, "SELECT * FROM products WHERE category_id = $latest_cat_id") or die('query failed');
   if(mysqli_num_rows($select_products) > 0){
      while($fetch_products = mysqli_fetch_assoc($select_products)){
?>
<form action="" method="POST" class="box">
   <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="fas fa-eye"></a>
   <div class="price">RS.<?php echo $fetch_products['price']; ?>/-</div>
   <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
   <div class="name"><?php echo $fetch_products['name']; ?></div>
   <input type="number" name="product_quantity" value="1" min="0" class="qty">
   <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
   <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
   <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
   <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
   <input type="submit" value="add to wishlist" name="add_to_wishlist" class="option-btn">
   <input type="submit" value="add to cart" name="add_to_cart" class="btn">
</form>
<?php
      }
   } else {
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

   <!-- Category Heading and Image -->
   <section id="category-frame" class="products">
      <h1 class="title">Category</h1>
      <div style="text-align:center; margin: 2rem 0;">
         <img src="images/aa.png" alt="Category Divider" style="max-width:200px;">
      </div>
   </section>

</section>

<!-- Filter Image Frame with Icon -->
<div class="filter-frame">
   <i class="fas fa-filter filter-icon" onclick="document.querySelector('.filter-box').style.display='block';"></i>
   <p>You can categorize your search here!<br> <br> <br>Just Click Filter Icon</p>
</div>

<!-- Filter Box -->
<div class="filter-box">
   <form method="GET" action="#category-frame">
      <div class="category-label">Filter by Category:</div>
      <select name="category">
         <option value="">All</option>
         <option value="Birthday">Birthday</option>
         <option value="Anniversary">Anniversary</option>
         <option value="Wedding Bouquets">Wedding Bouquets</option>
         <option value="Event Planning">Event Planning</option>
         <option value="Love & Romance">Love & Romance</option>
         <option value="Congratulations">Congratulations</option>
         <option value="Flower Bouquets">Flower Bouquets</option>
      </select>
      <input type="text" name="flower" placeholder="Type flower name (for Flower Bouquets)">
      <input type="submit" value="Apply Filter">
   </form>
</div>

<!-- Filtered Product Section -->
<?php
if (isset($_GET['category']) && $_GET['category'] !== '') {
   $category_name = mysqli_real_escape_string($conn, $_GET['category']);
   $flower_filter = '';

   $category_query = mysqli_query($conn, "SELECT id FROM categories WHERE name = '$category_name' LIMIT 1");
   if(mysqli_num_rows($category_query) > 0){
       $category_row = mysqli_fetch_assoc($category_query);
       $category_id = $category_row['id'];
   } else {
       $category_id = 0;
   }

   if ($category_name == 'Flower Bouquets' && isset($_GET['flower']) && $_GET['flower'] !== '') {
      $flower = mysqli_real_escape_string($conn, $_GET['flower']);
      $flower_filter = " AND name LIKE '%$flower%'";
   }
   echo '<section class="products">';
   echo '<h1 class="title">' . htmlspecialchars($category_name) . '</h1>';
   echo '<a href="shop.php#category-frame" class="back-btn">&larr; Back to All Products</a>';
   echo '<div class="box-container">';

   $query = "SELECT * FROM products WHERE category_id = $category_id $flower_filter";
   $filtered_products = mysqli_query($conn, $query);

   if(mysqli_num_rows($filtered_products) > 0){
      while($fetch_products = mysqli_fetch_assoc($filtered_products)){
?>
<form action="" method="POST" class="box">
   <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="fas fa-eye"></a>
   <div class="price">RS.<?php echo $fetch_products['price']; ?>/-</div>
   <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
   <div class="name"><?php echo $fetch_products['name']; ?></div>
   <input type="number" name="product_quantity" value="1" min="0" class="qty">
   <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
   <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
   <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
   <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
   <input type="submit" value="add to wishlist" name="add_to_wishlist" class="option-btn">
   <input type="submit" value="add to cart" name="add_to_cart" class="btn">
</form>
<?php
      }
   } else {
      echo '<p class="empty">No products found!</p>';
   }

   echo '</div></section>';
}
?>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
