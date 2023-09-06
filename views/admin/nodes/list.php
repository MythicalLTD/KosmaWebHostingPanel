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
                <div class="app-container container-xxl d-flex">
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid">
                            <div id="kt_app_content" class="app-content">
                                <div class="row g-5 g-xxl-10">
                                <form class="mt-4">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Search nodes..." name="search"
                                    value="<?= $searchKeyword ?>">
                                <button class="btn btn-outline-secondary" type="submit">Search</button>
                            </div>
                        </form>
                        <div class="card">
                            <h5 class="card-header">
                                Nodes
                                <button type="button" data-bs-toggle="modal" data-bs-target="#createNode"
                                    class="btn btn-primary float-end">Add new node</button>
                            </h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Created</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td><a href='/admin/nodes/info?id=" . $row['id'] . "'>" . $row['name'] . "</a></td>";
                                                echo "<td>" . $row['description'] . "</td>";
                                                echo "<td>" . $row['created-date'] . "</td>";
                                                echo "<td><a href=\"/admin/nodes/edit?id=" . $row['id'] . "\" class=\"btn btn-primary\">Edit</a>&nbsp;<a href=\"/admin/nodes/delete?id=" . $row['id'] . "\" class=\"btn btn-danger\">Delete</a></td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><br<center><td class='text-center'colspan='5'><br>No nodes found.<br><br>&nbsp;</td></center></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                                </div>
                            </div>
                        </div>
                        <?php include(__DIR__ . '/../../components/footer.php') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include(__DIR__ . '/../../requirements/footer.php') ?>
</body>

</html>