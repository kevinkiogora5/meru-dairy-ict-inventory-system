<?php

use sigawa\mvccore\Application;
$user =Application::$app->user;
?>
<?php if (isset($user) && $user->session_token): ?>
  <meta name="session-token" content="<?= htmlspecialchars($user->session_token) ?>">
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Multipurpose, super flexible, powerful, clean modern responsive bootstrap 5 admin template"
        name="description">
    <meta
        content="admin template, axelit admin template, dashboard template, flat admin template, responsive admin template, web app"
        name="keywords">
    <meta content="la-themes" name="author">
    <link href="../assets/logo/logo2.png" rel="shortcut icon" type="image/x-icon">
    <title>Mount Kenya Milk Admin</title>

    <!-- Animation css -->
    <link href="/assets/vendor/animation/animate.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="../../../../css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- iconoir icon css  -->
    <link href="/assets/vendor/ionio-icon/css/iconoir.css" rel="stylesheet">

    <!-- wheather icon css-->
    <link href="/assets/vendor/weather/weather-icons.css" rel="stylesheet" type="text/css">
    <link href="/assets/vendor/weather/weather-icons-wind.css" rel="stylesheet" type="text/css">

    <!--flag Icon css-->
    <link href="/assets/vendor/flag-icons-master/flag-icon.css" rel="stylesheet" type="text/css">

    <!-- tabler icons-->
    <link href="/assets/vendor/tabler-icons/tabler-icons.css" rel="stylesheet" type="text/css">

    <!-- prism css-->
    <link href="/assets/vendor/prism/prism.min.css" rel="stylesheet" type="text/css">

    <!-- apexcharts css-->
    <link href="/assets/vendor/apexcharts/apexcharts.css" rel="stylesheet" type="text/css">

    <!-- slick css -->
    <link href="/assets/vendor/slick/slick.css" rel="stylesheet">
    <link href="/assets/vendor/slick/slick-theme.css" rel="stylesheet">

    <!-- Data Table css-->
    <link href="/assets/vendor/datatable/jquery.dataTables.min.css" rel="stylesheet" type="text/css">

    <!-- Bootstrap css-->
    <link href="/assets/vendor/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css">


    <!-- apexcharts css-->
    <link href="/assets/vendor/apexcharts/apexcharts.css" rel="stylesheet" type="text/css">

    <!-- simplebar css-->
    <link href="/assets/vendor/simplebar/simplebar.css" rel="stylesheet" type="text/css">

    <!-- App css-->
    <link href="/assets/css/style.css" rel="stylesheet" type="text/css">

    <!-- Responsive css-->
    <link href="/assets/css/responsive.css" rel="stylesheet" type="text/css">
    <!-- Toastify css-->

    <link href="/assets/vendor/toastify/toastify.css" rel="stylesheet" type="text/css">

    <!-- Grid.js CSS -->
    <link href="/scripts/gridjs/theme.css" rel="stylesheet" />


</head>

