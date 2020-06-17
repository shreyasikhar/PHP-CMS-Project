<?php
$count = recordCount('comments');
if($count == 0)
{
    echo "<h1 class='text-center'>No comments available</h1>";
}
else
{
?>    
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
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
            if(is_admin()):
                $comments = "SELECT * FROM comments";
            else:    
                $comments = "SELECT * FROM comments inner join posts on comments.comment_post_id = posts.post_id where posts.user_id='{$_SESSION['user_id']}'";
            endif;    
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
                echo "<td>{$comment_id}</td>";
                echo "<td>{$comment_author}</td>";
                echo "<td>{$comment_content}</td>";
                echo "<td>{$comment_email}</td>";
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
                echo "<td><a class='btn btn-primary' href='comments.php?approve={$comment_id}'>Approve</a></td>";
                echo "<td><a class='btn btn-info' href='comments.php?disapprove={$comment_id}'>Disapprove</a></td>";
        ?>
                <form method = "post">
                    <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
        <?php            
                    echo "<td><input class='btn btn-danger' type='submit' name='delete' value='Delete'></td>";
        ?>            
                </form>
        <?php          
                // echo "<td><a href='comments.php?delete={$comment_id}'>Delete</a></td>";
                echo "<tr/>";
            }
        ?>
        </tbody>
    </table>

<?php
    if(isset($_GET['approve']))
    {
        if(isset($_SESSION['user_role']))
        {
            $the_comment_id  = escape($_GET['approve']);
            $query = "update comments set comment_status = 'approved' where comment_id = {$the_comment_id}";
            $approve_comment = mysqli_query($connection, $query);
            header('location:comments.php');
        }    
    }

    if(isset($_GET['disapprove']))
    {
        if(isset($_SESSION['user_role']))
        {
            $the_comment_id  = escape($_GET['disapprove']);
            $query = "update comments set comment_status = 'disapproved' where comment_id = {$the_comment_id}";
            $disapprove_comment = mysqli_query($connection, $query);
            header('location:comments.php');
        }    
    }

    if(isset($_POST['delete']))
    {
        if(isset($_SESSION['user_role']))
        {
            $the_comment_id  = escape($_POST['comment_id']);
            $query = "delete from comments where comment_id = {$the_comment_id}";
            $delete_comment = mysqli_query($connection, $query);
            header('location:comments.php');
        }    
    }
}    
?>