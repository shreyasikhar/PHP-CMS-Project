<?php
    if(isset($_GET['p_id']))
    {
        $p_id = escape($_GET['p_id']);
        $posts = "SELECT * FROM posts where post_id = {$p_id}";
        $posts_result_by_id = mysqli_query($connection, $posts);

        while($row = mysqli_fetch_assoc($posts_result_by_id))
        {
            $post_id = $row['post_id'];
            $post_user = $row['post_user'];
            $post_title = $row['post_title'];
            $post_category_id = $row['post_category_id'];
            $post_status = $row['post_status'];
            $post_image = $row['post_image'];
            $post_tags = $row['post_tags'];
            $post_comment_count = $row['post_comment_count'];
            $post_date = $row['post_date'];
            $post_content = $row['post_content'];
        }
    } 
    if(isset($_POST['update_post']))
    {
        $post_user = mysqli_real_escape_string($connection, $_SESSION['username']);
        $post_title = mysqli_real_escape_string($connection, $_POST['post_title']);
        $post_category_id = mysqli_real_escape_string($connection, $_POST['post_category_id']);
        $post_status = mysqli_real_escape_string($connection, $_POST['post_status']);
        $post_image = mysqli_real_escape_string($connection, $_FILES['post_image']['name']);
        $post_image_temp = mysqli_real_escape_string($connection, $_FILES['post_image']['tmp_name']);
        $post_content = mysqli_real_escape_string($connection, $_POST['post_content']);
        $post_tags = mysqli_real_escape_string($connection, $_POST['post_tags']);

        move_uploaded_file($post_image_temp, "../images/$post_image");

        if(empty($post_image))
        {
            $query = "SELECT * FROM posts where post_id = {$p_id}";
            $select_image = mysqli_query($connection, $query);
            while($row = mysqli_fetch_assoc($select_image))
            {
                $post_image = $row['post_image'];
            }    
        }

        $query  = "update posts set ";
        $query .= "post_title = '{$post_title}', ";
        $query .= "post_category_id = '{$post_category_id}', ";
        $query .= "post_date = now(), ";
        $query .= "post_user = '{$post_user}', ";
        $query .= "post_status = '{$post_status}', ";
        $query .= "post_tags = '{$post_tags}', ";
        $query .= "post_content = '{$post_content}', ";
        $query .= "post_image = '{$post_image}' ";
        $query .= "where post_id = {$post_id}";

        $update_post = mysqli_query($connection, $query);
        confirmQuery($update_post);

        echo "<p class='bg-success'>Post Updated. <a href='../post.php?p_id={$post_id}'>View Post</a> or <a href='posts.php'>Edit More Posts</a></p>";
    }       
?>

<form action="" method="post" enctype="multipart/form-data">
    
    <div class="form-group">
        <label for="title">Post Title</label>
        <input value="<?php echo $post_title; ?>" type="text" class="form-control" name="post_title">
    </div>

    <div class="form-group">
        <label for="post_category_id">Post Category</label>
        <select name="post_category_id">

        <?php
            $categories = "SELECT * FROM categories";
            $categories_result = mysqli_query($connection, $categories);
            confirmQuery($categories_result);
            while($row = mysqli_fetch_assoc($categories_result))
            {
                $cat_title  = $row['cat_title'];
                $cat_id  = $row['cat_id'];
                if($cat_id == $post_category_id)
                {
                    echo "<option selected value='{$cat_id}'>{$cat_title}</option>"; 
                }
                else
                {
                    echo "<option value='{$cat_id}'>{$cat_title}</option>";
                }
            }
        ?>

        </select>
    </div>

    <div class="form-group">
        <label for="post_user">Username</label>
        <input type='text' value="<?php echo $_SESSION['username']; ?>" name="post_user" disabled>
        <!-- <select name="post_user">
        <?php //echo "<option value='{$post_user}'>{$post_user}</option>"; ?>
        <?php  
            // $query = "select * from users where username not in ('$post_user')";
            // $user_list = mysqli_query($connection, $query);
            // while($row = mysqli_fetch_assoc($user_list))
            // {
            //     $username = $row['username'];
            //     echo "<option value='{$username}'>{$username}</option>";
            // }
        ?>
        </select> -->
    </div>
    
    <div class="form-group">  
        <label for="post_status">Choose Status</label>
        <select name="post_status">
                <option value="<?php echo $post_status; ?>"><?php echo $post_status; ?></option>
            <?php
                if($post_status == "draft")
                    echo "<option value='published'>Publish</option>";
                else
                    echo "<option value='draft'>Draft</option>";   
            ?>
        </select>
    </div>    

    <div class="form-group">
        <label for="post_image">Post Image</label>
        <img src="../images/<?php echo $post_image; ?>" alt="" width="100">
        <input type="file" class="form-control" name="post_image">
    </div>

    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input value="<?php echo $post_tags; ?>"type="text" class="form-control" name="post_tags">
    </div>

    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea class="form-control" name="post_content" id="body" col="30" rows="10"><?php echo $post_content; ?> </textarea>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update_post" value="Update Post">
    </div>

</form>