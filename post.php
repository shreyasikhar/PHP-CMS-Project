<?php ob_start(); ?>
<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
    <!-- Navigation -->
<?php include "includes/navigation.php"; ?>  

<?php
    if(isset($_POST['liked']))
    {
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];

        //Fetching the right posts
        $searchPost = "select * from posts where post_id = {$post_id}";
        $postResult = mysqli_query($connection, $searchPost);
        $row = mysqli_fetch_array($postResult);
        $likes = $row['likes'];
    
        //Update posts with likes
        mysqli_query($connection, "update posts set likes = {$likes} + 1 where post_id = {$post_id}");

        //Create likes for post
        mysqli_query($connection, "insert into likes (user_id, post_id) values($user_id, $post_id)");
    }

    if(isset($_POST['unliked']))
    {
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];

        //Fetching the right posts
        $searchPost = "select * from posts where post_id = {$post_id}";
        $postResult = mysqli_query($connection, $searchPost);
        $row = mysqli_fetch_array($postResult);
        $likes = $row['likes'];
    
        //Delete likes for post
        mysqli_query($connection, "delete from likes where user_id={$user_id} and post_id={$post_id}");

        //Update posts with likes
        mysqli_query($connection, "update posts set likes = {$likes} - 1 where post_id = {$post_id}");

    }
?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">
                <?php  

                    if(isset($_GET['p_id']))
                    {
                        $p_id = $_GET['p_id'];

                        $query = "update posts set post_views_count = post_views_count + 1 where post_id = {$p_id}";
                        $view_query = mysqli_query($connection, $query);
                        if(!$view_query)
                        {
                            die("QUERY ERROR: ".mysqli_error($connection));
                        }
                        if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin')
                        {
                            $postQuery = "select * from posts where post_id = '$p_id'";
                        }
                        else
                        {
                            $postQuery = "select * from posts where post_id = '$p_id' and post_status = 'published'";
                        }
                        $postResult = mysqli_query($connection, $postQuery);
                        if(mysqli_num_rows($postResult) < 1)
                        {
                            echo "<h1 class='text-center'>No posts available</h1>";
                        }
                        else
                        {
                            while($row = mysqli_fetch_assoc($postResult))
                            {
                                $post_title = $row['post_title'];
                                $post_author = $row['post_user'];
                                $post_date = $row['post_date'];
                                $post_image = $row['post_image'];
                                $post_content = $row['post_content'];
                        ?>

                                <h1 class="page-header">
                                    Posts
                                </h1>

                                <!-- Blog Post -->
                                <h2>
                                    <a href=""><?php echo $post_title; ?></a>
                                </h2>
                                <p class="lead">
                                    by <a href="/author_posts/<?php echo $post_author;?>"><?php echo $post_author; ?></a>
                                </p>
                                <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date; ?></p>
                                <hr>
                                <img class="img-responsive" src="/images/<?php echo imagePlaceholder($post_image); ?>" alt="">
                                <hr>
                                <p><?php echo $post_content; ?></p>

                                <!-- Freeing results -->
                                <?php  //mysqli_stmt_free_result($stmt);  ?>
                                <?php
                                    if(isLoggedIn()):
                                ?>
                                <hr>

                                <div class="row">
                                    <p class="pull-right">
                                        <a class="<?php echo userLikedThisPost($p_id) ? 'unlike' : 'like' ?>" href="" data-toggle="tooltip" data-placement="top" title="<?php echo userLikedThisPost($p_id) ? 'I liked this before' : 'Want to like it?' ?>">
                                        <span class="<?php echo userLikedThisPost($p_id) ? 'glyphicon glyphicon-thumbs-down' : 'glyphicon glyphicon-thumbs-up' ?>"></span> <?php echo userLikedThisPost($p_id) ? 'Unlike' : 'Like' ?>
                                        </a>
                                    </p>
                                </div>
                                
                                    <?php else: ?>
                                    <div class="row">
                                        <p class="pull-right likes">You need to <a href="/login">login</a> to like</p>
                                    </div>

                                    <?php endif; ?>
                                <div class="row">
                                    <p class="pull-right likes">Likes: <?php getPostLikes($p_id); ?></p>
                                </div>

                    <?php    
                            }
                    ?>

                    <!-- Blog Comments -->

                    <!-- Comments Form -->

                    <?php  
                        if(isset($_POST['create_comment']))
                        {
                            $p_id = $_GET['p_id'];

                            $comment_author = $_POST['comment_author']; 
                            $comment_email = $_POST['comment_email']; 
                            $comment_content = $_POST['comment_content']; 

                            if(!empty($comment_author) && !empty($comment_email) && !empty($comment_content))
                            {
                                $query = "insert into comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date)";
                                $query .= "values ($p_id, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'disapproved', now())";
                                $insert_comment = mysqli_query($connection, $query);

                                if(!$insert_comment)
                                {
                                    die("QUERY ERROR: " . mysqli_error($connection));
                                }
                            }  
                            else
                            {
                                echo "<script> alert('Fields cannot be empty'); </script>";
                            }  
                        }
                    ?>
                    <div class="well">
                        <h4>Leave a Comment:</h4>
                        <form role="form" method="post">
                            <div class="form-group">
                                <label for="author">Author</label>
                                <input type="text" class="form-control" name="comment_author">
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="comment_email">
                            </div>

                            <div class="form-group">
                            <label for="comment">Your Comment</label>
                                <textarea name="comment_content" class="form-control" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" name="create_comment">Submit</button>
                        </form>
                    </div>

                    <hr>

                    <!-- Posted Comments -->
                    
                    <?php
                        $query = "select * from comments where comment_post_id = {$p_id} ";
                        $query .= "and comment_status = 'approved' ";
                        $query .= "order by comment_id desc";
                        $select_comment = mysqli_query($connection, $query);
                        if(!$select_comment)
                        {
                            die("QUERY ERROR: " . mysqli_error($connection));
                        }
                        while($row = mysqli_fetch_assoc($select_comment))
                        {
                            $comment_date = $row['comment_date'];
                            $comment_content = $row['comment_content'];
                            $comment_author = $row['comment_author'];
                    ?>

                        <!-- Comment -->
                        <div class="media">
                            <a class="pull-left" href="#">
                                <img class="media-object" src="http://placehold.it/64x64" alt="">
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading"><?php echo $comment_author; ?>
                                    <small><?php echo $comment_date; ?></small>
                                </h4>
                                <?php echo $comment_content; ?>
                            </div>
                        </div>
                        <!-- Comment -->

                    <?php        
                        }
                    }
                }    
                else
                {
                    header("location:index.php");
                }    
                ?>

                
                

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>
        </div>
        <!-- /.row -->

        <hr>

        <?php include "includes/footer.php"; ?>

        <script>
            $(document).ready(function()
            {
                $("[data-toggle = 'tooltip']").tooltip();
                
                var post_id = <?php echo $p_id; ?>;
                var user_id = <?php echo loggedInUserId(); ?>;

                //Liking
                $('.like').click(function()
                {
                    $.ajax
                    ({
                        url: "/post.php?p_id=<?php echo $p_id; ?>",
                        type: 'post',
                        data:
                        {
                            'liked': 1,
                            'post_id': post_id,
                            'user_id': user_id
                        }
                    });
                });

                //Unliking
                $('.unlike').click(function()
                {
                    $.ajax
                    ({
                        url: "/post.php?p_id=<?php echo $p_id; ?>",
                        type: 'post',
                        data:
                        {
                            'unliked': 1,
                            'post_id': post_id,
                            'user_id': user_id
                        }
                    });
                });
            });
        </script>
