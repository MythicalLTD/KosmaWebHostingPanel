<?php
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Kosma\Database\Connect;

include(__DIR__ . '/../../requirements/page.php');
include(__DIR__ . '/../requirements/admin.php');

$conn = new Connect();
$conn = $conn->connectToDatabase();
function has_ip_address($domain)
{
    $ip_address = gethostbyname($domain);
    return $ip_address !== $domain;
}

function has_trailing_slash($url)
{
    $parsed_url = parse_url($url);
    if (isset($parsed_url['path']) && substr($parsed_url['path'], -1) === '/') {
        return true;
    } else {
        return false;
    }
}

function detect_url_scheme($url)
{
    $parsed_url = parse_url($url);

    if (isset($parsed_url['scheme'])) {
        $scheme = strtolower($parsed_url['scheme']);
        if ($scheme === 'https') {
            return 'https';
        } elseif ($scheme === 'http') {
            return 'http';
        }
    }
}

if (isset($_POST['submit']) && $_POST['submit'] == "") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $dsc = mysqli_real_escape_string($conn, $_POST['description']);
    $host = mysqli_real_escape_string($conn, $_POST['host']);
    $auth_key = mysqli_real_escape_string($conn, $_POST['auth_key']);
    if (!$name == "" && !$host == "" && !$auth_key == "") {
        if (!has_ip_address($host)) {
            if (!filter_var($host, FILTER_VALIDATE_URL) === false) {
                if (!has_trailing_slash($host)) {
                    $scheme = detect_url_scheme($host);
                    if ($scheme === 'https') {
                        $client = new Client();
                        try {
                            try {
                                $response = $client->post($host . '/api/daemon/info', [
                                    'form_params' => ['system_token' => $auth_key],
                                ]);
    
                                $statusCode = $response->getStatusCode();
                                $data = json_decode($response->getBody(), true);
    
                                if ($statusCode === 200) {
                                    if (json_last_error() === JSON_ERROR_NONE) {
                                        if (isset($data['code']) && $data['code'] === 200) {
                                            $user_query = "SELECT * FROM nodes WHERE name = ? AND host = ?";
                                            $stmt = mysqli_prepare($conn, $user_query);
                                            mysqli_stmt_bind_param($stmt, "ss", $name, $host);
                                            mysqli_stmt_execute($stmt);
                                            $result = mysqli_stmt_get_result($stmt);
                                            if (!mysqli_num_rows($result) > 0) {
                                                $conn->query("INSERT INTO `nodes` (`name`, `description`, `host`, `auth_key`) VALUES ('" . $name . "', '" . $dsc . "', '" . $host . "', '" . $auth_key . "')");
                                                $conn->close();
                                                $stmt->close();
                                                header('location: /admin/nodes?s=We connected to the node: <code>' . $data['error'] . '</code>');
                                                die();
                                            } else {
                                                $conn->close();
                                                $stmt->close();
                                                header('location: /admin/nodes?e=Failed to connect to the node: <code>ENC:1 This node already exists in the database.</code>');
                                                die();
                                            }
                                        } else {
                                            header('location: /admin/nodes?e=Failed to connect to the node: <code>ENC:2 ' . $data['error'] . '</code>');
                                            die();
                                        }
                                    } else {
                                        header('location: /admin/nodes?e=Failed to connect to the node: <code>ENC:3 Invalid JSON response</code>');
                                        die();
                                    }
                                } else {
                                    header('location: /admin/nodes?e=Failed to connect to the node: <code>ENC:4 Unexpected status code:' . $statusCode . '</code>');
                                    die();
                                }
                            } catch (RequestException $e) {
                                if ($e->hasResponse()) {
                                    $response = $e->getResponse();
                                    $statusCode = $response->getStatusCode();
    
                                    $data = json_decode($response->getBody(), true);
    
                                    if (json_last_error() === JSON_ERROR_NONE) {
                                        header('location: /admin/nodes?e=Failed to connect to the node: <code>ENC:5 ' . $data['error'] . '</code>');
                                        die();
                                    } else {
                                        header('location: /admin/nodes?e=Failed to connect to the node: <code>ENC:6 ' . $response->getReasonPhrase() . '</code>');
                                        die();
                                    }
                                } else {
                                    header('location: /admin/nodes?e=Failed to connect to the node: <code>ENC:7 ' . $e->getMessage() . '</code>');
                                    die();
                                }
                            }
                        } catch (Exception $exed) {
                            header('location: /admin/nodes?e=Failed to connect to the node: <code>ENC:8 Some unexpected error occurred.</code>');
                            die();
                        }
                    } else {
                        header('location: /admin/nodes?e=Failed to connect to the node: <code>ENC:9 Please use a https connection</code>');
                        die();
                    }

                } else {
                    header('location: /admin/nodes?e=Failed to connect to the node: <code>ENC:10 This is not a valid url please remove the / after the url</code>');
                    die();
                }

            } else {
                header('location: /admin/nodes?e=Failed to connect to the node: <code>ENC:11 This is not a valid url</code>');
                die();
            }
        } else {
            header('location: /admin/nodes?e=Failed to connect to the node: <code>ENC:12 This is not a valid domain</code>');
            die();
        }
    } else {
        header('location: /admin/nodes?e=Please fill in all required information');
        die();
    }
}
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