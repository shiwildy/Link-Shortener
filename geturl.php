<?php
    require_once "vendor/autoload.php";
    use Dotenv\Dotenv;
    
    function connectdb() {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    
        $dbHost = $_ENV['DB_HOST'];
        $dbName = $_ENV['DB_NAME'];
        $dbUser = $_ENV['DB_USER'];
        $dbPass = $_ENV['DB_PASS'];
    
        if (!$dbHost || !$dbName || !$dbUser) {
            echo "Database configuration error!";
            die();
        }
    
        try {
            $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            echo "Database connection error: " . $e->getMessage();
            die();
        }
    }
    
    function getlink($shortedlink) {
        $db = connectdb();
        $stmt = $db->prepare("SELECT links FROM shortedlinks WHERE shortedid = :shortedid");
        $stmt->bindParam(':shortedid', $shortedlink);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            return $result['links'];
        } else {
            return null;
        }
    }
    
    $shortedlink = $_GET['urlshorted'];
    $getlink = getlink($shortedlink);
    
    if ($getlink) {
        header("Location: $getlink");
        exit();
    } else {
        include_once "index.php";
        echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Shortlink not found",
                    text: "We couldn\'t find the requested link in our system.",
                    confirmButtonColor: "#4299e1",
                    confirmButtonText: "OK, I will check again",
                }).then(function() {
                    window.location.href = "/";
                });
            </script>
        ';
    }
?>
