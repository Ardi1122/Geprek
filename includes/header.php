<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geprek Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo isset($path_to_root) ? $path_to_root : '..'; ?>/assets/css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            background-color: #ffffff;
            width: 260px;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            border-right: 1px solid #e0e0e0;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #FFCC00;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-brand i {
            font-size: 1.75rem;
        }

        .sidebar-menu {
            padding: 1.5rem 0;
        }

        .nav-pills .nav-link {
            color: #6c757d;
            border-radius: 8px;
            margin: 0.25rem 1rem;
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }

        .nav-pills .nav-link:hover {
            background-color: #fff8e1;
            color: #FFCC00;
        }

        .nav-pills .nav-link.active {
            background-color: #FFCC00;
            color: #ffffff;
        }

        .nav-pills .nav-link i {
            font-size: 1.1rem;
            width: 24px;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Navbar */
        .top-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            padding: 1rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .user-dropdown .dropdown-toggle {
            background-color: transparent;
            border: 1px solid #e0e0e0;
            border-radius: 25px;
            padding: 0.5rem 1rem;
            color: #495057;
            font-weight: 500;
        }

        .user-dropdown .dropdown-toggle:hover {
            background-color: #f8f9fa;
            border-color: #FFCC00;
        }

        .user-dropdown .dropdown-menu {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* Dashboard Cards */
        .dashboard-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-2px);
            border-color: #FFCC00;
        }

        .card-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .card-primary .card-icon {
            background-color: #fff8e1;
            color: #FFCC00;
        }

        .card-success .card-icon {
            background-color: #e8f5e9;
            color: #4caf50;
        }

        .card-warning .card-icon {
            background-color: #fff3e0;
            color: #ff9800;
        }

        .card-title-text {
            font-size: 0.875rem;
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .card-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #212529;
        }

        /* Chart Container */
        .chart-container {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .chart-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #212529;
            margin-bottom: 1.5rem;
        }

        /* Mobile Sidebar */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: transparent;
            border: none;
            font-size: 1.5rem;
            color: #495057;
            cursor: pointer;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .sidebar {
                left: -260px;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .top-navbar {
                padding: 1rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-card {
                margin-bottom: 1rem;
            }

            .card-value {
                font-size: 1.5rem;
            }

            .chart-container {
                padding: 1rem;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 100%;
                left: -100%;
            }

            .sidebar.show {
                left: 0;
            }

            .card-icon {
                width: 48px;
                height: 48px;
                font-size: 1.25rem;
            }

            .card-value {
                font-size: 1.25rem;
            }

            .chart-title {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>