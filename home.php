<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}

if (isset($_POST['add_to_wishlist'])) {

   $product_id = $_POST['product_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];

   $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if (mysqli_num_rows($check_wishlist_numbers) > 0) {
      $message[] = 'already added to wishlist';
   } elseif (mysqli_num_rows($check_cart_numbers) > 0) {
      $message[] = 'already added to cart';
   } else {
      mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
      $message[] = 'product added to wishlist';
   }
}

if (isset($_POST['add_to_cart'])) {

   $product_id = $_POST['product_id'];
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if (mysqli_num_rows($check_cart_numbers) > 0) {
      $message[] = 'already added to cart';
   } else {

      $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

      if (mysqli_num_rows($check_wishlist_numbers) > 0) {
         mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
      }

      mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <title>Home</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
   <link rel="stylesheet" href="css/style.css" />

   <style>
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
      }

      body, html {
         width: 100%;
         height: 100%;
      }

      .slider-container {
         position: relative;
         width: 100%;
         height: 450px;
         background-image: url('images/cc.jpg');
         background-size: cover;
         background-position: center;
         overflow: hidden;
      }

      .slider-slide {
         position: absolute;
         width: 100%;
         height: 100%;
         display: none;
         justify-content: center;
         align-items: center;
         transition: opacity 1s ease-in-out;
      }

      .slider-slide img {
         max-width: 100%;
         max-height: 100%;
         object-fit: contain;
         display: block;
         margin: 0 auto;
         user-select: none;
         pointer-events: none;
      }

      .slider-caption {
   position: absolute;
   top: 50%;
   left: 50px;
   transform: translateY(-50%);
   z-index: 2;
   text-align: left;
   color: black;
   text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.7);
}

.slider-caption h3 {
   font-size: 30px;
   margin: 10px 0;
}

.slider-caption p {
   font-size: 25px;
   margin: 20px 0;
}

.slider-caption .btn {
  margin-left: 150px; /* Adjust this value to move the button right */
  background-color: lightpink;
  color: black;
  padding: 12px 24px;
  text-decoration: none;
  border-radius: 30px;
  font-weight: bold;
  border: 2px solid deeppink;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
  display: inline-block;
  margin-top: 10px;
}

.slider-caption .btn:hover {
  background-color: deeppink;
  color: white;
  transform: scale(1.05);
}


      .prev, .next {
         position: absolute;
         top: 50%;
         transform: translateY(-50%);
         font-size: 30px;
         color: white;
         background: rgba(0,0,0,0.5);
         padding: 10px;
         border-radius: 50%;
         cursor: pointer;
         z-index: 2;
         user-select: none;
      }

      .prev { left: 20px; }
      .next { right: 20px; }

      section.products {
         margin-top: 0;
         padding-top: 10px;
      }

      .category-section {
         margin-top: 40px;
         padding: 20px;
         background: #f7f7f7;
      }

      .carousel-container {
         position: relative;
         width: 100%;
         overflow: hidden;
      }

      .carousel-wrapper {
         overflow: hidden;
         width: 100%;
      }

      .carousel-inner {
         display: flex;
         transition: transform 0.5s ease;
         gap: 20px;
         width: max-content;
      }

      .category-section .box {
         flex: 0 0 calc(33.333% - 20px);
         background: #fff;
         border: 1px solid #ccc;
         padding: 10px;
         text-align: center;
         border-radius: 10px;
         box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      }

      .category-section .box .image {
         width: 100%;
         height: 200px;
         object-fit: cover;
         border-radius: 10px;
      }

      .category-section .box .name {
         font-size: 18px;
         margin: 10px 0;
         font-weight: bold;
      }

      .carousel-btn {
         position: absolute;
         top: 50%;
         transform: translateY(-50%);
         background: rgba(0,0,0,0.5);
         color: #fff;
         border: none;
         padding: 10px;
         cursor: pointer;
         z-index: 1;
         border-radius: 50%;
      }

      .carousel-btn.left {
         left: 10px;
      }

      .carousel-btn.right {
         right: 10px;
      }

.sale-frame {
   width: 100%;
   height: 280px;
   background-color: lightpink;  /* light pink background */
   background-image: url('images/s14.jpg');
   background-repeat: no-repeat;
   background-position: center;  /* center the image */
   display: flex;
   align-items: center;
   padding-left: 40px;
   box-sizing: border-box;
   margin: 40px 0;
   border-radius: 10px;
   color: black;
   font-weight: bold;
   font-family: Arial, sans-serif;
   position: relative;}
         


.sale-content {
   display: flex;
   flex-direction: column;
   gap: 15px;
}


#countdown {
   display: flex;
   gap: 15px;
   font-size: 20px;
}

