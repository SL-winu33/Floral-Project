<?php
@include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
   exit;
}

// JSON file to store reviews
$reviews_file = 'reviews.json';
$reviews = file_exists($reviews_file) ? json_decode(file_get_contents($reviews_file), true) : [];

// Handle form submission
if(isset($_POST['submit_review'])){
    $name = htmlspecialchars($_POST['name']);
    $review = htmlspecialchars($_POST['review']);
    $rating = intval($_POST['rating']);
    
    if(isset($_FILES['profile']) && $_FILES['profile']['error'] == 0){
        $profile_tmp = $_FILES['profile']['tmp_name'];
        $profile_name = time().'_'.basename($_FILES['profile']['name']);
        move_uploaded_file($profile_tmp, 'images/'.$profile_name);

        if(!empty($name) && !empty($review) && $rating > 0){
            $new_review = [
                'name' => $name,
                'review' => $review,
                'rating' => $rating,
                'profile' => $profile_name
            ];
            $reviews[] = $new_review;
            file_put_contents($reviews_file, json_encode($reviews, JSON_PRETTY_PRINT));
            // Redirect to prevent duplicate submission
            header('Location: '.$_SERVER['PHP_SELF'].'?success=1');
            exit;
        }
    } else {
        header('Location: '.$_SERVER['PHP_SELF'].'?error=profile');
        exit;
    }
}

// Show messages
$message = '';
if(isset($_GET['success'])){
    $message = "Your review has been submitted successfully! 🌸";
} elseif(isset($_GET['error']) && $_GET['error'] == 'profile'){
    $message = "Please upload a profile picture to submit your review!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Write a Review</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="css/style.css">
<style>
body { font-family: 'Poppins', sans-serif; background:#fff8f0; margin:0; padding:0;}
.review-wrapper { max-width:700px; margin:30px auto; padding:25px; border-radius:15px; background:#fff8f0; box-shadow:0 8px 25px rgba(0,0,0,0.15);}
.review-wrapper h2 { text-align:center; margin-bottom:20px; color:#ff6b81; }
.inputBox { margin-bottom:15px; }
.inputBox input, .inputBox textarea, .inputBox select { width:100%; padding:12px; border-radius:8px; border:1px solid #ccc; font-size:1rem; }
textarea { resize:none; height:100px; }
.btn { width:100%; padding:12px; border:none; border-radius:50px; background: linear-gradient(45deg,#ff6b81,#ff4757); color:#fff; font-weight:600; font-size:1rem; cursor:pointer; transition:all 0.3s ease; }
.btn:hover { transform:scale(1.05); }
.review-box { display:flex; align-items:flex-start; margin-bottom:15px; padding:15px; background:#fff3e6; border-radius:12px; }
.review-box img { width:60px; height:60px; border-radius:50%; margin-right:15px; border:2px solid #ff6b81; object-fit:cover; }
.review-box h3 { margin:0 0 5px 0; color:#ff6b81; }
.review-box .stars i { color:#ffbc00; }
.message { color:green; font-weight:bold; text-align:center; margin-bottom:15px; }
.back-btn { display:inline-block; margin-top:20px; text-align:center; background:#ff6b81; color:#fff; padding:10px 25px; border-radius:50px; text-decoration:none; transition:all 0.3s ease; }
.back-btn:hover { background:#ff4757; transform:scale(1.05); }
.heading { text-align:center; padding:50px 20px 20px; color:#ff4757; }
.heading a { color:#ff6b81; text-decoration:none; font-weight:500; }
.heading a:hover { text-decoration:underline; }
</style>
</head>
<body>

<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Write a Review</h3>
    <p> <a href="home.php">Home</a> / Write Review </p>
</section>

<section class="review-wrapper">
    <h2>Share Your Experience 💐</h2>

    <?php if(!empty($message)) { echo '<p class="message">'.$message.'</p>'; } ?>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="inputBox">
            <input type="text" name="name" placeholder="Your Name 🌸" required>
        </div>
        <div class="inputBox">
            <textarea name="review" placeholder="Your Review 📝" required></textarea>
        </div>
        <div class="inputBox">
            <select name="rating" required>
                <option value="">Select Rating ⭐</option>
                <option value="5">⭐⭐⭐⭐⭐</option>
                <option value="4">⭐⭐⭐⭐</option>
                <option value="3">⭐⭐⭐</option>
                <option value="2">⭐⭐</option>
                <option value="1">⭐</option>
            </select>
        </div>
        <div class="inputBox">
            <input type="file" name="profile" accept="image/*" required>
        </div>
        <input type="submit" name="submit_review" value="Submit Review 🌸" class="btn">
    </form>

    <a href="about.php" class="back-btn">← Back to About Page</a>
</section>

<section class="reviews-section">
    <div class="box-container" style="max-width:700px;margin:30px auto;">
    <?php
    if(!empty($reviews)){
        foreach($reviews as $r){
            if(!empty($r['profile'])){
                echo '<div class="review-box">';
                echo '<img src="images/'.$r['profile'].'" alt="'.$r['name'].'">';
                echo '<div>';
                echo '<h3>'.$r['name'].'</h3>';
                echo '<div class="stars">';
                for($i=0;$i<$r['rating'];$i++){ echo '<i class="fas fa-star"></i>'; }
                for($i=$r['rating'];$i<5;$i++){ echo '<i class="far fa-star"></i>'; }
                echo '</div>';
                echo '<p>'.$r['review'].'</p>';
                echo '</div>';
                echo '</div>';
            }
        }
    } else {
        echo '<p style="text-align:center;">No reviews yet. Be the first to share your experience! 💐</p>';
    }
    ?>
    </div>
</section>

<?php @include 'footer.php'; ?>

</body>
</html>
