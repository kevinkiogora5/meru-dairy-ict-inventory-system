<body class="sign-in-bg">
    <div class="app-wrapper d-block">
        <div class="main-container">
            <!-- Sign up start -->
            <div class="container">
                <div class="row sign-in-content-bg">
                    <!-- Left Image Panel -->
                    <div class="col-lg-6 image-contentbox d-none d-lg-block">
                        <div class="form-container">
                            <div class="signup-content mt-4 text-center">
                                <span>
                                    <img alt="Mount Kenya Milk Logo" class="img-fluid"
                                        src="../assets/logo/logo.png">
                                </span>
                            </div>

                            <div class="signup-bg-img text-center">
                                <img alt="Signup Illustration" class="img-fluid" src="../assets/images/login/02.png">
                            </div>
                        </div>
                    </div>

                    <!-- Right Form Panel -->
                    <div class="col-lg-6 form-contentbox d-flex align-items-center">
                        <div class="form-container w-100">
                            <form class="app-form rounded-control" id="signup">
                                <div class="text-center text-lg-start mb-4">
                                    <h2 class="text-primary-dark fw-bold">Create Account</h2>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="firstname">Firstname</label>
                                    <input class="form-control" name="first_name" id="first_name"
                                        placeholder="Enter Your Firstname" required type="text">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="lastname">Lastname</label>
                                    <input class="form-control" name="last_name" id="last_name"
                                        placeholder="Enter Your Lastname" required type="text">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input class="form-control" name="email" id="email" placeholder="Enter Your Email"
                                        required type="email">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="phone">Phone</label>
                                    <input class="form-control" name="phone" id="phone" placeholder="Enter Your Phone"
                                        required type="text">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="password">Password</label>
                                        <input class="form-control" name="password" id="password"
                                            placeholder="Enter Your Password" required type="password">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="rpassword">Confirm Password</label>
                                        <input class="form-control" name="rpassword" id="rpassword"
                                            placeholder="Confirm Your Password" required type="password">
                                    </div>
                                </div>

                                <p class="text-secondary small">Password must be at least 6 characters long</p>

                                <div class="form-check mb-4">
                                    <input class="form-check-input" id="checkDefault" type="checkbox" required>
                                    <label class="form-check-label text-secondary" for="checkDefault">
                                        Accept Terms & Conditions
                                    </label>
                                </div>

                                <div class="mb-3">
                                    <button class="btn btn-light-primary w-100" type="submit" id="ingia">Sign
                                        Up</button>
                                </div>

                                <div class="text-center text-lg-start">
                                    Already Have An Account?
                                    <a class="link-primary-dark text-decoration-underline" href="/login">Sign In</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sign up end -->
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="/scripts/base/base.js"></script>
    <script src="/scripts/signup/signup.js"></script>
</body>