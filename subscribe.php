<?php 
@include 'config.php';

$feedback = "";
$feedbackClass = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $feedback = "Please enter a valid name and email address.";
        $feedbackClass = "error";
    } else {
        $stmt = $conn->prepare("SELECT * FROM subscriptions WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $feedback = "You're already subscribed!";
            $feedbackClass = "info";
        } else {
            $insert = $conn->prepare("INSERT INTO subscriptions (name, email, phone) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $name, $email, $phone);
            if ($insert->execute()) {
                $discountCode = strtoupper(substr(md5($email . time()), 0, 8));
                $feedback = "🎉 Thank you for subscribing, $name! Use code <strong>$discountCode</strong> for 10% off.";
                $feedbackClass = "success";
            } else {
                $feedback = "Something went wrong. Please try again later.";
                $feedbackClass = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Subscribe Now</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100%;
            background: url('images/s21.jpg') no-repeat center center fixed;
            background-size: cover;
        }

       .subscribe-box {
    background: rgba(255, 255, 255, 0.15); /* semi-transparent white */
    backdrop-filter: blur(10px);            /* glass blur */
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 30px;
    border-radius: 20px;
    max-width: 450px;
    margin: 100px auto;
    text-align: center;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    color: #000; /* make text inside form black */
}

.subscribe-box h2,
.subscribe-box p {
    color: #000; /* black text for headings & paragraphs */
}

.subscribe-box input[type="text"],
.subscribe-box input[type="email"],
.subscribe-box input[type="tel"] {
    padding: 10px;
    width: 80%;
    margin: 8px 0;
    border: 1px solid rgba(0, 0, 0, 0.2); /* subtle dark border */
    border-radius: 25px;
    font-size: 1rem;
    outline: none;
    background: rgba(255, 255, 255, 0.8); /* almost opaque white background for inputs */
    color: #000; /* black text */
}

.subscribe-box input::placeholder {
    color: #555; /* dark grey placeholder */
}

        .subscribe-box button {
            padding: 10px 20px;
            background: #ff4081;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .subscribe-box button:hover {
            background: #e91e63;
        }

        .subscribe-box .back-button {
            display: inline-block;
            padding: 10px 20px;
            background: #2196F3;
            color: white;
            border-radius: 25px;
            text-decoration: none;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .subscribe-box .back-button:hover {
            background: #0b7dda;
        }

        .alert {
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 1rem;
        }

        .alert.success { background: #d4edda; color: #155724; }
        .alert.error   { background: #f8d7da; color: #721c24; }
        .alert.info    { background: #cce5ff; color: #004085; }
    </style>
</head>
<body>

<div class="subscribe-box">
    <h2>Join Our Newsletter</h2>
    <p>Subscribe and get 10% off your first order!</p>

    <?php if (!empty($feedback)): ?>
        <div class="alert <?= $feedbackClass; ?>">
            <?= $feedback; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="name" placeholder="Enter your name" required><br>
        <input type="email" name="email" placeholder="Enter your email" required><br>
        <input type="tel" name="phone" placeholder="Enter your phone (optional)"><br>
        <button type="submit">Subscribe</button>
    </form>

    <a href="home.php" class="back-button">Back to Home</a>
</div>

</body>
</html>
