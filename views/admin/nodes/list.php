<?php
include(__DIR__ . '/../../requirements/page.php');
include(__DIR__ . '/../requirements/admin.php');
use Kosma\Nodes\NodeConnection;
$checker = new NodeConnection();


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
<html lang="en">

<head>
    <base href="/" />
    <title>
        <?= $settingsManager->getSetting('name') ?> | Nodes
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
                                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                                <?php include(__DIR__ . '/../../components/alert.php') ?>
                                    <div class="d-flex flex-column flex-column-fluid">
                                        <div id="kt_app_content" class="app-content">
                                            <div class="card">
                                                <div class="card-header border-0 pt-6">
                                                    <div class="card-title">
                                                        <div class="d-flex align-items-center position-relative my-1">
                                                            <i
                                                                class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                                                <form method="GET">
                                                                
                                                            <input type="text" data-kt-user-table-filter="search"
                                                                class="form-control form-control-solid w-250px ps-13"
                                                                placeholder="Search for nodes" name="search" value="<?= $searchKeyword ?>"></form>
                                                        </div>
                                                    </div>
                                                    <div class="card-toolbar">
                                                        <div class="d-flex justify-content-end"
                                                            data-kt-user-table-toolbar="base">
                                                            <a href="/admin/nodes/create" class="btn btn-primary"><i class="ki-outline ki-plus fs-2"></i>Add Node</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body py-4">
                                                    <div id="kt_table_users_wrapper"
                                                        class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                        <div class="table-responsive">
                                                            <table
                                                                class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                                                                id="userTable">
                                                                <thead>
                                                                    <tr
                                                                        class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                                        <th class="min-w-125px sorting" tabindex="0"
                                                                            aria-controls="kt_table_users" rowspan="1"
                                                                            colspan="1"
                                                                            aria-label="Node: activate to sort column ascending"
                                                                            style="width: 215.016px;">Node</th>
                                                                        <th class="min-w-125px sorting" tabindex="0"
                                                                            aria-controls="kt_table_users" rowspan="1"
                                                                            colspan="1"
                                                                            aria-label="Description: activate to sort column ascending"
                                                                            style="width: 125px;">Description</th>
                                                                        <th class="min-w-125px sorting" tabindex="0"
                                                                            aria-controls="kt_table_users" rowspan="1"
                                                                            colspan="1"
                                                                            aria-label="Host: activate to sort column ascending"
                                                                            style="width: 125px;">Host</th>
                                                                        <th class="min-w-125px sorting" tabindex="0"
                                                                            aria-controls="kt_table_users" rowspan="1"
                                                                            colspan="1"
                                                                            aria-label="Created Date: activate to sort column ascending"
                                                                            style="width: 159.688px;">Created Date</th>
                                                                        <th class="text-end min-w-100px sorting_disabled"
                                                                            rowspan="1" colspan="1" aria-label="Actions"
                                                                            style="width: 101.906px;">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="text-gray-600 fw-semibold">
                                                                    <?php
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        ?>
                                                                        <tr class="odd">
                                                                            <td class="d-flex align-items-center">
                                                                                <div
                                                                                    class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                                                    <a href="/admin/nodes/info?id=<?= $row['id'] ?>">
                                                                                        <div class="symbol-label">
                                                                                            <?php 
                                                                                            $NodeResult = $checker->checkStatus($row['host'], $row['auth_key']);
                                                                                            if ($NodeResult == "online") {
                                                                                                ?>
                                                                                                <img src="https://img.icons8.com/sf-black-filled/64/40C057/like.png"
                                                                                                alt="Online"
                                                                                                class="w-100">
                                                                                                <?php
                                                                                            } else if ($NodeResult == "offline") {
                                                                                                ?> 
                                                                                                <img src="https://img.icons8.com/glyph-neue/64/FA5252/--broken-heart.png"
                                                                                                alt="Offline"
                                                                                                class="w-100">
                                                                                                <?php
                                                                                            } else if ($NodeResult == "unauthorized") {
                                                                                                ?> 
                                                                                                <img src="https://img.icons8.com/ios-filled/50/FA5252/lock-2.png"
                                                                                                alt="Offline"
                                                                                                class="w-100">
                                                                                                <?php
                                                                                            }
                                                                                            else {
                                                                                                ?> 
                                                                                                <img src="https://img.icons8.com/glyph-neue/64/FA5252/--broken-heart.png"
                                                                                                    alt="Offline"
                                                                                                    class="w-100">
                                                                                                    <script>
                                                                                                        console.error("<?= $NodeResult ?>");
                                                                                                    </script>
                                                                                                <?php
                                                                                            }                                                                                            
                                                                                            ?>
                                                                                            
                                                                                        </div>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="d-flex flex-column">
                                                                                    <a href="/admin/nodes/info?id=<?= $row['id'] ?>"
                                                                                        class="text-gray-800 text-hover-primary mb-1">
                                                                                        <?php echo $row['name']; ?>
                                                                                        
                                                                                    </a>
                                                                                    <span>
                                                                                    <?php 
                                                                                            $NodeResult = $checker->checkStatus($row['host'], $row['auth_key']);
                                                                                            if ($NodeResult == "online") {
                                                                                                ?>
                                                                                                Online
                                                                                                <?php
                                                                                            } else if ($NodeResult == "offline") {
                                                                                                ?> 
                                                                                                Offline
                                                                                                <?php
                                                                                            } else if ($NodeResult == "unauthorized") { 
                                                                                                ?> 
                                                                                                Unauthorized (Server token is incorrect)
                                                                                                <?php
                                                                                            }
                                                                                            else {
                                                                                                ?> 
                                                                                                Offline
                                                                                                <?php
                                                                                            }
                                                                                            ?>
                                                                                        
                                                                                    </span>
                                                                                </div>
                                                                            </td>
                                                                            <td
                                                                                data-order="<?php echo $row['description']; ?> ">
                                                                                <div class="badge badge-light fw-bold">
                                                                                <?php echo $row['description']; ?>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $row['host']; ?>
                                                                            </td>
                                                                            <td
                                                                                data-order="<?php echo $row['created-date']; ?>">
                                                                                <?php echo $row['created-date']; ?></td>
                                                                            <td class="text-end">
                                                                                <a href="#"
                                                                                    class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                                                                    data-kt-menu-trigger="click"
                                                                                    data-kt-menu-placement="bottom-end">Actions
                                                                                    <i
                                                                                        class="ki-outline ki-down fs-5 ms-1"></i></a>
                                                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                                                    data-kt-menu="true">
                                                                                    <?php if ($NodeResult == "online") {
                                                                                    ?>
                                                                                        <div class="menu-item px-3">
                                                                                            <a href="/admin/nodes/shutdown?id=<?php echo $row['id']; ?>"
                                                                                                class="menu-link px-3">Shutdown</a>
                                                                                        </div>
                                                                                        <div class="menu-item px-3">
                                                                                            <a href="/admin/nodes/reboot?id=<?php echo $row['id']; ?>"
                                                                                                class="menu-link px-3">Reboot</a>
                                                                                        </div>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                    <div class="menu-item px-3">
                                                                                        <a href="/admin/nodes/edit?id=<?php echo $row['id']; ?>"
                                                                                            class="menu-link px-3">Edit</a>
                                                                                    </div>
                                                                                    <div class="menu-item px-3">
                                                                                        <a href="/admin/nodes/delete?id=<?php echo $row['id']; ?>"
                                                                                            class="menu-link px-3"
                                                                                            data-kt-users-table-filter="delete_row">Delete</a>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include(__DIR__ . '/../../requirements/footer.php') ?>
        <!-- Initialize DataTables -->
        <script>
            $(document).ready(function () {
                $('#userTable').DataTable();
            });
        </script>
    </div>
</body>

</html>