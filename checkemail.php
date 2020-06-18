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
        header('location:/cms/');
    }
    require './vendor/autoload.php';
?>
<?php
    if(isset($_GET['email']))
    {
        $email = $_GET['email'];
    }
    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $otp = trim($_POST['otp']);

        $error = [
            'otp' => ''
        ];

        if(($otp) == '')
        {
            $error['otp'] = 'OTP cannot be empty';
        }
        $result = query("select otp from users where user_email='$email'");
        $row = mysqli_fetch_array($result);
        $db_otp = $row['otp'];
        if($db_otp != $otp)
        {
            $error['otp'] = 'Wrong OTP entered';
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
            // login_user($_POST['username'], $_POST['password']);
            query("update users set flag = 1 where user_email='$email'");
            header('location:login');
        }

    }
?>


    <!-- Navigation -->
    
    <?php  include "includes/navigation.php"; ?>
    
 
    <!-- Page Content -->
    <div class="container">

<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2">
                <div class="form-wrap">
                <h2>Email Verification</h2>
                    <form role="form" action="" method="post" id="login-form" autocomplete="off">

                        <div class="form-group">
                            <label for="otp" class="">Enter OTP</label>
                            <input type="text" name="otp" id="otp" class="form-control" autocomplete = "on">
                            <p><?php echo isset($error['otp']) ? $error['otp'] : '' ?></p>
                        </div>
                
                        <input type="submit" name="submit_otp" id="btn-login" class="btn btn-primary btn-lg btn-block" value="Submit OTP">
                    </form>
                    <br/>
                 
                </div>  


            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>


<hr>

<?php include "includes/footer.php";?>
