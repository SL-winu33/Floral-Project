<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['send'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $msg = mysqli_real_escape_string($conn, $_POST['message']);

    $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

    if(mysqli_num_rows($select_message) > 0){
        $message[] = 'message sent already!';
    }else{
        mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
        $message[] = 'message sent successfully!';
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

   <style>
      
body {
    background: url('images/floral-bg.png')
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
    position: relative;
    min-height: 100vh;
    overflow-x: hidden;
}

/* Create blurred background */
body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('images/floral-bg.png') no-repeat center center/cover;
    filter: blur(8px); /* adjust blur strength here */
    z-index: -1; /* ensures content is above background */
}

/* Optional: add overlay for better contrast */
body::after {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.2); /* semi-transparent overlay */
    z-index: -1;
}

      .heading {
         text-align: center;
         padding: 50px 20px 20px;
         color: #fff;
         text-shadow: 1px 1px 5px rgba(0,0,0,0.6);
      }

      .heading h3 {
         font-size: 36px;
         margin-bottom: 10px;
      }

      .heading p a {
         color: #ff6fa5;
         text-decoration: none;
         font-weight: 500;
      }

      .contact {
         display: flex;
         justify-content: center;
         align-items: center;
         padding: 40px 20px;
      }

      .contact form {
         background: rgba(255, 255, 255, 0.95);
         border-radius: 20px;
         padding: 40px;
         max-width: 500px;
         width: 100%;
         box-shadow: 0 15px 35px rgba(0,0,0,0.2);
         position: relative;
         overflow: hidden;
      }

      .contact form::before {
         content: '';
         position: absolute;
         top: -50%;
         left: -50%;
         width: 200%;
         height: 200%;
         background: linear-gradient(120deg, #ff6fa5, #ff9472, #6a82fb, #fc5c7d);
         animation: rotate 6s linear infinite;
         z-index: 0;
      }

      @keyframes rotate {
         0% { transform: rotate(0deg); }
         100% { transform: rotate(360deg); }
      }

      .contact form * {
         position: relative;
         z-index: 1;
      }

      .contact form h3 {
         text-align: center;
         font-size: 28px;
         margin-bottom: 25px;
         color: #333;
      }

      .form-group {
         position: relative;
         margin-bottom: 25px;
      }

      .form-group input,
      .form-group textarea {
         width: 100%;
         padding: 15px 15px 15px 15px;
         border: 1px solid #ccc;
         border-radius: 10px;
         font-size: 16px;
         background: transparent;
         outline: none;
         transition: all 0.3s ease;
      }

      .form-group label {
         position: absolute;
         left: 15px;
         top: 50%;
         transform: translateY(-50%);
         background: white;
         padding: 0 5px;
         color: #999;
         font-size: 16px;
         pointer-events: none;
         transition: all 0.3s ease;
      }

      .form-group input:focus + label,
      .form-group input:not(:placeholder-shown) + label,
      .form-group textarea:focus + label,
      .form-group textarea:not(:placeholder-shown) + label {
         top: -10px;
         font-size: 14px;
         color: #ff6fa5;
      }

      .form-group input:focus,
      .form-group textarea:focus {
         border-color: #ff6fa5;
         box-shadow: 0 0 10px rgba(255,111,165,0.3);
      }

      textarea {
         resize: none;
         height: 120px;
      }

      .btn {
         width: 100%;
         padding: 15px;
         border: none;
         border-radius: 10px;
         background: linear-gradient(135deg, #ff6fa5, #ff9472);
         color: #fff;
         font-size: 18px;
         font-weight: 600;
         cursor: pointer;
         transition: all 0.3s ease;
      }

      .btn:hover {
         background: linear-gradient(135deg, #ff9472, #ff6fa5);
         transform: translateY(-2px);
         box-shadow: 0 6px 15px rgba(0,0,0,0.2);
      }
   </style>
</head>
<body>

<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Contact Us</h3>
    <p> <a href="home.php">Home</a> / Contact </p>
</section>

<section class="contact">

    <form action="" method="POST">
        <h3>Send Us a Message!</h3>
        <div class="form-group">
            <input type="text" name="name" placeholder=" " required>
            <label>Enter Your Name</label>
        </div>
        <div class="form-group">
            <input type="email" name="email" placeholder=" " required>
            <label>Enter Your Email</label>
        </div>
        <div class="form-group">
            <input type="tel" name="number" placeholder=" " required pattern="[0-9+ ]+">
            <label>Enter Your Phone Number</label>
        </div>
        <div class="form-group">
            <textarea name="message" placeholder=" " required></textarea>
            <label>Enter Your Message</label>
        </div>
        <input type="submit" value="Send Message" name="send" class="btn">
    </form>

</section>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
