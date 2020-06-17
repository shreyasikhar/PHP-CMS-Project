<?php ob_start(); ?>
<?php  
    use PHPMailer\PHPMailer\PHPMailer; 
    // use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
?>
<?php  include "includes/db.php"; ?>
<?php  include "includes/header.php"; ?>
<!-- Navigation -->
<?php include "includes/navigation.php"; ?> 
<?php
    require './vendor/autoload.php';
    //On terminal write 
    //composer dump-autoload -o
    //to load all classes by using autoload.php

    if(!isset($_GET['forgot']))
    {
        redirect('/');
    }
    if(ifItIsMethod('post'))
    {
        if(isset($_POST['email']))
        {
            $email = $_POST['email'];
            $length = 50;
            $token = bin2hex(openssl_random_pseudo_bytes($length));

            if(email_exists($email))
            {
                if($stmt = mysqli_prepare($connection, "update users set token = ? where user_email = ?"))
                {
                    mysqli_stmt_bind_param($stmt, "ss", $token, $email);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

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
                    $mail->Subject = 'Forgot Password on https://blog-by-shreyas.000webhostapp.com/';
                    $mail->Body = '<p>Please click to reset your password
                    <a href="https://blog-by-shreyas.000webhostapp.com/reset.php?email='.$email.'&token='.$token.'">https://blog-by-shreyas.000webhostapp.com/reset.php?email='.$email.'&token='.$token.'</a>
                    </p>';

                    if($mail->send())
                    {
                        $emailSent = true;
                    }
                    else
                    {
                        echo 'Not sent';
                    }

                }
                else
                {
                    echo "QUERY ERROR: ".mysqli_error($connection);
                }
            }
        }
    }
?>

<!-- Page Content -->
<div class="container">

    <div class="form-gap"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">

                            <?php if(!isset($emailSent)): ?>

                            <h3><i class="fa fa-lock fa-4x"></i></h3>
                            <h2 class="text-center">Forgot Password?</h2>
                            <p>You can reset your password here.</p>
                            <div class="panel-body">


                                <form id="register-form" role="form" autocomplete="off" class="form" method="post">

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                            <input id="email" name="email" placeholder="email address" class="form-control"  type="email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input name="recover-submit" class="btn btn-lg btn-primary btn-block" id="linkButton" value="Reset Password" type="submit">
                                    </div>

                                    <input type="hidden" class="hide" name="token" id="token" value="">
                                </form>

                            </div><!-- Body-->
                            <?php else: 
                                echo "<h1>Please check your email";    
                            ?>
                                
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <hr>

    <?php include "includes/footer.php";?>

</div> <!-- /.container -->
