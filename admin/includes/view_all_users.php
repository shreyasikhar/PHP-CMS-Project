<?php
if(!is_admin())
{
    redirect('/cms/admin');
}
$count = recordCount('users');
if($count == 0)
{
    echo "<h1 class='text-center'>No users available</h1>";
}
else
{
?>    
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Id</th>
                <th>Username</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Email</th>
                <th>Role</th>
                <th>Admin</th>
                <th>Subscriber</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
        
        <?php
            $users = "SELECT * FROM users";
            $users_result = mysqli_query($connection, $users);

            while($row = mysqli_fetch_assoc($users_result))
            {
                $user_id = $row['user_id'];
                $username = $row['username'];
                $user_firstname = $row['user_firstname'];
                $user_lastname = $row['user_lastname'];
                $user_password = $row['user_password'];
                $user_email = $row['user_email'];
                $user_role = $row['user_role'];
                $user_image = $row['user_image'];

                echo "<tr>";
                echo "<td>{$user_id}</td>";
                echo "<td>{$username}</td>";
                echo "<td>{$user_firstname}</td>";
                echo "<td>{$user_lastname}</td>";

                // $categories = "SELECT * FROM categories where cat_id = {$post_category_id}";
                // $get_categories = mysqli_query($connection, $categories);
                // while($row = mysqli_fetch_assoc($get_categories))
                // {
                //     $cat_title = $row['cat_title'];
                // }    
                echo "<td>{$user_email}</td>";

                // $query = "select * from posts where post_id = '{$comment_post_id}'";
                // $select_post_id = mysqli_query($connection, $query);
                // while($row = mysqli_fetch_assoc($select_post_id))
                // {
                //     $post_id = $row['post_id'];
                //     $post_title = $row['post_title'];
                //     echo "<td><a href='../post.php?p_id=$post_id'>{$post_title}</a></td>";
                // }
                
                echo "<td>{$user_role}</td>";
                echo "<td><a href='users.php?change_to_admin={$user_id}'>Admin</a></td>";
                echo "<td><a href='users.php?change_to_sub={$user_id}'>Subscriber</a></td>";
                echo "<td><a href='users.php?source=edit_user&edit_user={$user_id}'>Edit</a></td>";
                echo "<td><a href='users.php?delete={$user_id}'>Delete</a></td>";
                echo "<tr/>";
            }
        ?>
        </tbody>
    </table>

<?php
    if(isset($_GET['change_to_admin']))
    {
        if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == "admin")
        {
            $user_id  = escape($_GET['change_to_admin']);
            $query = "update users set user_role = 'admin' where user_id = {$user_id}";
            $change_to_admin = mysqli_query($connection, $query);
            header('location:users.php');
        }    
    }

    if(isset($_GET['change_to_sub']))
    {
        if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == "admin")
        {
            $user_id  = escape($_GET['change_to_sub']);
            $query = "update users set user_role = 'subscriber' where user_id = {$user_id}";
            $change_to_sub = mysqli_query($connection, $query);
            header('location:users.php');
        }    
    }

    if(isset($_GET['delete']))
    {
        if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == "admin")
        {
            $user_id  = mysqli_real_escape_string($connection, $_GET['delete']);
            $query = "delete from users where user_id = {$user_id}";
            $delete_user = mysqli_query($connection, $query);
            header('location:users.php');
        }
    }
}    
?>