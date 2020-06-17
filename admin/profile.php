<?php include "includes/admin_header.php"; ?>

<?php
    if(isset($_SESSION['username']))
    {
        $username = $_SESSION['username'];
        $query = "select * from users where username = '{$username}'";
        $select_user_profile = mysqli_query($connection, $query);
        while($row = mysqli_fetch_array($select_user_profile))
        {
            $user_id = $row['user_id'];
            $username = $row['username'];
            $user_firstname = $row['user_firstname'];
            $user_lastname = $row['user_lastname'];
            $user_password = $row['user_password'];
            $user_email = $row['user_email'];
            $user_role = $row['user_role'];
            $user_image = $row['user_image'];
        }
    }

    if(isset($_POST['edit_user']))
    {
        $user_firstname = escape($_POST['user_firstname']);
        $user_lastname = escape($_POST['user_lastname']);
        $username = escape($_POST['username']);

        // $post_image = $_FILES['post_image']['name'];
        // $post_image_temp = $_FILES['post_image']['tmp_name'];

        $user_email = escape($_POST['user_email']);
        $user_password = escape($_POST['user_password']);
        // $post_date = date('d-m-y');

        // move_uploaded_file($post_image_temp, "images/$post_image");

        if(!empty($user_password))
        {
            $query = "select user_password from users where user_id = $user_id";
            $get_user = mysqli_query($connection, $query);
            confirmQuery($get_user);

            $row = mysqli_fetch_array($get_user);
            $db_user_password = $row['user_password'];

            if($db_user_password != $user_password)
            {
                $hashed_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 12));
            }
            else
            {
                $hashed_password = $user_password;
            }
            
            $query  = "update users set ";
            $query .= "user_firstname = '{$user_firstname}', ";
            $query .= "user_lastname = '{$user_lastname}', ";
            $query .= "username = '{$username}', ";
            $query .= "user_email = '{$user_email}', ";
            $query .= "user_password = '{$hashed_password}' ";
            $query .= "where username = '{$username}'";            
            $update_user = mysqli_query($connection, $query);
            confirmQuery($update_user);

        }
        else
        {
            $query  = "update users set ";
            $query .= "user_firstname = '{$user_firstname}', ";
            $query .= "user_lastname = '{$user_lastname}', ";
            $query .= "username = '{$username}', ";
            $query .= "user_email = '{$user_email}'";
            $query .= "where username = '{$username}'";            
            $update_user = mysqli_query($connection, $query);
            confirmQuery($update_user);

        }   
    }
?>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include "includes/admin_navigation.php"; ?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                        Welcome <?php echo $_SESSION['username']; ?>
                        </h1>
                        <form action="" method="post" enctype="multipart/form-data">
    
                            <div class="form-group">
                                    <label for="user_firstname">First Name</label>
                                    <input type="text" class="form-control" name="user_firstname" value = "<?php echo $user_firstname; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="user_lastname">Last Name</label>
                                    <input type="text" class="form-control" name="user_lastname" value = "<?php echo $user_lastname; ?>">
                                </div>


                                <!-- <div class="form-group">
                                    <label for="post_image">Post Image</label>
                                    <input type="file" class="form-control" name="post_image">
                                </div> -->

                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" name="username" value = "<?php echo $username; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="user_email">Email</label>
                                    <input type="email" class="form-control" name="user_email" value = "<?php echo $user_email; ?>">    
                                </div>

                                <div class="form-group">
                                    <label for="user_password">Password</label>
                                    <input type="password" class="form-control" name="user_password" autocomplete="off">    
                                </div>

                                <div class="form-group">
                                    <input class="btn btn-primary" type="submit" name="edit_user" value="Update Profile">
                                </div>

                            </form>

                <?php
                    if(isset($_GET['source']))
                    {
                        $source = escape($_GET['source']);
                    }
                    else
                    {
                        $source = "";
                    }
                    
                ?>

                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    <?php include "includes/admin_footer.php"; ?>
