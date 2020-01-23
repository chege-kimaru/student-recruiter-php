<?php
require_once __DIR__ . '/bootstrap.php';
requireEmployer();
require_once __DIR__ . '/_partials/head.php';
require_once __DIR__ . '/_partials/nav.php';

try {
    $job_categories = getAllCategories();
    $jobs = getAllJobs();

    $job = getJob(request()->get('jobId'));
    if(empty($job)) {
        echo "<center><h1>This job does not esxist</h1></center>";
        exit;
    }

    if($job["company_id"] !== getCompanyByEmployerId(user("id"))["id"]) {
        echo "</center><h1>You are not authorized to edit this job.</h1></center>";
        exit;
    }
} catch (Exception $e) {
    echo "An error occurred while presenting form.<br> $e";
}

?>

<!-- Titlebar
================================================== -->
<div id="titlebar" class="single submit-page">
    <div class="container">

        <div class="sixteen columns">
            <h2><i class="fa fa-plus-circle"></i> Update Job</h2>
        </div>

    </div>
</div>


<!-- Content
================================================== -->
<div class="container">

    <!-- Submit Page -->
    <div class="sixteen columns">
        <div class="submit-page">

            <!--			<!-- Notice -->
            <!--			<div class="notification notice closeable margin-bottom-40">-->
            <!--				<p><span>Have an account?</span> If you don’t have an account you can create one below by entering your email address. A password will be automatically emailed to you.</p>-->
            <!--			</div>-->
            <form method="post" class="login" method="post" action="procedures/editJob.php">

                <?php print display_errors(); ?>
                <?php print display_success(); ?>

                <?php include_once __DIR__ . '/_partials/job_form.php'; ?>

                <input class="button big margin-top-5" type="submit" value="Update"/>

            </form>
        </div>
    </div>

</div>


<?php require_once __DIR__ . '/_partials/footer.php'; ?>

