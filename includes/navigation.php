<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">CMS Blog</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">

                <?php
                    $query = "SELECT * FROM categories";
                    $result = mysqli_query($connection, $query);
                    while($row = mysqli_fetch_assoc($result))
                    {
                        $cat_id = $row['cat_id'];
                        $cat_title = $row['cat_title'];

                        $category_class = '';
                        $contact_class = '';
                        $contact = 'contact.php';

                        $pageName = basename($_SERVER['PHP_SELF']);
                        if(isset($_GET['category']) && $_GET['category'] == $cat_id)
                        {
                            $category_class = 'active';
                        }
                        else if($pageName == $contact)
                        {
                            $contact_class = 'active';
                        }

                        echo "<li class = '{$category_class}'><a href='/category/{$cat_id}'>{$cat_title}</a></li>";
                    }
                ?>
                    <li class='<?php echo $contact_class; ?>'>
                        <a href="/contact">Contact Us</a>
                    </li>

                    <?php if(isLoggedIn()): ?>
                        <li>
                            <a href="/admin">Dashboard</a>
                        </li>
                        <li>
                            <a href="/includes/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="/login">Login</a>
                        </li>
                    <?php endif; ?>
                    
                <?php
                    if(isset($_SESSION['user_role']))
                    {
                        if(isset($_GET['p_id']))
                        {
                            $p_id = $_GET['p_id'];
                            echo "<li><a href='/admin/posts.php?source=edit_post&p_id={$p_id}'>Edit Post</a></li>";
                        }
                    }
                ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
</nav>
