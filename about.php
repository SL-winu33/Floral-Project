<?php

@include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
}

// Initialize session reviews if not exists
if(!isset($_SESSION['reviews'])) {
    $_SESSION['reviews'] = array();
}

// Previous fixed customer reviews
$fixed_reviews = array(
    array(
        'name' => 'Sasindu Deneth',
        'review' => 'Absolutely loved the bouquet I received! 🌹 The flowers were fresh and the arrangement was stunning. Delivery was super fast 🚚. Highly recommend!',
        'rating' => 5,
        'profile' => 'pic-1.jpeg'
    ),
    array(
        'name' => 'Sanduni Rasindi',
        'review' => 'Absolutely lovely bouquet I received! 🌹 The flowers were fresh and the arrangement was stunning. Delivery was super fast 🚚. Highly recommend!',
        'rating' => 5,
        'profile' => 'pic-2.jpeg'
    ),
    array(
        'name' => 'Chamara Silva',
        'review' => 'I ordered flowers for a corporate event and they looked amazing 🌸. Everyone commented on how professional and beautiful the arrangements were. Will order again!',
        'rating' => 5,
        'profile' => 'pic-3.jpeg'
    ),
    array(
        'name' => 'Achini Kavindi',
        'review' => 'Fast delivery, excellent quality, and beautiful presentation 🎁. I’m really impressed with the consistency of their service.',
        'rating' => 5,
        'profile' => 'pic-4.jpeg'
    ),
    array(
        'name' => 'Rashan Pathum',
        'review' => 'The team created a personalized bouquet just as I imagined 💖. Customer service was amazing and the flowers lasted for over a week 🌷!',
        'rating' => 5,
        'profile' => 'pic-5.jpeg'
    ),
    array(
        'name' => 'Tharushi Netsara',
        'review' => 'I’ve ordered multiple times and they never disappoint 🌺. The attention to detail and freshness of the flowers are always perfect 💖.',
        'rating' => 5,
        'profile' => 'pic-6.jpeg'
    )
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About Us</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php @include 'header.php'; ?>

<section class="heading">
    <h3>about us</h3>
    <p> <a href="home.php">home</a> / about </p>
</section>

<section class="about">

    <div class="flex">
        <div class="image enhanced-image">
            <img src="images/about-imge-1.jpeg" alt="Why Choose Us">
        </div>
        <div class="content">
            <h3>why choose us?</h3>
            <p>🌸 Welcome to our floral boutique, where <strong>creativity meets excellence</strong>. Every bouquet is carefully hand-selected 🌹 and artfully arranged 🎨 to create unforgettable moments. With over <strong>10,000+ bouquets delivered 🚚</strong> and a client satisfaction rate of <strong>4.9/5 ⭐</strong>, we are trusted across Sri Lanka. Our <strong>eco-friendly packaging 🌿</strong> ensures that beauty doesn’t cost the planet. From birthdays 🎉 to weddings 💍, we make every occasion memorable ✨.</p>
            <a href="shop.php" class="btn">shop now</a>
        </div>
    </div>

    <div class="flex">
        <div class="content">
            <h3>what we provide?</h3>
            <p>💐 We offer a wide range of floral services designed to bring elegance and joy. From <strong>luxury wedding & event designs 💎</strong> to <strong>corporate gifting 🎁</strong> and <strong>exotic flower collections 🌺</strong> sourced from 5 countries, every creation is crafted with passion. Each year, we design flowers for <strong>200+ weddings 💒</strong> and provide regular deliveries to over <strong>500 loyal clients 🏆</strong>. Our interactive <strong>floral workshops 🌼</strong> and personalized consultations make each experience exclusive.</p>
            <a href="contact.php" class="btn">contact us</a>
        </div>
        <div class="image enhanced-image">
            <img src="images/about-imge-2.jpeg" alt="What We Provide">
        </div>
    </div>

    <div class="flex">
        <div class="image enhanced-image">
            <img src="images/about-imge-3.jpeg" alt="Who We Are">
        </div>
        <div class="content">
            <h3>who we are?</h3>
            <p>🌟 We are a passionate team of <strong>professional floral designers 👩‍🎨👨‍🎨</strong> turning flowers into <strong>memorable stories 📖</strong>. Trusted by over <strong>1,000 clients 👥</strong>, including local celebrities and corporate leaders, we’ve participated in <strong>10+ international flower expos 🌍</strong>, bringing global trends to Sri Lanka. Every creation reflects our <strong>innovation 💡</strong>, <strong>elegance ✨</strong>, and <strong>excellence 🏅</strong>.</p>
            <a href="#reviews" class="btn">clients reviews</a>
        </div>
    </div>

</section>

<section class="reviews" id="reviews">

    <h1 class="title">client's reviews</h1>

    <div class="box-container">
        <?php
        // Display fixed reviews first
        foreach($fixed_reviews as $r){
            echo '<div class="box">';
            echo '<img src="images/'.$r['profile'].'" alt="'.$r['name'].'">';
            echo '<p>'.$r['review'].'</p>';
            echo '<div class="stars">';
            for($i=0; $i<$r['rating']; $i++) echo '<i class="fas fa-star"></i>';
            for($i=$r['rating']; $i<5; $i++) echo '<i class="far fa-star"></i>';
            echo '</div>';
            echo '<h3>'.$r['name'].'</h3>';
            echo '</div>';
        }

        // Display session-based reviews dynamically
        foreach($_SESSION['reviews'] as $r){
            echo '<div class="box">';
            echo '<img src="images/'.$r['profile'].'" alt="'.$r['name'].'">';
            echo '<p>'.$r['review'].'</p>';
            echo '<div class="stars">';
            for($i=0; $i<$r['rating']; $i++) echo '<i class="fas fa-star"></i>';
            for($i=$r['rating']; $i<5; $i++) echo '<i class="far fa-star"></i>';
            echo '</div>';
            echo '<h3>'.$r['name'].'</h3>';
            echo '</div>';
        }
        ?>
    </div>

    <div class="review-btn-container">
        <a href="review.php" class="btn enhanced-review-btn">Write a Review</a>
    </div>

</section>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

<style>

/* Enhanced Image Styles */
.enhanced-image img {
    width: 100%;
    max-width: 500px;   /* limits the width */
    height: auto;
    max-height: 400px;  /* limits the height */
    border-radius: 12px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    transition: transform 0.3s, box-shadow 0.3s;
    object-fit: cover;  /* ensures image fits nicely */
    display: block;
    margin: 0 auto;     /* centers image inside its container */
}
.enhanced-image img:hover {
    transform: scale(1.05);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

/* Make review images perfectly round */
.reviews .box img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ff6b81;
    margin-bottom: 10px;
}

/* "Write a Review" button enhanced only */
.review-btn-container {
    text-align: center;
    margin-top: 25px;
}
.enhanced-review-btn {
    display: inline-block;
    padding: 12px 30px;
    font-size: 1rem;
    border-radius: 50px;
    background: linear-gradient(45deg,#ff6b81,#ff4757);
    color: #fff;
    font-weight: 600;
    transition: all 0.3s ease;
}
.enhanced-review-btn:hover {
    background: linear-gradient(45deg,#ff4757,#ff6b81);
    transform: scale(1.05);
}

/* Review Stars */
.reviews .stars i {
    color: #ffbc00;
}
</style>

</body>
</html>