#countdown div {
   background: rgba(255, 255, 255, 0.7);
   padding: 10px 15px;
   border-radius: 8px;
   min-width: 70px;
   text-align: center;
}

#countdown span {
   display: block;
   font-size: 30px;
   font-weight: 900;
}

.shop-now-btn {
   background-color: darkpink;
   color: black;
   padding: 10px 25px;
   border: 50px;
   border-radius: 50px;
   font-weight: bold;
   text-decoration: none;
   width: max-content;
   text-align: center;
   cursor: pointer;
   user-select: none;
   box-shadow: 0 3px 8px rgba(255, 182, 193, 0.7);
}
.contact-images {
   display: flex;
   justify-content: center;
   align-items: center;
   flex-wrap: wrap;
   gap: 170px;
   margin-top: 20px;
}

.contact-images img {
   width: 180px;
   height: auto;
   border-radius: 10px;
   box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}
.center-image {
   display: flex;
   justify-content: center;
   margin: 20px 0;
}

.center-image img {
   width: 200px;
   height: auto;
}
.latest-center-image {
   display: flex;
   justify-content: center;
   margin: 20px 0;
}

.latest-center-image img {
   width: 200px; /* adjust as needed */
   height: auto;
}
 
   </style>
</head>
<body>

<?php @include 'header.php'; ?>

<!-- Slider Section -->
<div class="slider-container">
   <?php for ($i = 1; $i <= 3; $i++): ?>
   <div class="slider-slide fade">
      <img src="images/b<?= $i ?>.jpeg" alt="Slide <?= $i ?>" />
      <div class="slider-caption">
          <h3>🌹Fresh Flowers, Delivered with Love🌹</h3>
         
         <a href="about.php" class="btn">Discover More</a>
      </div>
   </div>
   <?php endfor; ?>

   <?php for ($i = 5; $i <= 11; $i++): ?>
   <div class="slider-slide fade">
      <img src="images/b<?= $i ?>.jpg" alt="Slide <?= $i ?>" />
      <div class="slider-caption">
         <h3>🌹Fresh Flowers, Delivered with Love🌹</h3>
         

         <a href="about.php" class="btn">    Discover More</a>
      </div>
   </div>
   <?php endfor; ?>

   <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
   <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>


<!-- Latest Products -->


<section class="products">
   <h1 class="title">latest products</h1>



<div class="latest-center-image">
   <img src="images/aa.png" alt="Center Image">
</div>


   <div class="box-container">
      <?php
      $latest_cat = mysqli_query($conn, "SELECT id FROM categories WHERE name = 'Latest Products' LIMIT 1") or die('query failed');
$latest_cat_id = mysqli_fetch_assoc($latest_cat)['id'] ?? 0;

$select_products = mysqli_query($conn, "SELECT * FROM products WHERE category_id = $latest_cat_id LIMIT 6") or die('query failed');

         
         //$select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE latest = 1 LIMIT 6") or die('query failed');
         //$latest_cat = mysqli_query($conn, "SELECT id FROM categories WHERE name = 'Latest Products' LIMIT 1") or die('query failed');
//$latest_cat_id = mysqli_fetch_assoc($latest_cat)['id'] ?? 0;

//$select_products = mysqli_query($conn, "SELECT * FROM products WHERE category_id = $latest_cat_id LIMIT 6") or die('query failed');


         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
      ?>
      <form action="" method="POST" class="box">
         <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
         <div class="price">RS.<?= $fetch_products['price']; ?>/-</div>
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="" class="image" />
         <div class="name"><?= $fetch_products['name']; ?></div>
         <input type="number" name="product_quantity" value="1" min="0" class="qty" />
         <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>" />
         <input type="hidden" name="product_name" value="<?= $fetch_products['name']; ?>" />
         <input type="hidden" name="product_price" value="<?= $fetch_products['price']; ?>" />
         <input type="hidden" name="product_image" value="<?= $fetch_products['image']; ?>" />
         <input type="submit" value="add to wishlist" name="add_to_wishlist" class="option-btn" />
         <input type="submit" value="add to cart" name="add_to_cart" class="btn" />
      </form>
      <?php
         }} else {
            echo '<p class="empty">no products added yet!</p>';
         }
      ?>
   </div>
   <div class="more-btn">
      <a href="shop.php" class="option-btn">load more</a>
   </div>
