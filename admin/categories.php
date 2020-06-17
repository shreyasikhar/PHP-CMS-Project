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
                        Welcome <?php echo $_SESSION['username']; ?>
                        </h1>

                        <div class="col-lg-6">

                        <?php
                            insert_categories();
                        ?>

                            <form action="" method="post">
                                <div class="form-group" data-toggle="tooltip" data-placement="top" title="Only admin can add category">
                                    <label for="cat-title">Add Category</label>
                                    <?php if(is_admin()): ?>
                                    <input type="text" class="form-control" name="cat_title">
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="cat_title" disabled>
                                    <?php endif; ?>    
                                </div> 
                                <div class="form-group">
                                    <?php if(is_admin()): ?>
                                    <input class="btn btn-primary" type="submit" name="submit" value="Add Category">
                                    <?php else: ?>
                                    <input class="btn btn-primary" type="submit" name="submit" value="Add Category" disabled>
                                    <?php endif; ?>
                                </div>    
                            </form>

                        <?php 
                            //UPDATE AND INCLUDE QUERY
                            if(isset($_GET['edit']))
                            {
                                $cat_id = escape($_GET['edit']);
                                include "includes/update_categories.php";
                            }
                        ?>    

                        </div> 
                        <div class="col-lg-6">
                        <?php
                            $count = get_all_categories_count();
                            if($count == 0)
                            {
                                echo "<h1 class='text-center'>No categories available</h1>";
                            }
                            else
                            {
                        ?>
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Category Title</th>
                            <?php if(is_admin()): ?>
                                    <th>Edit</th>
                                    <th>Delete</th>
                            <?php endif; ?>        
                                </tr>
                                </thead>
                                <tbody>

                                <?php findAllCategories(); ?>

                                <?php deleteCategories(); ?>
                                
                                </tbody>
                            </table>
                        <?php }  ?>    
                        </div>   
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    <?php include "includes/admin_footer.php"; ?>

    <script>
        $(document).ready(function()
        {
            $("[data-toggle = 'tooltip']").tooltip();
        });
    </script>
