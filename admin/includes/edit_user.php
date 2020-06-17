<?php
    if(isset($_GET['edit_user']))
    {
        $user_id = escape($_GET['edit_user']);
        $users = "SELECT * FROM users where user_id = $user_id";
        $users_result_query = mysqli_query($connection, $users);

        while($row = mysqli_fetch_assoc($users_result_query))
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
    

        if(isset($_POST['edit_user']))
        {
            $user_firstname = escape($_POST['user_firstname']);
            $user_lastname = escape($_POST['user_lastname']);
            $user_role = escape($_POST['user_role']);
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
                $query .= "user_role = '{$user_role}', ";
                $query .= "username = '{$username}', ";
                $query .= "user_email = '{$user_email}', ";
                $query .= "user_password = '{$hashed_password}' ";
                $query .= "where user_id = {$user_id}";            
                $update_user = mysqli_query($connection, $query);
                confirmQuery($update_user);

                echo "<p class='bg-success'>User Updated. <a href='users.php'>View Users</a></p>";
            }
            else
            {
                $query  = "update users set ";
                $query .= "user_firstname = '{$user_firstname}', ";
                $query .= "user_lastname = '{$user_lastname}', ";
                $query .= "user_role = '{$user_role}', ";
                $query .= "username = '{$username}', ";
                $query .= "user_email = '{$user_email}' ";
                $query .= "where user_id = {$user_id}";            
                $update_user = mysqli_query($connection, $query);
                confirmQuery($update_user);
                
                echo "<p class='bg-success'>User Updated. <a href='users.php'>View Users</a></p>"; 
            }
        }    
    }
    else
    {
        header("location:index.php");
    }
?>

<form action="" method="post" enctype="multipart/form-data">
    
<div class="form-group">
        <label for="user_firstname">First Name</label>
        <input type="text" class="form-control" name="user_firstname" value = "<?php echo $user_firstname; ?>">
    </div>
    
    <div class="form-group">
        <label for="user_lastname">Last Name</label>
        <input type="text" class="form-control" name="user_lastname" value = "<?php echo $user_lastname; ?>">
    </div>

    <div class="form-group">
        <label for="user_role">User Role</label>
        <select name="user_role">
            <option value="<?php echo $user_role; ?>"><?php echo $user_role; ?></option>
            <?php
                if($user_role == "admin")
                {
                    echo "<option value='subscriber'>subscriber</option>";
                }
                if($user_role == "subscriber")
                {
                    echo "<option value='admin'>admin</option>";
                }
            ?>
        </select>
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
        <input type="password" class="form-control" name="user_password" autocomplete = "off">    
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="edit_user" value="Update User">
    </div>

</form>