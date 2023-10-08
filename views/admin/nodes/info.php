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
                            alert("OS: <?= $NodeData['os_type']?>\nKernel: <?= $NodeData['kernel'] ?>\nCPU Name: <?= $NodeData['cpu_info']['name'] ?>\nUptime: <?= $NodeData['uptime']?>\nDisk: <?= $NodeData['disk_info']['used']?>MB / <?= $NodeData['disk_info']['total']?>MB (Free: <?= $NodeData['disk_info']['free']?>MB)\nMemory: <?= $NodeData['ram_info']['used']?>MB / <?= $NodeData['ram_info']['total']?>MB (Free: <?= $NodeData['ram_info']['free']?>MB)\nCPU Usage: <?= $NodeData['cpu_info']['usage'] ?>/100%");
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