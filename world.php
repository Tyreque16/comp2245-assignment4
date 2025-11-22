<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get the country from the URL (GET request)
$country = isset($_GET['country']) ? trim($_GET['country']) : "";

// Prepare SQL query (use correct column names)
$columns = ['name', 'continent', 'independence_year', 'head_of_state']; // adjust 'independence_year' if needed

if ($country !== "") {
    $stmt = $conn->prepare("SELECT " . implode(',', $columns) . " FROM countries WHERE name LIKE :country");
    $stmt->bindValue(':country', "%$country%", PDO::PARAM_STR);
    $stmt->execute();
} else {
    $stmt = $conn->query("SELECT " . implode(',', $columns) . " FROM countries");
}

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Output HTML table -->
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Country Name</th>
            <th>Continent</th>
            <th>Independence Year</th>
            <th>Head of State</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($results)): ?>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['continent']) ?></td>
                    <td><?= htmlspecialchars($row['independence_year'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['head_of_state'] ?? 'N/A') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No countries found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
