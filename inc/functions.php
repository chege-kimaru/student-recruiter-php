<?php

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


/**
 * @return \Symfony\Component\HttpFoundation\Request
 */
function request()
{
    return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
}

function redirect($path, $extra = [])
{
    $response = Response::create(null, Response::HTTP_FOUND, ['Location' => $path]);

    if (key_exists('cookies', $extra)) {
        foreach ($extra['cookies'] as $cookie) {
            $response->headers->setCookie($cookie);
        }
    }

    $response->send();
    exit;
}

function display_alerts($level, $messages = [])
{
    $response = '<div class="alert alert-' . $level . ' alert-dismissable">';
    foreach ($messages as $message) {
        $response .= "{$message}<br>";
    }
    $response .= '</div>';

    return $response;
}

function display_errors($bag = 'error')
{
    global $session;

    if (!$session->getFlashBag()->has($bag)) {
        return;
    }

    $messages = $session->getFlashBag()->get($bag);

    return display_alerts('danger', $messages);
}

function display_success($bag = 'success')
{
    global $session;

    if (!$session->getFlashBag()->has($bag)) {
        return;
    }

    $messages = $session->getFlashBag()->get($bag);

    return display_alerts('success', $messages);
}

function addBook($title, $description)
{
    global $db;
    $id = accessToken('sub');
    try {
        $stmt = $db->prepare("INSERT INTO books (name, description, owner_id) VALUES (:name, :description, :id)");
        $stmt->bindParam(':name', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    } catch (\Exception $e) {
        throw $e;
    }
}

function updateBook($id, $title, $description)
{
    global $db;
    try {
        $stmt = $db->prepare("UPDATE books SET name=:name, description=:description WHERE id = :id");
        $stmt->bindParam(':name', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    } catch (\Exception $e) {
        throw $e;
    }
}

function getBook($id)
{
    global $db;

    try {
        $stmt = $db->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
        throw $e;
    }
}

function deleteBook($id)
{
    global $db;

    try {
        $stmt = $db->prepare("DELETE from books where id = ? ");
        $stmt->execute([$id]);
        return true;
    } catch (\Exception $e) {
        return false;
    }
}

function getAllBooks()
{
    global $db;

    $userId = 0;

    if (isAuthenticated()) {
        $userId = accessToken('sub');
    }
    $query = "SELECT books.*, sum(votes.value) as score, (SELECT value FROM votes WHERE votes.book_id=books.id AND votes.user_id={$userId}) as myVote FROM books LEFT JOIN votes ON (books.id = votes.book_id) GROUP BY books.id ORDER BY score DESC";
    try {
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (\Exception $e) {
        throw $e;
    }
}

function getMyBooks()
{
    global $db;

    $userId = accessToken('sub');

    $query = "SELECT books.*, sum(votes.value) as score, (SELECT value FROM votes WHERE votes.book_id=books.id AND votes.user_id={$userId}) as myVote FROM books LEFT JOIN votes ON (books.id = votes.book_id) WHERE books.owner_id = ? GROUP BY books.id ORDER BY score DESC";

    try {
        $stmt = $db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    } catch (\Exception $e) {
        throw $e;
    }
}

function findUserByEmail($email)
{
    global $db;

    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
        throw $e;
    }
}

function findUserById($id)
{
    global $db;

    try {
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
        throw $e;
    }
}

function createUser($firstName, $lastName, $email, $phone, $educationLevel, $cvPath, $companyId, $password, $role_id = 3)
{
    global $db;

    try {
        $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, phone, education_level, cv_path,
          company_id, password, role_id) VALUES (:first_name, :last_name,:email, :phone, :education_level, :cv_path,
           :company_id, :password, :role_id)");
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':education_level', $educationLevel);
        $stmt->bindParam(':cv_path', $cvPath);
        $stmt->bindParam(':company_id', $companyId);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->execute();
        return $db->lastInsertId();
    } catch (\Exception $e) {
        throw $e;
    }
}

function createCompany($name, $website, $video_link, $twitter_username, $logo_path, $location, $description)
{
    global $db;

    try {
        $stmt = $db->prepare("INSERT INTO companies (name, website, video_link, twitter_username, logo_path,
          location, description) VALUES (:name, :website,:video_link, :twitter_username, :logo_path, :location,
           :description)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':website', $website);
        $stmt->bindParam(':video_link', $video_link);
        $stmt->bindParam(':twitter_username', $twitter_username);
        $stmt->bindParam(':logo_path', $logo_path);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        return $db->lastInsertId();
    } catch (\Exception $e) {
        throw $e;
    }
}

