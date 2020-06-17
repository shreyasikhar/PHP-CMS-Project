<?php

$count = recordCount('posts');
if($count == 0)
{
    echo "<h1 class='text-center'>No posts available</h1>";
}
else
{
    include "delete_modal.php";
    if(isset($_POST['checkBoxArray']))
    {
        foreach($_POST['checkBoxArray'] as $postValueId)
        {
            $bulk_options = escape($_POST['bulk_options']);
            $postValueId = escape($postValueId);
            switch($bulk_options)
            {
                case 'published':
                    $query = "update posts set post_status = '{$bulk_options}' where post_id = {$postValueId} ";
                    $update_to_publish = mysqli_query($connection, $query);
                    confirmQuery($update_to_publish);
                break;

                case 'draft':
                    $query = "update posts set post_status = '{$bulk_options}' where post_id = {$postValueId} ";
                    $update_to_draft = mysqli_query($connection, $query);
                    confirmQuery($update_to_draft);
                break;

                case 'delete':
                    $query = "delete from posts where post_id = {$postValueId} ";
                    $delete_posts = mysqli_query($connection, $query);
                    confirmQuery($delete_posts);
                break;

                case 'clone':
                    $query = "select * from posts where post_id = {$postValueId}";
                    $clone_posts = mysqli_query($connection, $query);
                    confirmQuery($clone_posts);
                    while ($row = mysqli_fetch_array($clone_posts))
                    {
                        $post_title = $row['post_title'];
                        $post_category_id = $row['post_category_id'];
                        $post_date = $row['post_date'];
                        $post_author = $row['post_author'];
                        $post_user = $row['post_user'];
                        $post_status = $row['post_status'];
                        $post_image = $row['post_image'];
                        $post_tags = $row['post_tags'];
                        $post_content = $row['post_content'];
                    }
                    $query = "insert into posts(post_category_id, post_title, post_author, post_user, post_date, post_image, post_content, post_tags, post_status) ";
                    $query .= "values ('{$post_category_id}', '{$post_title}', '{$post_author}', '{$post_user}', now(), '{$post_image}', '{$post_content}', '{$post_tags}', '{$post_status}')";
                    $insert_post = mysqli_query($connection, $query);
                    confirmQuery($insert_post);
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
                <option value="published">Publish</option>
                <option value="draft">Draft</option>
                <option value="delete">Delete</option>
                <option value="clone">Clone</option>
            </select>
        </div>
        <div class="col-xs-4">
            <input type="submit" name="submit" class="btn btn-success" value="Apply">
            <a href="posts.php?source=add_post" class="btn btn-primary">Add New</a>
        </div>
        <thead>
            <tr>
                <th><input id='selectAllBoxes' type='checkbox'></th>
                <th>Id</th>
                <th>User</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Image</th>
                <th>Tags</th>
                <th>Comments</th>
                <th>Date</th>
                <th>View</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Views</th>
            </tr>
        </thead>
        <tbody>
        
        <?php
            if(is_admin())
            {
                $posts = "SELECT posts.post_id, posts.post_author, posts.post_user, posts.post_title, posts.post_category_id, posts.post_status, posts.post_image, ";
                $posts .= "posts.post_tags, posts.post_comment_count, posts.post_date, posts.post_views_count, categories.cat_id, categories.cat_title ";
                $posts .= "from posts ";
                $posts .= "left join categories on posts.post_category_id = categories.cat_id order by post_id desc";
            }
            else
            {
                $posts = "SELECT posts.post_id, posts.post_author, posts.post_user, posts.post_title, posts.post_category_id, posts.post_status, posts.post_image, ";
                $posts .= "posts.post_tags, posts.post_comment_count, posts.post_date, posts.post_views_count, categories.cat_id, categories.cat_title ";
                $posts .= "from posts ";
                $posts .= "left join categories on posts.post_category_id = categories.cat_id where posts.user_id ={$_SESSION['user_id']} order by post_id desc";
            } 
            $posts_result = mysqli_query($connection, $posts);

            while($row = mysqli_fetch_assoc($posts_result))
            {
                $post_id = $row['post_id'];
                $post_author = $row['post_author'];
                $post_user = $row['post_user'];
                $post_title = $row['post_title'];
                $post_category_id = $row['post_category_id'];
                $post_status = $row['post_status'];
                $post_image = $row['post_image'];
                $post_tags = $row['post_tags'];
                $post_comment_count = $row['post_comment_count'];
                $post_date = $row['post_date'];
                $post_views_count = $row['post_views_count'];
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];
                if(empty($post_tags))
                {
                    $post_tags = "No tags";
                }
                echo "<tr>";

        ?>
                <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $post_id; ?>'></td>
        <?php        

                echo "<td>{$post_id}</td>";

                
                if(!empty($post_user))
                {
                    echo "<td>{$post_user}</td>";
                }
                else if(!empty($post_author))
                {
                    echo "<td>{$post_author}</td>";

                }
 
                echo "<td>{$post_title}</td>";
                echo "<td>{$cat_title}</td>";
                echo "<td>{$post_status}</td>";
                echo "<td><img class='img-responsive' src='../images/{$post_image}' width='100' alt='image'></td>";
                echo "<td>{$post_tags}</td>";

                $query = "select comment_id from comments where comment_post_id = $post_id";
                $comment_count_result = mysqli_query($connection, $query);
                confirmQuery($comment_count_result);
                $comment_count = mysqli_num_rows($comment_count_result);

                echo "<td><a href='post_comments.php?post_id=$post_id'>{$comment_count}</a></td>";
                echo "<td>{$post_date}</td>";                
                echo "<td><a class='btn btn-primary' href='../post.php?p_id={$post_id}'>View Post</a></td>";
                echo "<td><a class='btn btn-info' href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
        ?>
                <form method = "post">
                    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
        <?php            
                    echo "<td><input class='btn btn-danger' type='submit' name='delete' value='Delete'></td>";
        ?>            
                </form>
        <?php        
                // echo "<td><a rel='$post_id' href='javascript:void(0)' class='delete_link'>Delete</a></td>";
                //echo "<td><a onClick =\"javascript: return confirm('Are you sure you want to delete?');\"  href='posts.php?delete={$post_id}'>Delete</a></td>";
                echo "<td><a onClick =\"javascript: return confirm('Do you want to reset the view count?');\" href='posts.php?reset={$post_id}'>{$post_views_count}</a></td>";
                echo "<tr/>";
            }
        ?>
        </tbody>
    </table>
</form>    

<?php
    if(isset($_POST['delete']))
    {
        if(isset($_SESSION['user_role']))
        {    
            $the_post_id  = escape($_POST['post_id']);
            $query = "delete from posts where post_id = {$the_post_id}";
            $delete_post = mysqli_query($connection, $query);
            confirmQuery($delete_post);
            header('location:posts.php');
        }    
    }
    if(isset($_GET['reset']))
    {
        if(isset($_SESSION['user_role']))
        {
            $the_post_id  = escape($_GET['reset']);
            $query = "update posts set post_views_count = 0 where post_id = {$the_post_id}";
            $reset_count = mysqli_query($connection, $query);
            confirmQuery($reset_count);
            header('location:posts.php');
        }
    }
}    
?>
<script>
    $(document).ready(function()
    {
        $('.delete_link').on('click', function()
        {
            var id = $(this).attr("rel");
            var delete_url = "posts.php?delete="+ id +"";

            $('.modal_delete_link').attr('href', delete_url);
            
            $('#myModal').modal('show');
        });
    });
</script>