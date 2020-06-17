<?php ob_start(); ?>
<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
    <!-- Navigation -->
<?php include "includes/navigation.php"; ?>    
    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">
                <?php  

                    if(isset($_GET['author']))
                    {
                        $post_author = $_GET['author'];
                        
                    }
                    $postQuery = "select * from posts where post_user = '$post_author'";
                    $postResult = mysqli_query($connection, $postQuery);
                    while($row = mysqli_fetch_assoc($postResult))
                    {
                        $post_id =$row['post_id'];
                        $post_title = $row['post_title'];
                        $post_author = $row['post_user'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];
                ?>

                        <h1 class="page-header">
                            Page Heading
                            <small>Secondary Text</small>
                        </h1>

                        <!-- Blog Post -->
                        <h2>
                            <a href="/post/<?php echo $post_id ?>"><?php echo $post_title; ?></a>
                        </h2>
                        <p class="lead">
                            by <a href="/author_posts/<?php echo $post_author; ?>"><?php echo $post_author; ?></a>
                        </p>
                        <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date; ?></p>
                        <hr>
                        <img class="img-responsive" src="/images/<?php echo $post_image; ?>" alt="">
                        <hr>
                        <p><?php echo $post_content; ?></p>

                        <hr>

                <?php        
                    }
                ?>
                
                

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>
        </div>
        <!-- /.row -->

        <hr>

        <?php include "includes/footer.php"; ?>
