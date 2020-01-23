<?php
require_once __DIR__ . '/bootstrap.php';
requireEmployer();
require_once __DIR__ . '/_partials/head.php';
require_once __DIR__ . '/_partials/nav.php';

try {
    $job_categories = getAllCategories();
} catch (Exception $e) {
    echo "An error occurred while fetching available categories";
}

?>

    <!-- Titlebar
    ================================================== -->
    <div id="titlebar" class="single submit-page">
    <div class="container">

    <div class="sixteen columns">
    <h2><i class="fa fa-plus-circle"></i> Add Job Category</h2>
    <ol>
        <?php foreach ($job_categories as $catg) : ?>
            <li><?php echo "<b>{$catg["name"]}</b>"; ?></li>
        <?php endforeach; ?>
    </ol>
    </div>

    </div>
    </div>


    <!-- Content
    ================================================== -->
    <div class="container">

        <!-- Submit Page -->
        <div class="sixteen columns">
            <div class="submit-page">

                <form method="post" class="login" method="post" action="procedures/addJobCategory.php">

                    <?php print display_errors(); ?>
                    <?php print display_success(); ?>

                    <!-- Email -->
                    <div class="form">
                        <h5>Category Title:</h5>
                        <input class="search-field" type="text" name="name" placeholder="Title" value=""/>
                    </div>

                    <!-- Title -->
                    <div class="form">
                        <h5>Description (Optional)</h5>
                        <input class="search-field" type="text" name="description" placeholder="Description" value=""/>
                    </div>

                    <input class="button big margin-top-5" value="Add" type="submit"/>

                </form>


            </div>
        </div>

    </div>


    <?php require_once __DIR__ . '/_partials/footer.php'; ?>