<?php

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../bootstrap.php';

//Common for both employer and user
$user_type = request()->get('reg');
$email = request()->get('email');
$password = request()->get('password');

if (!$email || !$password) {
    $session->getFlashBag()->add('error', 'Please Enter both username and password.');
    redirectToRegister($user_type);
}

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

// Check to see if user exists already with that email
try {
    $user = findUserByEmail(request()->get('email'));
} catch (Exception $e) {
    $session->getFlashBag()->add('error', 'An error occurred while Logging you into the system.
         Please try again later.\n' . $e);
    redirectToRegister($user_type);
}

if (empty($user)) {
    $session->getFlashBag()->add('error', 'Username or Password is incorrect!');
    redirectToRegister($user_type);
}

if (!password_verify(request()->get('password'), $user['password'])) {
    $session->getFlashBag()->add('error', 'Username or Password is incorrect!');
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

// Send to index page
redirect('../index.php', ['cookies' => [$accessToken]]);
