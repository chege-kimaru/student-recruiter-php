<?php

require_once __DIR__ . '/../bootstrap.php';

requireEmployer();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $session->getFlashBag()->add('error', 'Sorry, access to the page you tried to visit is denied. Or is currently not available!');
    redirect('../index.php');
}

//Common for both employer and user
$title = request()->get('name');
$description = request()->get('description');

if (!$title) {
    $session->getFlashBag()->add('error', 'Title is required.');
    redirect("../add-job-category.php");
}

try {
    $jobCategoryId = createJobCategory($title, $description);
} catch (Exception $e) {
    $session->getFlashBag()->add('error', 'An error occurred while adding job category.
         Please try again later.\n'.$e);
    redirect("../add-job-category.php");
}

$session->getFlashBag()->add('success', 'Successfully added job category.');
redirect("../add-job-category.php");