<body>
    <div class="app-wrapper">

        <!-- Menu Navigation starts -->
        <nav>
            <div class="app-logo">
                <a class="logo d-inline-block" href="index.html">
                    <img alt="kevo" src="../assets/logo/logo2.png">
                </a>

                <span class="bg-light-primary toggle-semi-nav">
                    <i class="ti ti-chevrons-right f-s-20"></i>
                </span>
            </div>
            <div class="app-nav" id="app-simple-bar">
                <ul class="main-nav p-0 mt-2">
                    <li class="menu-title">
                        <span>Admin Panel</span>
                    </li>
                    <li>
                        <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#dashboard">
                            <i class="iconoir-home-alt"></i>
                            Inventory_Assignment
                        </a>
                        <ul class="collapse" id="dashboard">
                            <li><a href="/assignment">Manage Inventory_Assignment</a></li>
                        </ul>
                    </li>
                    <li>
                        <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#maps">
                            <i class="iconoir-map"></i>
                            Inventory items
                        </a>
                        <ul class="collapse" id="maps">
                            <li><a href="/goods">Manage Inventory_Items</a></li>
                        </ul>
                    </li>
                    <li>
                        <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#apps">
                            <i class="iconoir-apple-shortcuts"></i>
                            Inventory Categories
                        </a>
                        <ul class="collapse" id="apps">
                            <li><a href="/categories">Manage Inventory_Categories</a></li>
                        </ul>
                    </li>
                    <li>
                        <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#ui-kits">

                            <i class="iconoir-handbag"></i>
                            Employees
                        </a>
                        <ul class="collapse" id="ui-kits">
                            <li><a href="/employees">Manage Employees</a></li>
                        </ul>
                    </li>


                    <li>
                        <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#advance-ui">
                            <i class="iconoir-shopping-bag-plus"></i> Departments
                        </a>
                        <ul class="collapse" id="advance-ui">
                            <li><a href="/department">Manage Departments</a></li>
                        </ul>
                    </li>
                    <li>
                        <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#advance-uis">
                            <i class="iconoir-archive"></i> Locations
                        </a>
                        <ul class="collapse" id="advance-uis">
                            <li><a href="/location">Manage Locations</a></li>
                        </ul>
                    </li>
                    <li>
                        <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#advance">
                            <i class="iconoir-archive"></i> Inventory Returns
                        </a>
                        <ul class="collapse" id="advance">
                            <li><a href="/returns">Manage Inventory Returns</a></li>
                        </ul>
                    </li>
                     <li>
                        <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#advances">
                            <i class="iconoir-archive"></i> Create User Account
                        </a>
                        <ul class="collapse" id="advances">
                            <li><a href="/signup">Add users</a></li>
                        </ul>
                    </li>
        </nav>
        <!-- Menu Navigation ends -->

        <div class="app-content">
            <div class="">

                <!-- Header Section starts -->
                <header class="header-main">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-6 col-sm-4 d-flex align-items-center header-left p-0">
                                <span class="header-toggle me-3">
                                    <i class="iconoir-view-grid"></i>
                                </span>
                            </div>

                            <div class="col-6 col-sm-8 d-flex align-items-center justify-content-end header-right p-0">

                                <ul class="d-flex align-items-center">
                                    <li class="header-language">
                                        <div class="flex-shrink-0 dropdown" id="lang_selector">
                                            <a aria-expanded="false" class="d-block head-icon ps-0"
                                                data-bs-toggle="dropdown" href="#">
                                                <div class="lang-flag lang-en ">
                                                    <span class="flag rounded-circle overflow-hidden">
                                                        <i class=""></i>
                                                    </span>
                                                </div>
                                            </a>
                                            <ul class="dropdown-menu language-dropdown header-card border-0">
                                                <li class="lang lang-en selected dropdown-item p-2"
                                                    data-bs-placement="top" data-bs-toggle="tooltip" title="US">
                                                    <span class="d-flex align-items-center">
                                                        <i
                                                            class="flag-icon flag-icon-usa flag-icon-squared b-r-10 f-s-22"></i>
                                                        <span class="ps-2">English</span>
                                                    </span>
                                                </li>
                                                <li class="lang lang-pt dropdown-item p-2" data-bs-placement="top"
                                                    data-bs-toggle="tooltip" title="FR">
                                                    <span class="d-flex align-items-center">
                                                        <i
                                                            class="flag-icon flag-icon-fra flag-icon-squared b-r-10 f-s-22"></i>
                                                        <span class="ps-2">Française </span>
                                                    </span>
                                                </li>
                                                <li class="lang lang-es dropdown-item p-2" data-bs-placement="top"
                                                    data-bs-toggle="tooltip" title="UK">
                                                    <span class="d-flex align-items-center">
                                                        <i
                                                            class="flag-icon flag-icon-gbr flag-icon-squared b-r-10 f-s-22"></i>
                                                        <span class="ps-2">English</span>
                                                    </span>
                                                </li>
                                                <li class="lang lang-es dropdown-item p-2" data-bs-placement="top"
                                                    data-bs-toggle="tooltip" title="Ru">
                                                    <span class="d-flex align-items-center">
                                                        <i
                                                            class="flag-icon flag-icon-rus flag-icon-squared b-r-10 f-s-22"></i>
                                                        <span class="ps-2">Русская</span>
                                                    </span>
                                                </li>
                                                <li class="lang lang-es dropdown-item p-2" data-bs-placement="top"
                                                    data-bs-toggle="tooltip" title="IT">
                                                    <span class="d-flex align-items-center">
                                                        <i
                                                            class="flag-icon flag-icon-ita flag-icon-squared b-r-10 f-s-22"></i>
                                                        <span class="ps-2">française</span>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>

                                    </li>