function addCV($userId, $cvPath)
{
    global $db;

    try {
        $stmt = $db->prepare("UPDATE users SET cv_path = :cv_path WHERE id = :user_id");
        $stmt->bindParam(':cv_path', $cvPath);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
    } catch (\Exception $e) {
        throw $e;
    }
}

function addLogo($companyId, $logoPath)
{
    global $db;

    try {
        $stmt = $db->prepare("UPDATE companies SET logo_path = :logo_path WHERE id = :company_id");
        $stmt->bindParam(':logo_path', $logo_path);
        $stmt->bindParam(':company_id', $company_id);
        $stmt->execute();
    } catch (\Exception $e) {
        throw $e;
    }
}

function isAuthenticated()
{
    if (!request()->cookies->has('access_token')) {
        return false;
    }

    try {
        \Firebase\JWT\JWT::$leeway = 1;
        $jwt = \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
        );

        return true;
    } catch (\Exception $e) {
        return false;
    }

}

function requireAuth()
{
    if (!isAuthenticated()) {
        $accessToken = new Cookie("access_token", 'EXPIRED', time() - 3600, '/', getenv('COOKIE_DOMAIN'));
        redirect('register.php#tab1', ['cookies' => [$accessToken]]);
    }

    try {
        \Firebase\JWT\JWT::$leeway = 1;
        \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
        );
    } catch (\Exception $e) {
        $accessToken = new Cookie("access_token", 'EXPIRED', time() - 3600, '/', getenv('COOKIE_DOMAIN'));
        redirect('register.php#tab1', ['cookies' => [$accessToken]]);
    }

}

function isAdmin()
{
    if (!isAuthenticated()) {
        return false;
    }

    try {
        \Firebase\JWT\JWT::$leeway = 1;
        $jwt = \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
        );
    } catch (\Exception $e) {
        return false;
    }

    return $jwt->is_admin;
}

function isEmployer()
{
    if (!isAuthenticated()) {
        return false;
    }

    try {
        \Firebase\JWT\JWT::$leeway = 1;
        $jwt = \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
        );
    } catch (\Exception $e) {
        return false;
    }

    return $jwt->is_employer;
}


function requireAdmin()
{
    if (!isAuthenticated()) {
        $accessToken = new Cookie("access_token", 'EXPIRED', time() - 3600, '/', getenv('COOKIE_DOMAIN'));
        redirect('register.php#tab1', ['cookies' => [$accessToken]]);
    }


    try {
        \Firebase\JWT\JWT::$leeway = 1;
        $jwt = \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
        );
    } catch (\Exception $e) {
        $accessToken = new Cookie("access_token", 'EXPIRED', time() - 3600, '/', getenv('COOKIE_DOMAIN'));
        redirect('register.php#tab1', ['cookies' => [$accessToken]]);
        exit;
    }

    if (!$jwt->is_admin) {
        redirect('unauthorized.php');
    }
}

function requireEmployer()
{
    if (!isAuthenticated()) {
        $accessToken = new Cookie("access_token", 'EXPIRED', time() - 3600, '/', getenv('COOKIE_DOMAIN'));
        redirect('register_company.php#tab1', ['cookies' => [$accessToken]]);
    }


    try {
        \Firebase\JWT\JWT::$leeway = 1;
        $jwt = \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
        );
    } catch (\Exception $e) {
        $accessToken = new Cookie("access_token", 'EXPIRED', time() - 3600, '/', getenv('COOKIE_DOMAIN'));
        redirect('register_company.php#tab1', ['cookies' => [$accessToken]]);
        exit;
    }

    if (!($jwt->is_employer)) {
        redirect('unauthorized.php');
    }
}

function user($item = null)
{
    if (!isAuthenticated()) {
        return false;
    }
    try {
        \Firebase\JWT\JWT::$leeway = 1;
        $jwt = \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
        );
    } catch (\Exception $e) {
        $accessToken = new Cookie("access_token", 'EXPIRED', time() - 3600, '/', getenv('COOKIE_DOMAIN'));
        redirect('login.php', ['cookies' => [$accessToken]]);
        exit;
    }

    $user = findUserById($jwt->sub);

    if (!$user) {
        return false;
    }

    if ($item) {
        return $user[$item];
    }

    return $user;
}

