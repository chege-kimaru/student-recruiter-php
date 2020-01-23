<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/_partials/head.php';
require_once __DIR__ . '/_partials/nav.php';
?>
    <!-- Titlebar
    ================================================== -->
    <div id="titlebar" class="single">
        <div class="container">

            <div class="sixteen columns">
                <h2>My Account</h2>
                <nav id="breadcrumbs">
                    <ul>
                        <li>You are here:</li>
                        <li><a href="#">Home</a></li>
                        <li>My Account</li>
                    </ul>
                </nav>
            </div>

        </div>
    </div>

    <!-- Content
    ================================================== -->

    <!-- Container -->
    <div class="container">

        <div class="my-account">

            <?php print display_errors(); ?>
            <?php print display_success(); ?>

            <ul class="tabs-nav">
                <li class=""><a href="#tab1">Login</a></li>
                <li><a href="#tab2">Register</a></li>
            </ul>

            <div class="tabs-container">
                <!-- Login -->
                <div class="tab-content" id="tab1" style="display: none;">
                    <form method="post" class="login" method="post" action="procedures/doLogin.php">

                        <input type="hidden" value="user" name="reg">

                        <p class="form-row form-row-wide">
                            <label for="username">Email:
                                <i class="ln ln-icon-Male"></i>
                                <input type="text" class="input-text" name="email" id="username" value=""/>
                            </label>
                        </p>

                        <p class="form-row form-row-wide">
                            <label for="password">Password:
                                <i class="ln ln-icon-Lock-2"></i>
                                <input class="input-text" type="password" name="password" id="password"/>
                            </label>
                        </p>

                        <!--                        <p class="form-row">-->
                        <!--                            <input type="submit" class="button border fw margin-top-10" name="login" value="Login"/>-->
                        <!---->
                        <!--                            <label for="rememberme" class="rememberme">-->
                        <!--                                <input name="rememberme" type="checkbox" id="rememberme" value="forever"/> Remember-->
                        <!--                                Me</label>-->
                        <!--                        </p>-->
                        <!---->
                        <!--                        <p class="lost_password">-->
                        <!--                            <a href="#">Lost Your Password?</a>-->
                        <!--                        </p>-->
                        <p class="form-row">
                            <input type="submit" class="button border fw margin-top-10" name="login"
                                   value="Login"/>
                        </p>

                    </form>
                </div>

                <!-- Register -->
                <div class="tab-content" id="tab2" style="display: none;">

                    <form enctype="multipart/form-data" method="post" class="register"
                          action="procedures/doRegister.php">

                        <input type="hidden" value="employer" name="reg">

                        <!-- Company Details -->
                        <div class="divider"><h3>Company Details</h3></div>

                        <!-- Company Name -->
                        <div class="form">
                            <h5>Company Name</h5>
                            <input type="text" placeholder="Enter the name of the company" name="name">
                        </div>

                        <div class="form">
                            <h5>Location</h5>
                            <input type="text" placeholder="Enter the name of the company" name="location">
                        </div>

                        <!-- Website -->
                        <div class="form">
                            <h5>Website <span>(optional)</span></h5>
                            <input type="text" placeholder="http://" name="website">
                        </div>

                        <!-- Teagline -->
                        <div class="form">
                            <h5>Tagline <span>(optional)</span></h5>
                            <input type="text" placeholder="Briefly describe your company" name="description">
                        </div>

                        <!-- Video -->
                        <div class="form">
                            <h5>Video <span>(optional)</span></h5>
                            <input type="text" placeholder="A link to a video about your company" name="video_link">
                        </div>

                        <!-- Twitter -->
                        <div class="form">
                            <h5>Twitter Username <span>(optional)</span></h5>
                            <input type="text" placeholder="@yourcompany" name="twitter_username">
                        </div>

                        <!-- Logo -->
                        <p class="form-row form-row-wide">
                            <label for="logo">Logo:
                                <input type="file" name="logo" id="logo"/>
                            </label>
                        </p>

                        <hr>

                        <p class="form-row form-row-wide">
                            <label for="email2">Email:
                                <i class="ln ln-icon-Mail"></i>
                                <input type="text" class="input-text" name="email" id="email2" value="" required/>
                            </label>
                        </p>

                        <p class="form-row form-row-wide">
                            <label for="email2">Phone Number:
                                <i class="ln ln-icon-Phone"></i>
                                <input type="text" class="input-text" name="phone" id="email2" value="" required/>
                            </label>
                        </p>


                        <p class="form-row form-row-wide">
                            <label for="password1">Password:
                                <i class="ln ln-icon-Lock-2"></i>
                                <input class="input-text" type="password" name="password" id="password1" required/>
                            </label>
                        </p>

                        <p class="form-row form-row-wide">
                            <label for="password2">Confirm Password:
                                <i class="ln ln-icon-Lock-2"></i>
                                <input class="input-text" type="password" name="confirm_password" id="password2"
                                       required/>
                            </label>
                        </p>

                        <p class="form-row">
                            <input type="submit" class="button border fw margin-top-10" name="register"
                                   value="Register"/>
                        </p>

                    </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/_partials/footer.php'; ?>