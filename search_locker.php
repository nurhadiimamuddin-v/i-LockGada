<?php
include 'config.php';

header('Content-Type: application/json');

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$resultsPerPage = 10;

$query = "SELECT * FROM locker 
          WHERE status='belum_terisi' 
          AND (kode_locker LIKE ? OR ukuran LIKE ?)
          LIMIT ?, ?";

$searchParam = "%{$searchTerm}%";
$offset = ($page - 1) * $resultsPerPage;

$stmt = $conn->prepare($query);
$stmt->bind_param("ssii", $searchParam, $searchParam, $offset, $resultsPerPage);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = [
        'id' => $row['id_locker'],
        'text' => $row['kode_locker'] . ' (' . ($row['ukuran'] ?? 'Medium') . ')',
        'ukuran' => $row['ukuran'] ?? 'Medium'
    ];
}

// Get total count for pagination
$countQuery = "SELECT COUNT(*) as total FROM locker 
               WHERE status='belum_terisi' 
               AND (kode_locker LIKE ? OR ukuran LIKE ?)";
$countStmt = $conn->prepare($countQuery);
$countStmt->bind_param("ss", $searchParam, $searchParam);
$countStmt->execute();
$totalCount = $countStmt->get_result()->fetch_assoc()['total'];

echo json_encode([
    'items' => $items,
    'total_count' => $totalCount
]);
?>