</section>

<!-- Category Slider -->
<section class="products category-section">
   <h1 class="title">Categories</h1>
  <div class="center-image">
   <img src="images/aa.png" alt="Center Image">
</div>

</div>

   <div class="carousel-container">
      <button class="carousel-btn left" onclick="scrollCategories(-1)">&#10094;</button>
      <div class="carousel-wrapper">
         <div class="carousel-inner">
            <?php
               $categories = [
                  ['name' => 'Birthday Wishes', 'image' => 'birthday.jpg'],
                  ['name' => 'Anniversary', 'image' => 'anniver.jpg'],
                  ['name' => 'Wedding Bouquets', 'image' => 'wedding.jpeg'],
                  ['name' => 'Event Planning', 'image' => 'event.jpg'],
                  ['name' => 'Love & Romance', 'image' => 'lvu.jpg'],
                  ['name' => 'Congratulations', 'image' => 'congrats.jpeg'],
                  ['name' => 'Flower Boquets', 'image' => 'b11.jpg'],
               ];

               foreach ($categories as $cat) {
            ?>
            <div class="box">
               <img src="images/<?= $cat['image']; ?>" alt="<?= $cat['name']; ?>" class="image" />
               <div class="name"><?= $cat['name']; ?></div>
               <a href="shop.php" class="btn">More</a>
            </div>
            <?php } ?>
         </div>
      </div>
      <button class="carousel-btn right" onclick="scrollCategories(1)">&#10095;</button>
   </div>
</section>
</section> <!-- end category section -->

<!-- Sale Frame -->
<section class="sale-frame">
   <div class="sale-content">
      <div id="countdown">
         <div><span id="days">00</span> Days</div>
         <div><span id="hours">00</span> Hours</div>
         <div><span id="minutes">00</span> Minutes</div>
         <div><span id="seconds">00</span> Seconds</div>
      </div>
      <a href="shop.php" class="btn shop-now-btn">Shop Now</a>
   </div>
</section>
<section class="newsletter">
  <div class="newsletter-content">
    <h2>Subscribe & Get 10% Off</h2>
    <p>Join our newsletter for seasonal offers, flower care tips, and birthday reminders.</p>
    <form class="newsletter-form" action="subscribe.php" method="POST">
      <input type="email" name="email" placeholder="Enter your email" required>
      <button type="submit">Subscribe</button>
    </form>
  </div>
</section>


<script>
  window.onload = function() {
    // Clear email field on page load
    const emailInput = document.getElementById('subscribe-email');
    if(emailInput) {
      emailInput.value = '';
    }
  }
</script>


<style>

.newsletter {
  background: url('images/s21.jpg') no-repeat center center/cover;
  padding: 40px 60px; /* balanced vertical and horizontal padding */
  color: #fff;
  position: relative;
  border-radius: 15px;
  margin: 40px auto;
  max-width: 2000px; /* make max-width wider to match header */
  width: 100%; /* responsive width */
  box-shadow: 0 0 20px rgba(0,0,0,0.3);
  display: flex;
  align-items: center;
  min-height: 320px; /* taller, more like header */
  box-sizing: border-box; /* include padding in width */
}


.newsletter::before {
  content: '';
  position: absolute;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.5); /* dark overlay */
  z-index: 0;
  border-radius: 15px;
}

