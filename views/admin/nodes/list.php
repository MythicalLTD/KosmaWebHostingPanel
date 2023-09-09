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
<html lang="en">

<head>
    <base href="/" />
    <title>
        <?= $settingsManager->getSetting('name') ?> | Users
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
                                                            <button type="button" class="btn btn-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#kt_modal_add_user">
                                                                <i class="ki-outline ki-plus fs-2"></i>Add Node
                                                            </button>
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
                                                                            aria-label="User: activate to sort column ascending"
                                                                            style="width: 215.016px;">Node</th>
                                                                        <th class="min-w-125px sorting" tabindex="0"
                                                                            aria-controls="kt_table_users" rowspan="1"
                                                                            colspan="1"
                                                                            aria-label="Username: activate to sort column ascending"
                                                                            style="width: 125px;">Description</th>
                                                                        <th class="min-w-125px sorting" tabindex="0"
                                                                            aria-controls="kt_table_users" rowspan="1"
                                                                            colspan="1"
                                                                            aria-label="Role: activate to sort column ascending"
                                                                            style="width: 125px;">Host</th>
                                                                        <th class="min-w-125px sorting" tabindex="0"
                                                                            aria-controls="kt_table_users" rowspan="1"
                                                                            colspan="1"
                                                                            aria-label="Joined Date: activate to sort column ascending"
                                                                            style="width: 159.688px;">Created Date</th>
                                                                        <th class="text-end min-w-100px sorting_disabled"
                                                                            rowspan="1" colspan="1" aria-label="Actions"
                                                                            style="width: 101.906px;">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="text-gray-600 fw-semibold">
                                                                    <?php
                                                                    // Loop through your user data and populate the table rows here
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        ?>
                                                                        <tr class="odd">
                                                                            <td class="d-flex align-items-center">
                                                                                <div
                                                                                    class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                                                    <a href="#">
                                                                                        <div class="symbol-label">
                                                                                            <img src="https://img.icons8.com/ios-filled/100/40C057/filled-circle.png"
                                                                                                alt="Online"
                                                                                                class="w-100">
                                                                                        </div>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="d-flex flex-column">
                                                                                    <a href="#"
                                                                                        class="text-gray-800 text-hover-primary mb-1">
                                                                                        <?php echo $row['name']; ?>
                                                                                        
                                                                                    </a>
                                                                                    <span>
                                                                                        Online
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
                                                                                    <div class="menu-item px-3">
                                                                                        <a href="/admin/user/edit?id=<?php echo $row['id']; ?>"
                                                                                            class="menu-link px-3">Edit</a>
                                                                                    </div>
                                                                                    <div class="menu-item px-3">
                                                                                        <a href="/admin/user/delete?id=<?php echo $row['id']; ?>"
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
                                    <?php include(__DIR__ . '/../../components/footer.php') ?>
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