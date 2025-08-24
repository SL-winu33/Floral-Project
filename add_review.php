<?php
session_start();

// Only allow logged-in users to submit
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

// Path to store reviews
$file = 'reviews.json';

// Check if form is submitted
if(isset($_POST['name'], $_POST['location'], $_POST['rating'], $_POST['review'])){

    // Sanitize input
    $name = htmlspecialchars(trim($_POST['name']));
    $location = htmlspecialchars(trim($_POST['location']));
    $rating = (float)$_POST['rating'];
    $review_text = htmlspecialchars(trim($_POST['review']));

    // Validate rating
    if($rating < 1) $rating = 1;
    if($rating > 5) $rating = 5;

    // Load existing reviews
    if(file_exists($file)){
        $reviews = json_decode(file_get_contents($file), true);
    } else {
        $reviews = [];
    }

    // Add new review to the top
    $reviews[] = [
        'name' => $name,
        'location' => $location,
        'rating' => $rating,
        'review_text' => $review_text,
        'date' => date('Y-m-d H:i')
    ];

    // Save back to JSON file
    file_put_contents($file, json_encode($reviews, JSON_PRETTY_PRINT));

    // Redirect back to About Page with success message
    header('Location: about.php?review=success');
    exit;
} else {
    // Invalid submission, redirect back
    header('Location: about.php?review=error');
    exit;
}