.newsletter-content {
  z-index: 1;
  width: 100%;
  max-width: 800px;
  margin: 0 auto;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.newsletter h2 {
  font-size: 4rem;      /* Larger heading */
  margin-bottom: 15px;
  color: white;
}

.newsletter p {
  font-size: 2rem;     /* Larger paragraph */
  margin-bottom: 25px;
  color: white;
}

.newsletter-form {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-wrap: nowrap;     /* Ensure input and button stay on same line */
  gap: 10px;
  width: 100%;
  max-width: 600px;
}

.newsletter-form input[type="email"] {
  padding: 10px 15px;
  border: none;
  border-radius: 30px;
  width: 70%;            /* Wider input box */
  font-size: 3rem;
  outline: none;
}

.newsletter-form button {
  padding: 10px 25px;
  background-color: #ff4081;
  color: white;
  border: none;
  border-radius: 30px;
  font-size: 2.5rem;
  cursor: pointer;
  transition: background 0.3s ease;
  white-space: nowrap;  /* Prevent button text from wrapping */
}

.newsletter-form button:hover {
  background-color: #e73370;
}

</style>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Our Services</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #fff;
      margin: 0;
      padding: 0;
    }

    .services-section {
      text-align: center;
      padding: 40px 20px;
    }

    .services-section h2 {
      font-size: 36px;
      margin-bottom: 10px;
    }

    .services-section img.logo {
      width: 100px;
      margin-bottom: 30px;
    }

    .services-grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      max-width: 1000px;
      margin: 0 auto;
    }

    .service-box {
      width: 300px;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      background-color: #fff;
      text-align: center;
      position: relative;
    }

    .service-img, .service-text {
      width: 100%;
      height: 200px;
      display: block;
      object-fit: cover;
    }

    .service-text {
      background-color: #333;
      color: #fff;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
      font-size: 18px;
    }

    .service-title {
      font-size: 18px;
      padding: 10px 0;
      background: #fafafa;
      font-weight: bold;
    }

    .more-btn {
      width: 100%;
      background-color: #ff5da2;
      border: none;
      color: white;
      padding: 12px;
      cursor: pointer;
      font-size: 16px;
      border-bottom-left-radius: 12px;
      border-bottom-right-radius: 12px;
    }

    .more-btn:hover {
      background-color: #e64a8c;
    }
    .services-section {
  text-align: center;
  padding: 40px 20px;
  background-color: #ffe4ec;  /* light pink background */
  border-radius: 15px;        /* rounded corners for nice frame effect */
  max-width: 1500px;          /* optional: limit width for neatness */
  margin: 100px auto;          /* center the whole section with some top/bottom margin */
  box-shadow: 0 4px 10px rgba(255, 192, 203, 0.3); /* subtle pink shadow */
}

  </style>
</head>
<body>

<section class="services-section">
  <h2>Our Services</h2>
  <img src="images/aa.png" class="logo" alt="Logo">

  <div class="services-grid">
    <!-- First row -->
    <div class="service-box">
      <img src="images/service.jpg" class="service-img" alt="Events Delivery">
      <div class="service-text">No matter the occasion — birthdays, anniversaries, or corporate gatherings — we ensure your floral arrangements are delivered fresh and on time, directly to your event venue or doorstep. Every delivery is handled with care, making your special moments even more memorable.
</div>
      <div class="service-title">🌼 Events Delivery</div>
      <button class="more-btn" onclick="toggleService(this)">More</button>
    </div>

    <div class="service-box">
      <img src="images/service0.jpg" class="service-img" alt="Interior Florist">
      <div class="service-text">Elevate your living or work space with our indoor floral styling services. We create elegant and refreshing arrangements that blend seamlessly with your interior décor, bringing a touch of natural beauty to every room.</div>
      <div class="service-title">🏠 Interior Florist</div>
      <button class="more-btn" onclick="toggleService(this)">More</button>
    </div>

    <div class="service-box">
      <img src="images/service2.jpg" class="service-img" alt="Exterior Florist">
      <div class="service-text">Let your outdoor spaces bloom with life. From garden installations to exterior event decor, our exterior florist services are designed to enhance entrances, patios, and outdoor venues with seasonal charm and style.

</div>
      <div class="service-title">🌳 Exterior Florist</div>
      <button class="more-btn" onclick="toggleService(this)">More</button>
    </div>

    <!-- Second row -->
    <div class="service-box">
      <img src="images/service.avif" class="service-img" alt="Hospitality Florals">
      <div class="service-text">Make a lasting impression on your guests. We provide custom floral arrangements for hotels, restaurants, and resorts that reflect your brand's personality and enhance your ambiance with sophistication and freshness.

</div>
      <div class="service-title">🏨 Hospitality Florals</div>
      <button class="more-btn" onclick="toggleService(this)">More</button>
    </div>

    <div class="service-box">
      <img src="images/service1.avif" class="service-img" alt="Wedding Planner">
      <div class="service-text">Your dream wedding deserves flawless floral design and seamless planning. From bouquets to table arrangements, our team crafts breathtaking florals and offers personalized planning services to make your big day truly magical.

