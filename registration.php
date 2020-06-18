<?php ob_start(); ?>
<?php  include "includes/header.php"; ?>
<?php  include "includes/db.php"; ?>
<?php 
    use PHPMailer\PHPMailer\PHPMailer; 
    // use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
?>  
<?php
    if(isset($_SESSION['username']))
    {
        header('location:/');
    }
    require './vendor/autoload.php';
?>
<?php
    // Setting language variables 
    if(isset($_GET['lang']) && !empty($_GET['lang']))
    {
        $_SESSION['lang'] = $_GET['lang'];

        if(isset($_SESSION['lang']) && $_SESSION['lang'] != $_GET['lang'])
        {
            echo "<script type='text/javascript'>location.reload();</script>";
        }
    }    
    if(isset($_SESSION['lang']))
    {
        include "includes/".$_SESSION['lang'].".php";
    }
    else
    {
        include "includes/en.php";
    }

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $error = [
            'username' => '',
            'email' => '',
            'password' => ''
        ];

        if(strlen($username) < 4)
        {
            $error['username'] = 'Username needs to be longer';
        }
        if(($username) == '')
        {
            $error['username'] = 'Username cannot be empty';
        }
        if(username_exists($username))
        {
            $error['username'] = 'Username already exists, pick another one';
        }
        if(($email) == '')
        {
            $error['email'] = 'Email cannot be empty';
        }
        if(email_exists($email))
        {
            $error['email'] = 'Email already exists, <a href="index.php>Please login</a>';
        }
        if(($password) == '')
        {
            $error['password'] = 'Password cannot be empty';
        }

        foreach($error as $key => $value)
        {
            if(empty($value))
            {
                unset($error[$key]);
            }
        } //foreach
        if(empty($error))
        {
            register_user($username, $email, $password);
            $result = query("select otp from users where user_email='$email'");
            $row = mysqli_fetch_array($result);
            $otpmail = $row['otp'];
            /*
                Configure PHPMailer
            */
            $mail = new PHPMailer();
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
            //$mail->SMTPDebug = 2;                      
            $mail->isSMTP();                                           
            $mail->Host       = Config::SMTP_HOST;                    
            $mail->Username   = Config::SMTP_USER;                    
            $mail->Password   = Config::SMTP_PASSWORD; 
            $mail->Port       = Config::SMTP_PORT;                              
            $mail->SMTPSecure = 'ssl';           
            $mail->SMTPAuth   = true;  
            $mail->isHTML(true);  
            $mail->CharSet = 'UTF-8'; 
            
            $mail->setFrom('enlectic@gmail.com', 'Blog Admin');
            $mail->addAddress($email, "Blog User");
            $mail->Subject = 'Verify Email using OTP';
            $mail->Body = '<p>Your OTP is '. $otpmail .'.<br/>Please click to enter OTP and verify email
            <a href="https://blog-by-shreyas.000webhostapp.com/checkemail.php?email='.$email.'">https://blog-by-shreyas.000webhostapp.com/checkemail.php?email='.$email.'</a>
            </p>';

            if($mail->send())
            {
                $emailSent = true;
                $emailmsg= "You will receive email for verification";
            }
            else
            {
                echo 'Not sent';
            }

            // login_user($username, $password);
            // login_user($_POST['username'], $_POST['password']);
        }

    }
?>


    <!-- Navigation -->
    
    <?php  include "includes/navigation.php"; ?>
    
 
    <!-- Page Content -->
    <div class="container">
        <form class="navbar-form navbar-right" action="" method="get" id="language_form">
            <div class="form-group">
                <select name="lang" class="form-control" onchange="changeLanguage()">
                    <!-- <option value="" class="">Select Language</option> -->
                    <option value="en" <?php if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'en') echo "selected"; ?> >English</option>
                    <option value="ma" <?php if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'ma') echo "selected"; ?> >Marathi</option>
                    <option value="sp" <?php if(isset($_SESSION['lang']) && $_SESSION['lang'] == 'sp') echo "selected"; ?> >Spanish</option>
                </select>
            </div>
        </form>
    
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                <div class="form-wrap">
                <h1><?php echo _REGISTER; ?></h1>
                <h3 class="bg-success"><?php if(isset($emailmsg)) echo $emailmsg; ?></h3>
                    <form role="form" action="registration.php" method="post" id="login-form" autocomplete="off">

                        <div class="form-group">
                            <label for="username" class="sr-only">username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="<?php echo _USERNAME; ?>" autocomplete = "on" value = "<?php echo isset($username) ? $username : '' ?>">
                            <p><?php echo isset($error['username']) ? $error['username'] : '' ?></p>
                        </div>

                         <div class="form-group">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="<?php echo _EMAIL; ?>" autocomplete = "on" value="<?php echo isset($email) ? $email : '' ?>">
                            <p><?php echo isset($error['email']) ? $error['email'] : '' ?></p>
                        </div>

                         <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" name="password" id="key" class="form-control" placeholder="<?php echo _PASSWORD; ?>">
                            <p><?php echo isset($error['password']) ? $error['password'] : '' ?></p>
                        </div>
                
                        <input type="submit" name="submit" id="btn-login" class="btn btn-primary btn-lg btn-block" value="<?php echo _REGISTER; ?>">
                    </form>
                    <br/>
                    <a href="/login"><?php echo _LOGINLINK; ?></a>
                 
                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>


<hr>

<script>
    function changeLanguage()
    {
        document.getElementById('language_form').submit();
    }
</script>



<?php include "includes/footer.php";?>
