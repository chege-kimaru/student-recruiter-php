<?php

require __DIR__ . '/bootstrap.php';

$db->beginTransaction();

try {
    $createCompany = $db->exec("CREATE TABLE companies (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    website VARCHAR(255),
    video_link VARCHAR(255),
    twitter_username VARCHAR(255),
    logo_path VARCHAR(255),
    location VARCHAR(255),
    description VARCHAR(255),
    created_at datetime DEFAULT CURRENT_TIMESTAMP
    )");

    //Wiil be used for admins(role_id = 1), employers (role_id = 2), users (role_id=3)
    $createUsers = $db->exec("CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    company_id INT(11),
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    phone VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    education_level VARCHAR(255),
    cv_path VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    role_id int(10) UNSIGNED,
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id)
    )");

    $createJobCategory = $db->exec("CREATE TABLE job_categories (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    created_at datetime DEFAULT CURRENT_TIMESTAMP
    )");

    $createJob = $db->exec("CREATE TABLE jobs (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    job_category_id INT(11) NOT NULL,
    company_id INT(11) NOT NULL,
    level VARCHAR(50),
    type VARCHAR(50),
    deadline datetime NOT NULL,
    salary INT(11),
    description VARCHAR(2000) NOT NULL,
    skills VARCHAR(2000),
    optional_skills VARCHAR(2000),
    location VARCHAR(255),
    responsibilities VARCHAR(2000),
    application_url VARCHAR(255),
    job_tags VARCHAR(255),
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_category_id) REFERENCES job_categories(id),
    FOREIGN KEY (company_id) REFERENCES companies(id)
    )");

    $db->commit();
    redirect('register.php');

} catch (\Exception $e) {
    $db->rollBack();

    if($e->getCode() == '42S01') {
        redirect('index.php');
    }

    dump($e);
}