function accessToken($item = null)
{
    if (!isAuthenticated()) {
        return false;
    }
    try {
        \Firebase\JWT\JWT::$leeway = 1;
        $jwt = \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
        );
    } catch (\Exception $e) {
        return false;
    }

    if ($item) {
        return $jwt->{$item};
    }

    return $jwt;
}

function createJobCategory($name, $description)
{
    global $db;

    try {
        $stmt = $db->prepare("INSERT INTO job_categories (name, description) VALUES (:name, :description)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        return $db->lastInsertId();
    } catch (\Exception $e) {
        throw $e;
    }
}

function createJob($title, $job_category_id, $company_id, $level, $type, $deadline, $salary, $description,
                   $skills, $optional_skills, $location, $responsibilities, $application_url, $job_tags)
{
    global $db;
    if(!$salary) $salary = 0;

    try {
        $stmt = $db->prepare("INSERT INTO jobs (title, job_category_id, company_id, level, type,
 deadline, salary, description, skills, optional_skills, location, responsibilities, application_url,
  job_tags) VALUES (:title, :job_category_id, :company_id, :level, :type,
 :deadline, :salary, :description, :skills, :optional_skills, :location, :responsibilities, :application_url,
  :job_tags)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':job_category_id', $job_category_id);
        $stmt->bindParam(':company_id', $company_id);
        $stmt->bindParam(':level', $level);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':deadline', $deadline);
        $stmt->bindParam(':salary', $salary);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':skills', $skills);
        $stmt->bindParam(':optional_skills', $optional_skills);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':responsibilities', $responsibilities);
        $stmt->bindParam(':application_url', $application_url);
        $stmt->bindParam(':job_tags', $job_tags);
        $stmt->execute();
        return $db->lastInsertId();
    } catch (\Exception $e) {
        throw $e;
    }
}

function getAllCategories()
{
    global $db;

    try {
        $stmt = $db->prepare("SELECT * FROM job_categories");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
        throw $e;
    }
}

function getAllJobs()
{
    global $db;

    try {
        $stmt = $db->prepare("SELECT jobs.*, c.*, u.*, cat.name AS category FROM jobs 
LEFT JOIN companies AS c ON jobs.company_id = c.id
LEFT JOIN job_categories AS cat ON jobs.job_category_id = cat.id
LEFT JOIN users AS u ON u.company_id = c.id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
        throw $e;
    }
}

function getEmployerJobs($employerId)
{
    global $db;

    try {
        $stmt = $db->prepare("SELECT jobs.*, c.name AS company, u.*, cat.name FROM jobs 
LEFT JOIN companies AS c ON jobs.company_id = c.id
LEFT JOIN job_categories AS cat ON jobs.job_category_id = cat.id
LEFT JOIN users AS u ON u.company_id = c.id
WHERE u.id = ?");
        $stmt->execute([$employerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
        throw $e;
    }
}

function getCompanyByEmployerId($employerId) {
    global $db;

    try {
        $stmt = $db->prepare("SELECT companies.* FROM companies
LEFT JOIN users AS u ON u.company_id = companies.id
WHERE u.id = ?");
        $stmt->execute([$employerId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
        throw $e;
    }
}

function getJob($jobId)
{
    global $db;

    try {
        $stmt = $db->prepare("SELECT jobs.*, c.name AS company, u.*, cat.name AS category FROM jobs 
LEFT JOIN companies AS c ON jobs.company_id = c.id
LEFT JOIN job_categories AS cat ON jobs.job_category_id = cat.id
LEFT JOIN users AS u ON u.company_id = c.id
WHERE jobs.id = ?");
        $stmt->execute([$jobId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
        throw $e;
    }
}

function updatePassword($password)
{
    global $db;

    try {
        $stmt = $db->prepare('UPDATE users SET password=:password WHERE id = :userId');
        $stmt->execute([":password" => $password, ":userId" => accessToken('sub')]);
    } catch (\Exception $e) {
        return false;
    }

    return true;
}

function getAllUsers()
{
    global $db;

    try {
        $stmt = $db->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
        throw $e;
    }
}
