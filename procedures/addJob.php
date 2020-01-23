<?php

require_once __DIR__ . '/../bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $session->getFlashBag()->add('error', 'Sorry, access to the page you tried to visit is denied. Or is currently not available!');
    redirect('../index.php');
}

requireEmployer();

$valid = true;
$errors = "";

//Common for both employer and user
$title = request()->get('title');
$job_category_id = request()->get('job_category_id');
$level = request()->get('level');
$type = request()->get('type');
$deadline = request()->get('deadline');
$salary = request()->get('salary');
$description = request()->get('description');
$skills = request()->get('skills');
$optional_skills = request()->get('optional_skills');
$location = request()->get('location');
$responsibilities = request()->get('responsibilities');
$application_url = request()->get('application_url');
$job_tags = request()->get('job_tags');

try{
    $company_id = getCompanyByEmployerId(user("id"))["id"];
} catch (Exception $e) {
    $valid = false;
    $errors .= "Error! Company does not exist. Please login with your company account.\n $e\n";
}

if (!$title) {
    $valid = false;
    $errors .= "Job title is required. \n";
}
if (!$job_category_id) {
    $valid = false;
    $errors .= "Job Category is required. \n";
}
if (!$description) {
    $valid = false;
    $errors .= "Job Description is required. \n";
}
if (!$deadline) {
    $valid = false;
    $errors .= "Job Application Deadline is required. \n";
}

if(!$valid) {
    $session->getFlashBag()->add('error', $errors);
    redirect("../add-job.php");
}

try {
    $jobId = createJob($title, $job_category_id, $company_id, $level, $type, $deadline, $salary, $description,
        $skills, $optional_skills, $location, $responsibilities, $application_url, $job_tags);
} catch (Exception $e) {
    $session->getFlashBag()->add('error', 'An error occurred while adding job.
         Please try again later.<br/>'.$company_id.$e);
    redirect("../add-job.php");
}

$session->getFlashBag()->add('success', 'Successfully added job.');
redirect("../browse-jobs.php");