<!-- User Info -->
<li class="header-profile d-flex align-items-center gap-3">
    <div class="d-flex flex-column text-end">
        <?php if (isset($user)): ?>
            <h6 class="mb-0"><?= $user->getDisplayName() ?></h6>
            <p class="f-s-12 mb-1 text-secondary"><?= $user->email ?></p>
        <?php endif; ?>
    </div>

    <!-- Profile Dropdown -->
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" 
           id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?= $user->profile_image ?? 'assets/logo/download.jpg' ?>" 
                 alt="Profile" 
                 class="rounded-circle" 
                 style="width: 40px; height: 40px; object-fit: cover;">
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
            <li>
                      <a href="#" id="logoutBtn" class="btn btn-light-danger btn-sm justify-content-center">
  <i class="ph-duotone ph-sign-out pe-1 f-s-20"></i> Log Out
</a>
            </li>
        </ul>
    </div>
</li>


                                </ul>
                            </div>
                        </div>
                    </div>
                </header>
                {{content}}
                <!-- Header Section ends -->
            </div>
        </div>
        <!-- tap on top -->
        <div class="go-top">
            <span class="progress-value">
                <i class="ti ti-chevron-up"></i>
            </span>
        </div>

        <!-- Footer Section starts-->
        <footer class="bg-white py-4 w-full text-center border-t border-gray-200">
            <div class="container mx-auto px-4">
                <p class="text-gray-600 font-medium mb-0">
                    <a href="#" class="font-semibold text-indigo-700 hover:underline">&copy; 2025 <span
                            class="font-semibold text-indigo-600"></span>. All rights reserved </a>
                </p>
            </div>
        </footer>


        <!-- Footer Section ends-->
    </div>
     <!-- logout-->
    <script src="/scripts/openjs/openjs.js"></script>
<script src="/scripts/base/base.js"></script>
<script src="/scripts/logout/logout.js"></script>

    <!-- latest jquery-->
    <script src="/assets/js/jquery-3.6.3.min.js"></script>

    <!-- Bootstrap js-->
    <script src="/assets/vendor/bootstrap/bootstrap.bundle.min.js"></script>

    <!-- Simple bar js-->
    <script src="/assets/vendor/simplebar/simplebar.js"></script>

    <!-- phosphor js -->
    <script src="/assets/vendor/phosphor/phosphor.js"></script>

    <!-- slick-file -->
    <script src="/assets/vendor/slick/slick.min.js"></script>

    <!-- apexcharts-->
    <script src="/assets/vendor/apexcharts/apexcharts.min.js"></script>

    <!-- Customizer js-->
    <script src="/assets/js/customizer.js"></script>

    <!-- prism js-->
    <script src="/assets/vendor/prism/prism.min.js"></script>

    <!-- Ecommerce js-->
    <script src="/assets/js/ecommerce_dashboard.js"></script>

    <!-- App js-->
    <script src="/assets/js/script.js"></script>
    <!--toastify js-->
    <script src="/assets/vendor/toastify/toastify.js"></script>
    <!-- Grid.js JS -->
    <script src="/scripts/gridjs/gridjs.js"></script>


</body>

</html>