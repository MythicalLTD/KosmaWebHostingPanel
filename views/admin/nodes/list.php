<?php
include(__DIR__ . '/../../requirements/page.php');

$nodesPerPage = 20;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $nodesPerPage;

$searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';
$searchCondition = '';
if (!empty($searchKeyword)) {
    $searchCondition = " WHERE `name` LIKE '%$searchKeyword%' OR `host` LIKE '%$searchKeyword%'";
}
$user_query = "SELECT * FROM nodes" . $searchCondition . " ORDER BY `id` LIMIT $offset, $nodesPerPage";
$result = $conn->query($user_query);
$totalNodesQuery = "SELECT COUNT(*) AS total_nodes FROM nodes" . $searchCondition;
$totalResult = $conn->query($totalNodesQuery);
$totalNodes = $totalResult->fetch_assoc()['total_nodes'];
$totalPages = ceil($totalNodes / $nodesPerPage);
?>
<!DOCTYPE html>
<!--
    Author: Keenthemes
    Product Name: Metronic
    Product Version: 8.1.8
    Purchase: https://1.envato.market/EA4JP
    Website: http://www.keenthemes.com
    Contact: support@keenthemes.com
    Follow: www.twitter.com/keenthemes
    Dribbble: www.dribbble.com/keenthemes
    Like: www.facebook.com/keenthemes
    License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
    -->
<html lang="en">
    <head>
        <base href="/" />
        <title>
            <?= $settingsManager->getSetting('name') ?> - Dashboard
        </title>
        <?php include(__DIR__ . '/../../requirements/head.php'); ?>
        <link rel="icon" type="image/x-icon" href="<?= $logo ?>" />
    </head>
    <body id="kt_app_body" data-kt-app-header-fixed-mobile="true" data-kt-app-toolbar-enabled="true" class="app-default">
        <script>
            var defaultThemeMode = "light";
            var themeMode;
            if (document.documentElement) {
                if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                    themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
                } else {
                    if (localStorage.getItem("data-bs-theme") !== null) { themeMode = localStorage.getItem("data-bs-theme"); }
                    else { themeMode = defaultThemeMode; }
                }
                if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; }
                document.documentElement.setAttribute("data-bs-theme", themeMode);
            }
        </script>
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
        <?php include(__DIR__ . '/../../components/navbar.php') ?>
        <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
            <div id="kt_app_toolbar" class="app-toolbar pt-4 pt-lg-7 mb-n2 mb-lg-n3">
                <div id="kt_app_toolbar_container"
                    class="app-container container-xxl d-flex flex-stack flex-row-fluid">
                    <div class="d-flex flex-stack flex-row-fluid">
                        <div class="app-container container-xxl d-flex">
                            <?php include(__DIR__ . '/../components/sidebar.php'); ?>
                            <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                                <div class="d-flex flex-column flex-column-fluid">
                                    <div id="kt_app_content" class="app-content">
                                        
                                    </div>
                                </div>
                                <?php include(__DIR__ . '/../../components/footer.php') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include(__DIR__ . '/../../requirements/footer.php') ?>
    </body>
</html>