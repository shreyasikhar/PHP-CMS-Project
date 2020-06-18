<?php ob_start(); ?>
<?php  include "includes/header.php"; ?>
<?php  include "includes/db.php"; ?>
<?php
if(ifItIsMethod('post'))
{
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$error = [
        'username' => '',
        'credentials' => '',
        'password' => ''
	];
	if(($username) == '')
    {
        $error['username'] = 'Username cannot be empty';
	}
	if(!username_exists($username) && !empty($username) && !empty($password))
	{
		$error['credentials'] = 'Wrong credentials entered';
	}
	if(($password) == '')
    {
        $error['password'] = 'Password cannot be empty';
	}
	if(!username_password_match($username, $password) && !empty($username) && !empty($password))
	{
		$error['credentials'] = 'Wrong credentials entered';
	}
	$result = query("select flag from users where username='$username'");
	$row = mysqli_fetch_array($result);
	if(mysqli_num_rows($result) > 0)
	{
		$flag = $row['flag'];
		if(!$flag)
		{
			$error['credentials'] = 'Email not verified, Register again with your email';
			query("delete from users where username='$username'");
		}
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
		if(isset($_POST['username']) && isset($_POST['password']))
		{
			login_user($_POST['username'], $_POST['password']);
		}
		else
		{
			redirect('login');
		}
    }
}
?>
<?php
	checkIfUserIsLoggedInAndRedirect('/admin');
	
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
       include 'includes/en.php';
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

	<div class="form-gap"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="text-center">

							<h3><i class="fa fa-user fa-4x"></i></h3>
							<h2 class="text-center"><?php echo _LOGIN; ?></h2>
							<div class="panel-body">

								<form id="login-form" role="form" autocomplete="off" class="form" method="post">

									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>

											<input name="username" type="text" class="form-control" placeholder="<?php echo _LOGIN_USERNAME; ?>">
										</div>
										<p><?php echo isset($error['username']) ? $error['username'] : '' ?></p>
									</div>

									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
											<input name="password" type="password" class="form-control" placeholder="<?php echo _LOGIN_PASSWORD; ?>">
										</div>
										<p><?php echo isset($error['password']) ? $error['password'] : '' ?></p>
									</div>

									<div class="form-group">
										<p><?php echo isset($error['credentials']) ? $error['credentials']."<br/>" : "<br/>" ?></p>
										<input name="login" class="btn btn-lg btn-primary btn-block" value="<?php echo _LOGIN; ?>" type="submit">
									</div>

									<div class="form-group">
										<a class="pull-left" href="registration"><?php echo _SIGNUPLINK; ?></a>
										<a class="pull-right" href="forgot.php?forgot=<?php echo uniqid(true); ?>"><?php echo _FORGOTLINK; ?></a>
									</div>
								</form>

							</div><!-- Body-->

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<hr>

	<script>
		function changeLanguage()
		{
			document.getElementById('language_form').submit();
		}
	</script>

	<?php include "includes/footer.php";?>

</div> <!-- /.container -->
