<?php 
    //********** DATABASE HELPER FUNCTIONS **********//

    function redirect($location)
    {
        return header('location:'. $location);
    }

    function query($query)
    {
        global $connection;
        $result = mysqli_query($connection, $query);
        confirmQuery($result);
        return $result;
    }

    // to avoid mysql injection
    function escape($string)
    {
        global $connection;
        return mysqli_real_escape_string($connection, trim($string));
    }

    function fetchRecords($result)
    {
        return mysqli_fetch_array($result);
    }

    // to check query syntax correct or not
    function confirmQuery($result)
    {
        global $connection;
        if(!$result)
        {
            die("QUERY ERROR: " . mysqli_error($connection));
        }
    }

    //********** END DATABASE HELPER FUNCTIONS **********//






    //********** GENERAL HELPER FUNCTIONS **********//

    function get_user_name()
    {
        return isset($_SESSION['username']) ? $_SESSION['username'] : null;
    }

    function fetch_records($result)
    {
        return mysqli_fetch_array($result);
    }
    function count_records($result)
    {
        return mysqli_num_rows($result);
    }

    //to get entries count in the particular table
    function recordCount($table)
    {
        if(is_admin()):
            $queryResult = query("select * from ". $table);
        else:
            $queryResult = query("select * from ". $table ." where user_id={$_SESSION['user_id']}");
        endif;    
        $result = mysqli_num_rows($queryResult);
        if(isset($result))
        {
            return $result;
        }
        else
        {
            return 0;
        }
    }
    function get_all_categories_count()
    {
        $result = query("select * from categories");
        return count_records($result);
    }

    function get_all_user_posts()
    {
        return query("select * from posts where user_id=".loggedInUserId()."");
    }

    function get_all_posts_user_comments()
    {
        return query("select * from posts inner join comments on posts.post_id = comments.comment_post_id where posts.user_id=".loggedInUserId()."");
    }
    function get_all_posts_user_disapproved_comments()
    {
        return query("select * from posts inner join comments on posts.post_id = comments.comment_post_id where posts.user_id=".loggedInUserId()." and comment_status='disapproved'");
    }
    function get_all_posts_user_approved_comments()
    {
        return query("select * from posts inner join comments on posts.post_id = comments.comment_post_id where posts.user_id=".loggedInUserId()." and comment_status='approved'");
    }

    function get_all_user_categories()
    {
        if(is_admin())
        {
            return query("select * from categories");
        }
        else
        {
            return query("select distinct post_category_id from posts where user_id=".loggedInUserId()."");
        }
    }

    function get_all_user_published_posts()
    {
        return query("select * from posts where user_id=".loggedInUserId()." and post_status='published'");
    }

    function get_all_user_draft_posts()
    {
        return query("select * from posts where user_id=".loggedInUserId()." and post_status='draft'");
    }


    //********** END GENERAL HELPER FUNCTIONS **********//





    //********** AUTHENTICATION HELPER FUNCTIONS **********//

    //checks whether users is admin or not
    function is_admin()
    {
        if(isLoggedIn())
        {
            $username = $_SESSION['username'];
            $result = query("select user_role from users where username = '{$username}'");
            $row = fetchRecords($result);
            if($row['user_role'] == "admin")
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        return false;
    }

    //********** AUTHENTICATION HELPER FUNCTIONS **********//



    function currentUser()
    {
        if(isset($_SESSION['username']))
        {
            return $_SESSION['username'];
        }
        else
        {
            return false;
        }
    }

    function ifItIsMethod($method = null)
    {
        if($_SERVER['REQUEST_METHOD'] == strtoupper($method))
        {
            return true;
        }
        return false;
    } 

    function isLoggedIn()
    {
        if(isset($_SESSION['user_role']))
        {
            return true;
        }
        return false;
    }

    function loggedInUserId()
    {
        if(isLoggedIn())
        {
            $result = query("select * from users where username='".$_SESSION['username']."'");
            confirmQuery($result);
            $user = mysqli_fetch_array($result);
            return mysqli_num_rows($result) >= 1 ? $user['user_id'] : false;
        }
        return false;
    }

    function checkIfUserIsLoggedInAndRedirect($redirectLocation = null)
    {
        if(isLoggedIn())
        {
            redirect($redirectLocation);
        }
    }
    //********** END AUTHENTICATION HELPER FUNCTIONS **********//




    //********** GENERAL HELPER FUNCTIONS **********//
    function userLikedThisPost($post_id)
    {
        $result = query("select * from likes where user_id=".loggedInUserId()." and post_id={$post_id}");
        confirmQuery($result);
        return mysqli_num_rows($result) >= 1 ? true : false;
    }

    function getPostLikes($post_id)
    {
        $result = query("select * from likes where post_id={$post_id}");
        confirmQuery($result);
        echo mysqli_num_rows($result);
    }


    function login_user($username, $password)
    {   
        global $connection;
        $username = trim($username);
        $password = trim($password);
        $username = mysqli_real_escape_string($connection, $username);
        $password = mysqli_real_escape_string($connection, $password);
        
        $query = "select * from users where username = '{$username}'";
        $select_user = mysqli_query($connection, $query);
        confirmQuery($select_user);

        while($row = mysqli_fetch_assoc($select_user))
        {
            $db_user_id = $row["user_id"];
            $db_user_firstname = $row["user_firstname"];
            $db_user_lastname = $row["user_lastname"];
            $db_user_role = $row["user_role"];
            $db_username = $row["username"];
            $db_user_password = $row["user_password"];

            if(password_verify($password, $db_user_password))
            {
                $_SESSION['user_id'] = $db_user_id;
                $_SESSION['username'] = $db_username;
                $_SESSION['firstname'] = $db_user_firstname;
                $_SESSION['lastname'] = $db_user_lastname;
                $_SESSION['user_role'] = $db_user_role;
                redirect('/admin/');
            }
            else
            {
                return false;
            }
        }
        return true;
    }
    
    function register_user($username, $email, $password)
    {
        global $connection;
        $username = mysqli_real_escape_string($connection, $username);
        $email = mysqli_real_escape_string($connection, $email);
        $password = mysqli_real_escape_string($connection, $password);

        $hashed_password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

        $query = "insert into users (username, user_firstname, user_lastname, user_email, user_password, user_role) ";
        $query .= "values ('{$username}', '', '', '{$email}', '{$hashed_password}', 'subscriber')";
        $register_user = mysqli_query($connection, $query);
        confirmQuery($register_user);
    }

    //to check duplicate username entries
    function username_exists($username)
    {
        global $connection;
        $query = "select username from users where username = '{$username}'";
        $result = mysqli_query($connection, $query);
        confirmQuery($result);
        if(mysqli_num_rows($result) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function username_password_match($username, $password)
    {
        global $connection;
        $db_password = "";
        $query = "select * from users where username = '{$username}'";
        $result = mysqli_query($connection, $query);
        confirmQuery($result);
        $row = mysqli_fetch_array($result);
        if(isset($row['user_password']))
        {
            $db_password = $row['user_password'];
        }
        if(password_verify($password, $db_password))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    //to check duplicate email entries
    function email_exists($email)
    {
        global $connection;
        $query = "select user_email from users where user_email = '{$email}'";
        $result = mysqli_query($connection, $query);
        confirmQuery($result);
        if(mysqli_num_rows($result) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //to get the count for displaying in the google graph 
    function checkStatus($table, $column, $status)
    {
        global $connection;
        $query = "select * from $table where $column = '$status'";
        $result = mysqli_query($connection, $query);
        return mysqli_num_rows($result);
    }

    function imagePlaceholder($image)
    {
        if(!$image)
        {
            return 'notfound.png';
        }
        else
        {
            return $image;
        }
    }


    // to check number of users accessing the web application currently
    function users_online()
    {
        if(isset($_GET['onlineusers']))
        {    
            global $connection;
            if(!$connection)
            {
                session_start();
                include "../includes/db.php";
            }
            $session = session_id();
            $time = time();
            $timeout_in_seconds = 5;
            $timeout = $time - $timeout_in_seconds;
            $query = "select * from users_online where session = '$session'";
            $session_query = mysqli_query($connection, $query);
            $count = mysqli_num_rows($session_query);
            if($count == NULL)
            {
                mysqli_query($connection, "insert into users_online (session, time) values ('$session', '$time')");
            }
            else
            {
                mysqli_query($connection, "update users_online set time = '$time' where session = '$session'");
            }
            $users_online_query = mysqli_query($connection, "select * from users_online where time > '$timeout'");
            $user_count = mysqli_num_rows($users_online_query);
            echo $user_count;
        }
    }
    users_online();

    function insert_categories()
    {
        if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == "admin")
        {
            global $connection;
            if(isset($_POST['submit']))
            {
                $cat_title = escape($_POST['cat_title']);
                if($cat_title == "" || empty($cat_title))
                {
                    echo "This field should not be empty";
                }
                else
                {
                    $stmt = mysqli_prepare($connection, "insert into categories(user_id, cat_title) values (?, ?)");
                    mysqli_stmt_bind_param($stmt, 'is', $_SESSION['user_id'], $cat_title);
                    mysqli_stmt_execute($stmt);

                    if(!$stmt)
                    {
                        die("QUERY FAILED: " . mysqli_error($connection));
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }    
    }

    function findAllCategories()
    {
        global $connection;
        $categories = "SELECT * FROM categories";
        $categories_result = mysqli_query($connection, $categories);

        while($row = mysqli_fetch_assoc($categories_result))
        {
            $cat_id = $row['cat_id'];
            $cat_title = $row['cat_title'];
            echo "<tr>";
            echo "<td>{$cat_id}</td>";
            echo "<td>{$cat_title}</td>";
            if(is_admin()):
            echo "<td><a class='btn btn-info' href='categories.php?edit={$cat_id}'>Edit</a></td>";
            ?>
                <form method = "post">
                    <input type="hidden" name="cat_id" value="<?php echo $cat_id; ?>">
            <?php            
                    echo "<td><input class='btn btn-danger' type='submit' name='delete' value='Delete'></td>";
            ?>            
                </form>
            <?php  
            endif;   
            //echo "<td><a href='categories.php?delete={$cat_id}'>Delete</a></td>";
            echo "<tr/>";
        }
    }

    function deleteCategories()
    {
        if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == "admin")
        {
            global $connection;
            if(isset($_POST['delete']))
            {
                $the_cat_id = escape($_POST['cat_id']);
                $query = "delete from categories where cat_id = {$the_cat_id}";
                $delete_query = mysqli_query($connection, $query);
                header('location:categories.php');
            }
        }    
    }

    //********** END GENERAL HELPER FUNCTIONS **********//
?>