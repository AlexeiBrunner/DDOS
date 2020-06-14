<?

    require_once __DIR__ . '/../src/databases/DB.php';
    $connection = \App\Databases\DB::getInstance()->getConnection();

    $connection->exec("CREATE TABLE banned_ips(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ip VARCHAR(30) NOT NULL UNIQUE ,
    request_time TIMESTAMP,
    is_blocked BOOLEAN
)"
    );
