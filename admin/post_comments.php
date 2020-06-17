<?php include "includes/admin_header.php"; ?>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include "includes/admin_navigation.php"; ?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Welcome to Admin
                            <small>Author</small>
                        </h1>

<?php
if(isset($_POST['checkBoxArray']))
{
    foreach($_POST['checkBoxArray'] as $commentValueId)
    {
        $commentValueId = escape($commentValueId);
        $bulk_options = escape($_POST['bulk_options']);
        switch($bulk_options)
        {
            case 'approve':
                $query = "update comments set comment_status = '{$bulk_options}' where comment_id = {$commentValueId} ";
                $update_to_approved = mysqli_query($connection, $query);
                confirmQuery($update_to_approved);
            break;

            case 'disapprove':
                $query = "update comments set comment_status = '{$bulk_options}' where comment_id = {$commentValueId} ";
                $update_to_disapproved = mysqli_query($connection, $query);
                confirmQuery($update_to_disapproved);
            break;

            case 'delete':
                $query = "delete from comments where comment_id = {$commentValueId} ";
                $delete_comments = mysqli_query($connection, $query);
                confirmQuery($delete_comments);
            break;

            case 'clone':
                $query = "select * from comments where comment_id = {$commentValueId}";
                $clone_comments = mysqli_query($connection, $query);
                confirmQuery($clone_comments);
                while ($row = mysqli_fetch_array($clone_comments))
                {
                    $comment_post_id = $row['comment_post_id'];
                    $comment_date = $row['comment_date'];
                    $comment_author = $row['comment_author'];
                    $comment_status = $row['comment_status'];
                    $comment_email = $row['comment_email'];
                    $comment_content = $row['comment_content'];
                }
                $query = "insert into comments(comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) ";
                $query .= "values ('{$comment_post_id}', '{$comment_author}', '{$comment_email}', '{$comment_content}', '{$comment_status}',  now() )";
                $insert_comment = mysqli_query($connection, $query);
                confirmQuery($insert_comment);
            break;

        }
    }
}
?>
<form method="post" action="">
    <table class="table table-bordered table-hover">
        <div id="bulkOptionsContainer" style='padding:0px' class="col-xs-4">
            <select class="form-control" name="bulk_options">
                <option value="">Select Options</option>
                <option value="approved">Approve</option>
                <option value="disapproved">Disapprove</option>
                <option value="delete">Delete</option>
                <option value="clone">Clone</option>
            </select>
        </div>
        <div class="col-xs-4">
            <input type="submit" name="submit" class="btn btn-success" value="Apply">
            <!-- <a href="posts.php?source=add_post" class="btn btn-primary">Add New</a> -->
        </div>
     
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><input id='selectAllBoxes' type='checkbox'></th>
                                    <th>Id</th>
                                    <th>Author</th>
                                    <th>Comment</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>In Response to</th>
                                    <th>Date</th>
                                    <th>Approve</th>
                                    <th>Disapprove</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            <?php
                                $comments = "SELECT * FROM comments where comment_post_id =" . mysqli_real_escape_string($connection, $_GET['post_id']);
                                $comments_result = mysqli_query($connection, $comments);

                                while($row = mysqli_fetch_assoc($comments_result))
                                {
                                    $comment_id = $row['comment_id'];
                                    $comment_post_id = $row['comment_post_id'];
                                    $comment_author = $row['comment_author'];
                                    $comment_email = $row['comment_email'];
                                    $comment_content = $row['comment_content'];
                                    $comment_status = $row['comment_status'];
                                    $comment_date = $row['comment_date'];

                                    echo "<tr>";
                            ?>
                                <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $comment_id; ?>'></td>
                            <?php        
                                    echo "<td>{$comment_id}</td>";
                                    echo "<td>{$comment_author}</td>";
                                    echo "<td>{$comment_content}</td>";
                                    echo "<td>{$comment_email}</td>";

                                    // $categories = "SELECT * FROM categories where cat_id = {$post_category_id}";
                                    // $get_categories = mysqli_query($connection, $categories);
                                    // while($row = mysqli_fetch_assoc($get_categories))
                                    // {
                                    //     $cat_title = $row['cat_title'];
                                    // }    
                                    echo "<td>{$comment_status}</td>";

                                    $query = "select * from posts where post_id = '{$comment_post_id}'";
                                    $select_post_id = mysqli_query($connection, $query);
                                    while($row = mysqli_fetch_assoc($select_post_id))
                                    {
                                        $post_id = $row['post_id'];
                                        $post_title = $row['post_title'];
                                        echo "<td><a href='../post.php?p_id=$post_id'>{$post_title}</a></td>";
                                    }
                                    
                                    echo "<td>{$comment_date}</td>";
                                    echo "<td><a href='post_comments.php?approve={$comment_id}&post_id=".escape($_GET['post_id'])."'>Approve</a></td>";
                                    echo "<td><a href='post_comments.php?disapprove={$comment_id}&post_id=".escape($_GET['post_id'])."'>Disapprove</a></td>";
                                    echo "<td><a href='post_comments.php?delete={$comment_id}&post_id=".escape($_GET['post_id'])."'>Delete</a></td>";
                                    echo "<tr/>";
                                }
                            ?>
                            </tbody>
                        </table>
</form>    

                        <?php
                            if(isset($_GET['approve']))
                            {
                                if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == "admin")
                                {
                                    $the_comment_id  = escape($_GET['approve']);
                                    $query = "update comments set comment_status = 'approved' where comment_id = {$the_comment_id}";
                                    $approve_comment = mysqli_query($connection, $query);
                                    header("location:post_comments.php?post_id=".$_GET['post_id']."");
                                }    
                            }

                            if(isset($_GET['disapprove']))
                            {
                                if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == "admin")
                                {
                                    $the_comment_id  = escape($_GET['disapprove']);
                                    $query = "update comments set comment_status = 'disapproved' where comment_id = {$the_comment_id}";
                                    $disapprove_comment = mysqli_query($connection, $query);
                                    header("location:post_comments.php?post_id=".$_GET['post_id']."");
                                }    
                            }

                            if(isset($_GET['delete']))
                            {
                                if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == "admin")
                                {
                                    $the_comment_id  = escape($_GET['delete']);
                                    $query = "delete from comments where comment_id = {$the_comment_id}";
                                    $delete_comment = mysqli_query($connection, $query);
                                    header("location:post_comments.php?post_id=".$_GET['post_id']."");
                                }    
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
