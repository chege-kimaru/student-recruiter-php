<?php

use Symfony\Component\HttpFoundation\Cookie;

require_once __DIR__ . '/../bootstrap.php';

function redirectToRegister($reg)
{
    if ($reg === 'employer') {
        redirect('../register_company.php#tab2');
    } else if ($reg === 'user') {
        redirect('../register.php#tab1');
    }
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $session->getFlashBag()->add('error', 'Sorry, access to the page you tried to visit is denied. Or is currently not available!');
    redirect('../index.php');
}

//Common for both employer and user
$user_type = request()->get('reg');
$email = request()->get('email');
$phone = request()->get('phone');
$password = request()->get('password');
$confirm_password = request()->get('confirm_password');

//for user
$firstName = request()->get('firstName');
$lastName = request()->get('lastName');
$educationLevel = request()->get('education_level');

$cv = $_FILES['cv']['name'];
$tmp = $_FILES['cv']['tmp_name'];
$type = $_FILES['cv']['type'];
$file_size = $_FILES['cv']['size'];

//for employer
$name = request()->get('name');
$location = request()->get('location');
$website = request()->get('website');
$description = request()->get('description');
$video_link = request()->get('video_link');
$twitter_username = request()->get('twitter_username');

$logo = $_FILES['logo']['name'];
$logo_tmp = $_FILES['logo']['tmp_name'];
$logo_type = $_FILES['logo']['type'];
$logo_file_size = $_FILES['logo']['size'];

$errors = '';
$valid = true;

if (!$email) {
    $valid = false;
    $errors .= 'Email is required.<br>';
}
if (!$phone) {
    $valid = false;
    $errors .= 'Phone Number is required.<br>';
}

if ($user_type === 'user') {
    if (!$firstName) {
        $valid = false;
        $errors .= 'First Name is required.<br>';
    }
    if (!$lastName) {
        $valid = false;
        $errors .= 'Last Name is required.<br>';
    }
    if (($file_size > 2097152)) {
        $valid = false;
        $errors .= 'The uploaded CV has exceeded the file size limit (2MB)<br>';
    }

//    if (($type == "image/jpeg") || ($type == "image/jpg") || ($type == "image/png")) {
//
//    }
}
if ($user_type === 'employer') {
    if (!$name) {
        $valid = false;
        $errors .= 'Company name is required.<br>';
    }
    if (($logo_file_size > 2097152)) {
        $valid = false;
        $errors .= 'The uploaded CV has exceeded the file size limit (2MB)<br>';
    }

    if (($logo_type == "image/jpeg") || ($logo_type == "image/jpg") || ($logo_type == "image/png")) {

    } else {
        $valid = false;
        $errors .= 'The allowed file formats are jpeg, jpg and png. Please ensure the logo you uploaded is of one these types.<br>';
    }
}

if (!$valid) {
    $session->getFlashBag()->add('error', $errors);
    redirectToRegister($user_type);
}


// Confirm passwords are the same
$passwordEqual = request()->get('password') === request()->get('confirm_password');
if (!$passwordEqual) {
    $session->getFlashBag()->add('error', 'Passwords do not match');
    redirectToRegister($user_type);
}

// Check to see if user exists already with that email
try{
    $user = findUserByEmail(request()->get('email'));
}catch (Exception $e) {
    $session->getFlashBag()->add('error', 'An error occurred while registering you to the system.
         Please try again later.\n'.$e);
    redirectToRegister($user_type);
}

if (!empty($user)) {
    $session->getFlashBag()->add('error', 'Account with email already exists!');
    redirectToRegister($user_type);
}

// Generate a new bcrypt password
$hash = password_hash(request()->get('password'), PASSWORD_DEFAULT);

if ($user_type === 'employer') {
    try {
        $companyId = createCompany($name, $website, $video_link, $twitter_username, null, $location, $description);
        $employerId = createUser($firstName, $lastName, $email, $phone, $educationLevel, null, $companyId, $hash, 2);
    } catch (Exception $e) {
        $session->getFlashBag()->add('error', 'An error occurred while registering you to the system.
         Please try again later.\n'.$e);
        redirectToRegister($user_type);
    }
} else if ($user_type === 'user') {
    try {
        $userId = createUser($firstName, $lastName, $email, $phone, $educationLevel, null, null, $hash, 3);
    } catch (Exception $e) {
        $session->getFlashBag()->add('error', 'An error occurred while registering you to the system.
         Please try again later.\n'.$e);
        redirectToRegister($user_type);
    }
}

try {
    $user = findUserByEmail(request()->get('email'));
} catch (Exception $e) {
    $session->getFlashBag()->add('error', 'An error occurred while trying to log you in. 
    Please click on the login tab to manually login. '.$e);
    redirectToRegister($user_type);
}


// Generate an access_token JWT
$expTime = time() + 3600;
$jwt = \Firebase\JWT\JWT::encode([
    'iss' => request()->getBaseUrl(),
    'sub' => "{$user['id']}",
    'exp' => $expTime,
    'iat' => time(),
    'nbf' => time(),
    'is_admin' => $user['role_id'] == 1,
    'is_employer' => $user['role_id'] == 2

], getenv("SECRET_KEY"), 'HS256');


// Create JWT in cookie
$accessToken = new Cookie("access_token", $jwt, $expTime, '/', getenv('COOKIE_DOMAIN'));

if ($cv && $userId) {
    $dir = "../cvs/$userId/";
    $path = $dir . time();
    try {
        mkdir($dir, 0777, true);
        move_uploaded_file($tmp, $path);
        addCV($userId, $path);
    } catch (Exception $e) {
        $session->getFlashBag()->add('success', 'Successfully registered. Welcome to Student Recruiter.');
        $session->getFlashBag()->add('error', 'The CV was not correctly uploaded. Click <b>here</b> to try again. '.$e);
        redirect('../index.php', ['cookies' => [$accessToken]]);
    }
}

if ($logo && $companyId && $employerId) {
    $logo_dir = "../logos/$companyId/";
    $logo_path = $logo_dir . time();
    try {
        mkdir($logo_dir, 0777, true);
        move_uploaded_file($logo_tmp, $logo_path);
        addLogo($companyId, $logo_path);
    } catch (Exception $e) {
        $session->getFlashBag()->add('success', 'Successfully registered as Employer. Welcome to Student Recruiter.');
        $session->getFlashBag()->add('error', 'The Logo was not correctly uploaded. Click <b>here</b> to try again. '.$e);
        redirect('../index.php', ['cookies' => [$accessToken]]);
    }
}

// Send to index page
$session->getFlashBag()->add('success', 'Successfully registered. Welcome to Student Recruiter.');
redirect('../index.php', ['cookies' => [$accessToken]]);