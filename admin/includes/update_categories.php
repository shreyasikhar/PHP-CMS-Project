<form action="" method="post">
    <div class="form-group">
        <label for="cat-title">Edit Category</label>

    <?php
        if(isset($_GET['edit']))
        {
            if(is_admin($_SESSION['username']))
            {       
                $cat_id = escape($_GET['edit']); 
                $categories = "SELECT * FROM categories where cat_id = {$cat_id}";
                $categories_edit = mysqli_query($connection, $categories);
                while($row = mysqli_fetch_assoc($categories_edit))
                {
                    $cat_id = $row['cat_id'];
                    $cat_title = $row['cat_title'];
        ?>
        <input value="<?php if(isset($cat_title)) echo $cat_title; ?>" type="text" class="form-control" name="cat_title">
        <?php
                }
            }    
        }   
    ?>

    <?php
        // UPDATE CATEGORY
        if(isset($_POST['update']))
        {
            $the_cat_title = escape($_POST['cat_title']);
            $stmt = mysqli_prepare($connection, "update categories set cat_title = ? where cat_id=?");
            mysqli_stmt_bind_param($stmt, 'si', $the_cat_title, $cat_id);
            mysqli_stmt_execute($stmt);
            if(!$stmt)
            {
                die("QUERY ERRROR: " . mysqli_error($connection));
            }
            mysqli_stmt_close($stmt);
        }
    ?>
    </div> 
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update" value="Update Category">
    </div>    
</form>