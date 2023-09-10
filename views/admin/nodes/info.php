<?php
include(__DIR__ . '/../../requirements/page.php');
include(__DIR__ . '/../requirements/admin.php');
use Kosma\Nodes\NodeConnection;
use Kosma\Database\Connect;

$node = new NodeConnection();
$conn = new Connect();
$conn = $conn->connectToDatabase();

if (isset($_GET['id']) && !$_GET['id'] == "") {
    $node_id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM nodes WHERE id = '" . $node_id . "'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            try {
                $NodeInfo = $node->AdvancedCheckStatus($row['host'], $row['auth_key']);
                if ($NodeInfo == "online") {
                    $NodeData = $node->getNodeInfo($row['host'], $row['auth_key']);
                    if ($NodeData !== null) {
                        ?>
                        <script>
                            alert("OS: <?= $NodeData['os_type'] ?>\nKernel: <?= $NodeData['kernel'] ?> \nUptime: <?= $NodeData['uptime']['days'] . ' days, ' . $NodeData['uptime']['hours'] . ' hours, ' . $NodeData['uptime']['minutes'] . ' minutes' ?> \nDisk <?= $NodeData['disk_info']['used'] . ' (Used) / ' . $NodeData['disk_info']['free'] . ' (Free) / ' . $NodeData['disk_info']['total'] . ' (Total)' ?> \nRam: <?= $NodeData['ram_info']['used'] . ' (Used) / ' . $NodeData['ram_info']['free'] . ' (Free) / ' . $NodeData['ram_info']['total'] . ' (Total)' ?>\nCPU Usage: <?= $NodeData['cpu_info']['usage'] . '%/100%' ?>\nCPU Name: <?= $NodeData['cpu_info']['name'] ?>");
                            window.location.replace("/admin/nodes");
                        </script>
                        <?php
                        die();
                    } else {
                        ?>
                        <script>
                            alert("Sorry but we can't reach the daemon at this moment");
                            window.location.replace("/admin/nodes");
                        </script>
                        <?php
                        die();
                    }
                } else {
                    ?>
                    <script>
                        alert("Sorry but we can't reach the daemon at this moment");
                        window.location.replace("/admin/nodes");
                    </script>
                    <?php
                    die();
                }
            } catch (Exception $ex) {
                ?>
                <script>
                    alert("Sorry but we can't reach the daemon at this moment");
                    window.location.replace("/admin/nodes");
                </script>
                <?php
                die();
            }
        } else {
            header("location: /admin/nodes?e=Sorry but we can't find this node in the database");
            die();
        }
    } else {
        header("location: /admin/nodes?e=Sorry but we can't find this node in the database");
        die();
    }
} else {
    header('location: /admin/nodes?e=Please specify what node you want to get more information.');
    die();
}
?>