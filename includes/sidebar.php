<?php
    if(ifItIsMethod('post'))
    {
        if(isset($_POST['login']))
        {
            if(isset($_POST['username']) && isset($_POST['password']))
            {
                login_user($_POST['username'], $_POST['password']);
            }
            else
            {
                redirect('/');
            }
        }
        
    }
?>
<div class="col-md-4">
        <!-- Blog Search Well -->
        <div class="well">
            <h4>Blog Search</h4>
            <form action="search" method="post">
            <div class="input-group">
                <input type="text" class="form-control" name="search">
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit" name="submit">
                        <span class="glyphicon glyphicon-search"></span>
                </button>
                </span>
            </div>
            </form>
            <!-- /.input-group -->
        </div>
        <?php
        if(isset($_SESSION['username']))
        {
        ?>
            <div class="well">
                <h4>Logged in as <?php echo $_SESSION['username'];?></h4>
                <a href='/includes/logout.php'><button class="btn btn-primary" name="logout" type="submit">Logout</button></a>
            </div>
        <?php    
        }
        else
        {
        ?>
        <!-- login Form -->
            <!-- <div class="well">
                <h4>Login</h4>
                <form method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="username" placeholder="Enter Username">
                </div>
                <div class="form-group input-group">
                    <input type="password" class="form-control" name="password" placeholder="Enter Password">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" name="login" type="submit">Submit</button>
                    </span>
                </div>

                <div class="form-group">
                    <a class="pull-left" href="registration">Not a member? Register now!</a>
                    <a class="pull-right" href="forgot.php?forgot=<?php //echo uniqid(true); ?>">Forgot Password</a>
                </div>
                <br/>
                </form>
                
                    <!-- /.input-group --> 
            <!-- </div>  --> 
        
        <?php
        }
        ?>
        <!-- Blog Categories Well -->
        <div class="well">

        <?php
            $categories_sidebar = "SELECT * FROM categories";
            $categories_result = mysqli_query($connection, $categories_sidebar);
            confirmQuery($categories_result);
        ?>

            <h4>Blog Categories</h4>
            <div class="row">
                <div class="col-lg-12">
                    <?php
                        if(mysqli_num_rows($categories_result) < 1)
                        {
                            echo "<h5>No categories available</h5>";
                        }
                          
                    ?>
                    <ul class="list-unstyled">
                    <?php  
                        while($row = mysqli_fetch_assoc($categories_result))
                        {
                            $cat_title = $row['cat_title'];
                            $cat_id = $row['cat_id'];
                            echo "<li><a href='/category/{$cat_id}'>{$cat_title}</a></li>";
                        }
                    ?>
                    </ul>
                </div>
            </div>
            <!-- /.row -->
        </div>

    <?php  include "includes/widget.php"; ?>     
</div>
