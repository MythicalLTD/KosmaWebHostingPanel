<?php
include(__DIR__ . '/../../requirements/page.php');
include(__DIR__ . '/../requirements/admin.php');


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
                                    <?php include(__DIR__ . '/../../components/alert.php') ?>
                                    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                                        <div class="modal-content p-3 p-md-5">
                                            <div class="text-center mb-4">
                                                <h3 class="mb-2">Create a new node!</h3>
                                                <p class="text-muted">Remember to generate a strong key for the nodes
                                                    connection, so now one can get access to the daemon and do bad
                                                    stuff.</p>
                                            </div>
                                            <form method="POST" action="/admin/nodes/create" class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label" for="name">Name</label>
                                                    <input type="text" id="name" name="name" class="form-control"
                                                        placeholder="Node-1" required />
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label" for="description">Description</label>
                                                    <input type="text" id="description" name="description"
                                                        class="form-control" placeholder="This is my uk node" />
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label" for="host">Host (HTTPS REQUIRED)</label>
                                                    <input type="text" id="host" name="host" class="form-control"
                                                        placeholder="https://uk.mythicalsystems.me" required />
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label" for="auth_key">Server key</label>
                                                    <input type="password" id="auth_key" name="auth_key"
                                                        class="form-control" placeholder="" required />
                                                </div>
                                                &nbsp;
                                                <div class="col-12 text-center">
                                                    <button type="submit" name="submit"
                                                        class="btn btn-primary me-sm-3 me-1">Create new node</button>
                                                    <a href="/admin/nodes" class="btn btn-label-secondary">Cancel </a>
                                                </div>
                                            </form>
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