</div>
      <div class="service-title">💍 Wedding Planner</div>
      <button class="more-btn" onclick="toggleService(this)">More</button>
    </div>

    <div class="service-box">
      <img src="images/service3.avif" class="service-img" alt="Custom Floral">
      <div class="service-text">Every occasion is unique — and so are our flowers. Our custom floral design service lets you personalize bouquets and arrangements to match your style, theme, and sentiment.we work with you to bring your floral vision to life with creativity and precision.

</div>
      <div class="service-title">🌷 Custom Floral</div>
      <button class="more-btn" onclick="toggleService(this)">More</button>
    </div>
  </div>
</section>

<script>
  function toggleService(button) {
    const box = button.closest('.service-box');
    const img = box.querySelector('.service-img');
    const text = box.querySelector('.service-text');

    if (img.style.display !== 'none') {
      img.style.display = 'none';
      text.style.display = 'flex';
    } else {
      img.style.display = 'block';
      text.style.display = 'none';
    }
  }
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Our Guarantee</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .guarantee-section {
      background-color: #fff0f5; /* Light pink */
      padding: 60px 20px;
      text-align: center;
    }

    .guarantee-section h2 {
      font-size: 36px;
      margin-bottom: 10px;
    }

    .guarantee-section img.logo {
      width: 100px;
      margin: 10px auto 40px auto;
    }

    .guarantee-container {
      display: flex;
      justify-content: center;
      flex-wrap: nowrap;
      overflow-x: auto;
      gap: 30px;
    }

    .guarantee-box {
      background: #fff;
      border: 2px solid #ffc0cb;
      border-radius: 12px;
      width: 250px;
      padding: 20px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
      flex-shrink: 0;
    }

    .guarantee-box img {
      width: 100px;
      height: 100px;
      object-fit: contain;
    }

    .guarantee-title {
      font-size: 18px;
      font-weight: bold;
      margin: 10px 0;
    }

    .guarantee-text {
      display: none;
      font-size: 14px;
      color: #333;
      margin-top: 10px;
    }

    .more-btn {
      margin-top: 10px;
      padding: 8px 16px;
      border: none;
      background-color: #ffc0cb;
      color: white;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<section class="guarantee-section">
  <h2>Our Guarantee</h2>
  <img src="images/aa.png" alt="Logo" class="logo">

  <div class="guarantee-container">
    <div class="guarantee-box">
      <img src="images/v1.png" alt="">
      <div class="guarantee-title">🛒Online Shopping</div>
      <div class="guarantee-text">
Enjoy a seamless and secure online shopping experience. From browsing our latest floral collections to placing orders with ease, we make it simple and stress-free — anytime, anywhere.

</div>
      <button class="more-btn" onclick="toggleText(this)">More</button>
    </div>

    <div class="guarantee-box">
      <img src="images/v5.png" alt="">
      <div class="guarantee-title">🌹Quality Products</div>
      <div class="guarantee-text">
We handpick the freshest flowers and finest materials to ensure every bouquet and gift meets the highest standards. Quality is not just a promise — it's our practice.

</div>
      <button class="more-btn" onclick="toggleText(this)">More</button>
    </div>

    <div class="guarantee-box">
      <img src="images/v2.png" alt="">
      <div class="guarantee-title">⏰On-time Delivery</div>
      <div class="guarantee-text">
Punctuality matters. Our delivery system is designed to bring your flowers to their destination on time, every time — fresh, vibrant, and ready to impress.

</div>
      <button class="more-btn" onclick="toggleText(this)">More</button>
    </div>

    <div class="guarantee-box">
      <img src="images/v3.png" alt="">
      <div class="guarantee-title">🤝Customer Service</div>
      <div class="guarantee-text">
Our friendly and professional support team is always here for you. Whether you have a question, special request, or need help with an order, we're just a message away.

</div>
      <button class="more-btn" onclick="toggleText(this)">More</button>
    </div>

    <div class="guarantee-box">
      <img src="images/v1.png" alt="">
      <div class="guarantee-title">🗂️Well-Organized </div>
      <div class="guarantee-text">
From flower preparation to final packaging and delivery, every step is thoughtfully managed to ensure a flawless customer journey.

</div>
      <button class="more-btn" onclick="toggleText(this)">More</button>
    </div>

    <div class="guarantee-box">
      <img src="images/v4.png" alt="">
      <div class="guarantee-title">🌼Much More</div>
      <div class="guarantee-text">
We go the extra mile with unique floral designs, customizable options, and ongoing updates to bring you an unforgettable gifting experience — filled with color, care, and joy.

</div>
      <button class="more-btn" onclick="toggleText(this)">More</button>
    </div>
  </div>
</section>

<script>
  function toggleText(button) {
    const text = button.previousElementSibling;
    const isVisible = text.style.display === "block";
    text.style.display = isVisible ? "none" : "block";
    button.textContent = isVisible ? "More" : "Hide";
  }
</script>

</body>
</html>


<section class="home-contact">

<section class="home-contact">
   <div class="content">
      <h3>have any questions?</h3>
      <p>We're here to help! Whether it's about your order, flower availability, or special requests, feel free to reach out. We'll get back to you as soon as possible with the answers you need.

</p>

      <a href="contact.php" class="btn">contact us</a>
</div>
      <div class="contact-images">
   <img src="images/a1.jpg" alt="Image A1">
   <img src="images/a2.jpg" alt="Image A2">
   <img src="images/a3.jpg" alt="Image A3">
   <img src="images/a4.jpg" alt="Image A4">
</div>

   
</section>
<style>.container, .box-container {
  max-width: 1300px; /* or your desired width */
  margin: 0.5 auto; /* center horizontally */
  padding: 5px; /* horizontal padding */
  box-sizing: border-box;
}

.footer .box h3 {
  color: black;           /* Titles in black */
  font-weight: 600;
  font-size: 18px;
  margin-bottom: 15px;
  text-transform: uppercase;
  text-align: left;       /* Align titles left */
}

/* Add extra space after the 3rd box */
.footer .box-container .box:nth-child(3) {
  margin-right: 30px;  /* Increase this value to add more space */
}

.footer .box p,
.footer .box a {
  display: flex;
  align-items: center;
  gap: 10px;
  color: black;           /* Paragraph and link text in black */
  text-decoration: none;
  margin-bottom: 10px;
  font-size: 14px;
}

.footer .box p i,
.footer .box a i {
  min-width: 20px;
  font-size: 18px;
  color: #FF1493;         /* Icons in pink */
}

</style>

<?php @include 'footer.php'; ?>

<!-- JavaScript for slider -->
<script>
let slideIndex = 0;
showSlides();

function plusSlides(n) {
   slideIndex += n;
   showSlides();
}

function showSlides() {
   const slides = document.getElementsByClassName("slider-slide");
   if (slideIndex >= slides.length) slideIndex = 0;
   if (slideIndex < 0) slideIndex = slides.length - 1;
   for (let i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
   }
   slides[slideIndex].style.display = "flex";
}
setInterval(() => plusSlides(1), 5000);

let categoryScroll = 0;
function scrollCategories(direction) {
   const inner = document.querySelector('.carousel-inner');
   const boxWidth = inner.querySelector('.box').offsetWidth + 20;
   const visibleBoxes = 3;
   const maxScroll = (inner.children.length - visibleBoxes) * boxWidth;
   categoryScroll += direction * boxWidth;
   if (categoryScroll < 0) categoryScroll = 0;
   if (categoryScroll > maxScroll) categoryScroll = maxScroll;
   inner.style.transform = `translateX(-${categoryScroll}px)`;
}
// Countdown Timer (set your sale end date here)
const countdownDate = new Date();
countdownDate.setDate(countdownDate.getDate() + 35); // 5 days from now

function updateCountdown() {
   const now = new Date().getTime();
   const distance = countdownDate - now;

   if (distance < 0) {
      document.getElementById('countdown').innerHTML = "Sale ended!";
      clearInterval(timerInterval);
      return;
   }

   const days = Math.floor(distance / (1000 * 60 * 60 * 24));
   const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
   const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
   const seconds = Math.floor((distance % (1000 * 60)) / 1000);

   document.getElementById('days').textContent = String(days).padStart(2, '0');
   document.getElementById('hours').textContent = String(hours).padStart(2, '0');
   document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
   document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
}

const timerInterval = setInterval(updateCountdown, 1000);
updateCountdown();

</script>
<script src="js/script.js"></script>
</body>
</html> 