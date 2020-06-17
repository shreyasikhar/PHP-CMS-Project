<?php include "includes/header.php"; ?>
<?php include "includes/db.php"; ?>
    <!-- Navigation -->
<?php include "includes/navigation.php"; ?>    
    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">
                <?php  
                    if(isset($_GET['category']))
                    {
                        $p_id = $_GET['category'];
                    
                        if(isset($_SESSION['username']) && $_SESSION['user_role'] == 'admin')
                        {
                            $stmt1 = mysqli_prepare($connection, "select post_id, post_title, post_author, post_user, post_date, post_image, post_content from posts where post_category_id = ?");
                        }
                        else
                        {
                            $stmt2 = mysqli_prepare($connection, "select post_id, post_title, post_author, post_user, post_date, post_image, post_content from posts where post_category_id = ? and post_status = ?");
                            $published = 'published';
                        }    
                        if(isset($stmt1))
                        {
                            mysqli_stmt_bind_param($stmt1, 'i', $p_id);
                            mysqli_stmt_execute($stmt1);
                            mysqli_stmt_bind_result($stmt1, $post_id, $post_title, $post_author, $post_user, $post_date, $post_image, $post_content);
                            mysqli_stmt_store_result($stmt1);
                            $stmt = $stmt1;
                        }
                        else if(isset($stmt2))
                        {
                            mysqli_stmt_bind_param($stmt2, 'is', $p_id, $published);
                            mysqli_stmt_execute($stmt2);
                            mysqli_stmt_bind_result($stmt2, $post_id, $post_title, $post_author, $post_user, $post_date, $post_image, $post_content);
                            mysqli_stmt_store_result($stmt2);
                            $stmt = $stmt2;
                        }
                        
                        if(mysqli_stmt_num_rows($stmt) == 0)
                        {
                            echo "<h1 class='text-center'>No posts available</h1>";
                        }
                        else
                        {
                            while(mysqli_stmt_fetch($stmt))
                            {
                        ?>

                            <h1 class="page-header">
                                Posts
                            </h1>

                            <!-- Blog Post -->
                            <h2>
                                <a href="/post/<?php echo $post_id; ?>"><?php echo $post_title; ?></a>
                            </h2>
                            <p class="lead">
                                by <a href="/author_posts/<?php echo $post_user; ?>"><?php echo $post_user; ?></a>
                            </p>
                            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date; ?></p>
                            <hr>
                            <img class="img-responsive" src="/images/<?php echo $post_image; ?>" alt="">
                            <hr>
                            <p><?php echo $post_content; ?></p>
                            <a class="btn btn-primary" href="/post/<?php echo $post_id; ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                            <hr>

                    <?php        
                            }
                            mysqli_stmt_close($stmt);
                        }
                    }    
                    else
                    {
                        header('location:index.php');
                    }    
                ?>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>
        </div>
        <!-- /.row -->

        <hr>

        <?php include "includes/footer.php"; ?>
