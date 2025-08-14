<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
   exit;
}

if(isset($_POST['add_product'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);
   $details = mysqli_real_escape_string($conn, $_POST['details']);
   $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
   $image = time().'_'.$_FILES['image']['name'];  // Unique image name fix
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   if (!is_dir('uploaded_img')) {
      mkdir('uploaded_img', 0777, true);
   }

   // Removed duplicate name check block from here

   // Insert product without checking duplicate name
   $insert_product = mysqli_query($conn, "INSERT INTO `products`(name, details, price, image, category_id) VALUES('$name', '$details', '$price', '$image', '$category_id')") or die('query failed');

   if($insert_product){
      if($image_size > 2000000){
         $message[] = 'Image size is too large!';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);
         $message[] = 'Product added successfully!';
      }
   }

}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
   mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid = '$delete_id'") or die('query failed');
   mysqli_query($conn, "DELETE FROM `cart` WHERE pid = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
   exit;

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Admin Products</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
  
</head>
<body>

<?php @include 'admin_header.php'; ?>

<section class="add-products">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>add new product</h3>
      <input type="text" class="box" required placeholder="enter product name" name="name">
      <input type="number" min="0" class="box" required placeholder="enter product price" name="price">
      <textarea name="details" class="box" required placeholder="enter product details" cols="30" rows="10"></textarea>
      
      <select name="category_id" class="box" required>
         <option value="">Select category</option>
         <?php
            $default_categories = ['Birthday Wishes', 'Anniversary', 'Wedding Bouquets', 'Event Planning', 'Love & Romance', 'Congratulations', 'Flower Bouquets', 'Latest Products'];
            foreach($default_categories as $cat_name){
               $cat_check = mysqli_query($conn, "SELECT id FROM `categories` WHERE name = '$cat_name'");
               if(mysqli_num_rows($cat_check) == 0){
                  mysqli_query($conn, "INSERT INTO `categories` (name) VALUES ('$cat_name')");
               }
            }

            $select_categories = mysqli_query($conn, "SELECT * FROM `categories`") or die('query failed');
            if(mysqli_num_rows($select_categories) > 0){
               while($fetch_category = mysqli_fetch_assoc($select_categories)){
                  echo '<option value="'.$fetch_category['id'].'">'.$fetch_category['name'].'</option>';
               }
            }else{
               echo '<option value="">No categories available</option>';
            }
         ?>
      </select>

      <input type="file" accept="image/jpg, image/jpeg, image/png" required class="box" name="image">
      <input type="submit" value="add product" name="add_product" class="btn">
   </form>

</section>

<section class="show-products">

   <div class="box-container">

      <?php
         // Fixed JOIN column from category to category_id
         $select_products = mysqli_query($conn, "SELECT products.*, categories.name AS category_name FROM `products` LEFT JOIN `categories` ON products.category_id = categories.id") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <div class="box">
         <div class="price">RS.<?php echo $fetch_products['price']; ?>/-</div>
         <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <div class="details"><?php echo $fetch_products['details']; ?></div>
         <div class="category"><strong>Category: </strong><?php echo $fetch_products['category_name']; ?></div>
         <a href="admin_update_product.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

</section>

<script src="js/admin_script.js"></script>

</body>
</html>
