<?php
include 'database/db.php';

// --- FORM SUBMISSION LOGIC ---
$msg_alert = ""; // Message show karne ke liye variable

if (isset($_POST['submit'])) {
    // Data ko clean karo (Security ke liye)
    $name = mysqli_real_escape_string($conn, $_POST['con_name']);
    $email = mysqli_real_escape_string($conn, $_POST['con_email']);
    $subject = mysqli_real_escape_string($conn, $_POST['con_content']);
    $message = mysqli_real_escape_string($conn, $_POST['con_message']);

    // Validation: Koi field khali na ho
    if (!empty($name) && !empty($email) && !empty($message)) {
        
        // Database mein insert karo
        $sql = "INSERT INTO messages (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
        
        if (mysqli_query($conn, $sql)) {
            $msg_alert = "<div class='alert alert-success'>Thank you! Your message has been sent to Admin.</div>";
        } else {
            $msg_alert = "<div class='alert alert-danger'>Error: Could not send message.</div>";
        }
    } else {
        $msg_alert = "<div class='alert alert-warning'>Please fill all required fields.</div>";
    }
}
?>

<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Contact Us - FloSun</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">

    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/vendor/font.awesome.min.css">
    <link rel="stylesheet" href="assets/css/vendor/linearicons.min.css">
    <link rel="stylesheet" href="assets/css/plugins/swiper-bundle.min.css">
    <link rel="stylesheet" href="assets/css/plugins/animate.min.css">
    <link rel="stylesheet" href="assets/css/plugins/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/css/plugins/nice-select.min.css">
    <link rel="stylesheet" href="assets/css/plugins/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/style.css">

    
</head>

<body>

    <?php include "partials/header.php"?>

    <div class="breadcrumbs-area position-relative">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="breadcrumb-content position-relative section-content">
                        <h3 class="title-3">Contact Us</h3>
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li>Contact Us</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="contact-us-area mt-no-text">
        <div class="container custom-area">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-custom">
                    <div class="contact-info-item">
                        <div class="con-info-icon"><i class="lnr lnr-smartphone"></i></div>
                        <div class="con-info-txt">
                            <h4>Contact us Anytime</h4>
                            <p>Mobile / WhatsApp : 03350391951</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-custom text-align-center">
                    <div class="contact-info-item">
                        <div class="con-info-icon"><i class="lnr lnr-envelope"></i></div>
                        <div class="con-info-txt">
                            <h4>Support Overall</h4>
                            <p>tshumaila58@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 col-custom">
                    
                    <?php echo $msg_alert; ?>

                    <form method="post" action="" class="contact-form">
                        <div class="comment-box mt-5">
                            <h5 class="text-uppercase">Get in Touch</h5>
                            <div class="row mt-3">
                                <div class="col-md-6 col-custom">
                                    <div class="input-item mb-4">
                                        <input class="border-0 rounded-0 w-100 input-area name gray-bg" type="text" name="con_name" placeholder="Name" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-custom">
                                    <div class="input-item mb-4">
                                        <input class="border-0 rounded-0 w-100 input-area email gray-bg" type="email" name="con_email" placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="col-12 col-custom">
                                    <div class="input-item mb-4">
                                        <input class="border-0 rounded-0 w-100 input-area email gray-bg" type="text" name="con_content" placeholder="Subject" required>
                                    </div>
                                </div>
                                <div class="col-12 col-custom">
                                    <div class="input-item mb-4">
                                        <textarea cols="30" rows="5" class="border-0 rounded-0 w-100 custom-textarea input-area gray-bg" name="con_message" placeholder="Message" required></textarea>
                                    </div>
                                </div>
                                <div class="col-12 col-custom mt-40 mb-4">
                                    <button type="submit" name="submit" class="btn flosun-button secondary-btn theme-color rounded-0">Send A Message</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'partials/footer.php'; ?>


    <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
    <script src="assets/js/vendor/jquery-migrate-3.3.2.min.js"></script>
    <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="assets/js/plugins/swiper-bundle.min.js"></script>
    <script src="assets/js/plugins/nice-select.min.js"></script>
    <script src="assets/js/plugins/jquery.ajaxchimp.min.js"></script>
    <script src="assets/js/plugins/jquery-ui.min.js"></script>
    <script src="assets/js/plugins/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/main.js"></script>

</body>
</html>