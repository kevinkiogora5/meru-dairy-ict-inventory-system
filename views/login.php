<body class="sign-in-bg">
    <div class="app-wrapper d-block">
        <div class="main-container">
            <!-- Body main section starts -->
            <div class="container">
                <div class="row sign-in-content-bg">
                    <!-- Left Image Column -->
                    <div class="col-lg-6 image-contentbox d-none d-lg-block">
                        <div class="form-container">
                            <div class="signup-content mt-4 text-center">
                                <span>
                                    <img alt="Mount Kenya Milk Logo" class="img-fluid" src="../assets/logo/logo.png">
                                </span>
                            </div>

                            <div class="signup-bg-img text-center">
                                <img alt="Sign In Illustration" class="img-fluid" src="../assets/images/login/01.png">
                            </div>
                        </div>
                    </div>

                    <!-- Right Form Column -->
                    <div class="col-lg-6 form-contentbox d-flex align-items-center">
                        <div class="form-container w-100">
                            <form class="app-form rounded-control" id="user">
                                <div class="text-center text-lg-start mb-5">
                                    <h2 class="text-primary-dark fw-bold">Welcome To Mount Kenya Milk</h2>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input class="form-control" name="email" id="email" placeholder="Enter Your Email" type="email" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input class="form-control" name="password" id="password" placeholder="Enter Your Password" type="password" required>
                                </div>

                                <div class="mb-3">
                                    <button class="btn btn-light-primary w-100" type="submit" id="send">Sign In</button>
                                </div>
                                <div class="text-center">
                                   <a href="/forgotpassword" class="text-muted">Forgot Password?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Body main section ends -->
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/vendor/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="/scripts/base/base.js"></script>
    <script src="/scripts/login/login.js"></script>
</body>