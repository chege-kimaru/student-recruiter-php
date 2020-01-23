<!-- Header
================================================== -->
<header class="transparent sticky-header">
    <div class="container">
        <div class="sixteen columns">

            <!-- Logo -->
            <div id="logo">
                <h1><a href="index-2.html"><img src="images/logo2.png" alt="Work Scout"/></a></h1>
            </div>

            <!-- Menu -->
            <nav id="navigation" class="menu">
                <ul id="responsive">

                    <li><a href="index.php" id="current">Home</a>
                        <!-- <ul>
                            <li><a href="index-2.html">Home #1</a></li>
                            <li><a href="index-3.html">Home #2</a></li>
                            <li><a href="index-4.html">Home #3</a></li>
                            <li><a href="index-5.html">Home #4</a></li>
                            <li><a href="index-6.html">Home #5</a></li>
                        </ul> -->
                    </li>

                    <!-- <li><a href="#">Pages</a>
                        <ul>
                            <li><a href="job-page.html">Job Page</a></li>
                            <li><a href="job-page-alt.html">Job Page Alternative</a></li>
                            <li><a href="resume-page.html">Resume Page</a></li>
                            <li><a href="shortcodes.html">Shortcodes</a></li>
                            <li><a href="icons.html">Icons</a></li>
                            <li><a href="pricing-tables.html">Pricing Tables</a></li>
                            <li><a href="contact.html">Contact</a></li>
                        </ul>
                    </li> -->

                    <li><a href="#">For Candidates</a>
                        <ul>
                            <li><a href="browse-jobs.php">Browse Jobs</a></li>
                            <li><a href="browse-categories.php">Browse Categories</a></li>
                            <li><a href="add-resume.php">Add Resume</a></li>
                            <li><a href="manage-resumes.php">Manage Resumes</a></li>
                            <li><a href="job-alerts.php">Job Alerts</a></li>
                        </ul>
                    </li>

                    <li><a href="#">For Employers</a>
                        <ul>
                            <li><a href="add-job.php">Add Job</a></li>
                            <li><a href="add-job-category.php">Add Job Category</a></li>
                            <li><a href="manage-jobs.php">Manage Jobs</a></li>
                            <li><a href="manage-applications.php">Manage Applications</a></li>
                            <li><a href="browse-resumes.php">Browse Resumes</a></li>
                        </ul>
                    </li>

                    <li><a href="blog.php">Blog</a></li>
                </ul>


                <ul class="float-right">
                    <?php if (isAuthenticated()): ?>
                        <li><a href="#"><i class="fa fa-user"></i> Account |
                                <?php
                                if(isAdmin()) {
                                    echo "Admin | ".user("first_name");
                                }
                                else if(isEmployer()) {
                                    echo "Employer | ".user("email");
                                }
                                else {
                                    echo " ".user("first_name");
                                }?>
                            </a>
                        </li>
                        <li><a href="logout.php"><i class="fa fa-lock"></i> Log Out</a></li>
                    <?php else: ?>
                        <li><a href="#"><i class="fa fa-user"></i> Sign up</a>
                            <ul>
                                <li><a href="register.php#tab2">As Candidate</a></li>
                                <li><a href="register_company.php#tab2">As Company</a></li>
                            </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-lock"></i> Login</a>
                            <ul>
                                <li><a href="register.php#tab1">As Candidate</a></li>
                                <li><a href="register_company.php#tab1">As Company</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>

            </nav>

            <!-- Navigation -->
            <div id="mobile-navigation">
                <a href="#menu" class="menu-trigger"><i class="fa fa-reorder"></i> Menu</a>
            </div>

        </div>
    </div>
</header>
<div class="clearfix"></div>