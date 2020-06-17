<?php
    if(isset($_POST['create_post']))
    {
        $post_title = escape($_POST['post_title']);
        $post_user = escape($_SESSION['username']);
        $post_category_id = escape($_POST['post_category']);
        $user_id = loggedInUserId();
        $post_status = escape($_POST['post_status']);

        $post_image = $_FILES['post_image']['name'];
        $post_image_temp = $_FILES['post_image']['tmp_name'];

        $post_tags = escape($_POST['post_tags']);
        $post_content = escape($_POST['post_content']);
        $post_date = date('d-m-y');
        // $post_comment_count = 4;

        move_uploaded_file($post_image_temp, "../images/$post_image");
        // move_uploaded_file($post_image_temp, "images/$post_image");


        $query = "insert into posts(post_category_id, user_id, post_title, post_user, post_date, post_image, post_content, post_tags, post_status) ";
        $query .= "values ('{$post_category_id}', '{$user_id}','{$post_title}', '{$post_user}', now(), '{$post_image}', '{$post_content}', '{$post_tags}', '{$post_status}')";
        $insert_post = mysqli_query($connection, $query);
        confirmQuery($insert_post);
        $post_id = mysqli_insert_id($connection);
        echo "<p class='bg-success'>Post Created. <a href='../post.php?p_id={$post_id}'>View Post</a> or <a href='posts.php'>View More Posts</a></p>";
    }
?>                 

<form action="" method="post" enctype="multipart/form-data">
    
    <div class="form-group">
        <label for="title">Post Title</label>
        <input type="text" class="form-control" name="post_title">
    </div>

    <div class="form-group">
        <label for="post_category">Post Category</label>
        <select name="post_category">
        <?php  
            $query = "select * from categories";
            $category_list = mysqli_query($connection, $query);
            while($row = mysqli_fetch_assoc($category_list))
            {
                echo "<option value='{$row['cat_id']}'>{$row['cat_title']}</option>";
            }
        ?>
        </select>
    </div>

    <div class="form-group">
        <label for="post_user">Username</label>
        <input type='text' value="<?php echo $_SESSION['username']; ?>" name="post_user" disabled>
        <?php  
            
            // $query = "select * from users";
            // $user_list = mysqli_query($connection, $query);
            // while($row = mysqli_fetch_assoc($user_list))
            // {
            //     echo "<option value='{$row['username']}'>{$row['username']}</option>";
            // }
        ?>
    </div>
    
    <div class="form-group">
        <label for="post_status">Choose Status</label>
        <select name='post_status'>
            <option value="draft">Select Options</option>
            <option value="published">Publish</option>
            <option value="draft">Draft</option>
        </select>    
    </div>

    <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file" class="form-control" name="post_image">
    </div>

    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input type="text" class="form-control" name="post_tags">
    </div>

    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea class="form-control" name="post_content" id="body" col="30" rows="10"></textarea>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="create_post" value="Publish Post">
    </div>

</form>