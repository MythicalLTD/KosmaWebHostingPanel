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

<html lang="en" class="dark-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-semi-dark"
    data-assets-path="/assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <?php include(__DIR__ . '/../../requirements/head.php'); ?>
    <title>
        <?= $settingsManager->getSetting('name') ?> - Dashboard
    </title>
    <link rel="stylesheet" href="<?= $appURL ?>/assets/vendor/css/pages/page-help-center.css" />
</head>

<body>
    <div id="preloader" class="discord-preloader">
        <div class="spinner"></div>
    </div>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include(__DIR__ . '/../../components/sidebar.php') ?>
            <div class="layout-page">
                <?php include(__DIR__ . '/../../components/navbar.php') ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Admin /</span> Nodes</h4>
                        <?php include(__DIR__ . '/../../components/alert.php') ?>
                        <!-- Search Form -->
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
                    <?php include(__DIR__ . '/../../components/footer.php') ?>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>
    </div>
    <?php include(__DIR__ . '/../../requirements/footer.php') ?>
    <script src="<?= $appURL ?>/assets/js/dashboards-ecommerce.js"></script>
</body>

</html>