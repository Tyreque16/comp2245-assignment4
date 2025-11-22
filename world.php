<?php
// Database connection
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

// Get query parameters
$country = isset($_GET['country']) ? trim($_GET['country']) : '';
$lookup = isset($_GET['lookup']) ? trim($_GET['lookup']) : '';

// --------------- Lookup Cities -----------------
if ($lookup === 'cities' && $country !== '') {
    $stmt = $conn->prepare("
        SELECT cities.name AS city_name, cities.district, cities.population
        FROM cities
        JOIN countries ON cities.country_code = countries.code
        WHERE countries.name LIKE :country
        ORDER BY cities.name
    ");
    $stmt->bindValue(':country', "%$country%", PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output cities in a table
    echo '<table border="1" cellpadding="5" cellspacing="0">';
    echo '<thead><tr><th>Name</th><th>District</th><th>Population</th></tr></thead>';
    echo '<tbody>';
    if (!empty($results)) {
        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['city_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['district']) . '</td>';
            echo '<td>' . htmlspecialchars($row['population']) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="3">No cities found.</td></tr>';
    }
    echo '</tbody></table>';
}

// --------------- Lookup Countries -----------------
else {
    $columns = ['name', 'continent', 'independence_year', 'head_of_state'];
    
    if ($country !== '') {
        $stmt = $conn->prepare("SELECT " . implode(',', $columns) . " FROM countries WHERE name LIKE :country");
        $stmt->bindValue(':country', "%$country%", PDO::PARAM_STR);
        $stmt->execute();
    } else {
        $stmt = $conn->query("SELECT " . implode(',', $columns) . " FROM countries");
    }
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output countries in a table
    echo '<table border="1" cellpadding="5" cellspacing="0">';
    echo '<thead><tr><th>Country Name</th><th>Continent</th><th>Independence Year</th><th>Head of State</th></tr></thead>';
    echo '<tbody>';
    if (!empty($results)) {
        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['continent']) . '</td>';
            echo '<td>' . htmlspecialchars($row['independence_year'] ?? 'N/A') . '</td>';
            echo '<td>' . htmlspecialchars($row['head_of_state'] ?? 'N/A') . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="4">No countries found.</td></tr>';
    }
    echo '</tbody></table>';
}
?>
