<?php
    require_once "vendor/autoload.php";
    use Dotenv\Dotenv;

    function connectdb2() {
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

    function genrandstring($length) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomstring = '';
        for ($i = 0; $i < $length; $i ++) {
            $randomstring .= $characters[rand(0, strlen($characters) -1)];
        }
        return $randomstring;
    }

    function shortenlinks($originalLink) {
        $shortedId = genrandstring(6);
        $db = connectdb2();
        $stmt = $db->prepare("INSERT INTO shortedlinks (links, shortedid) VALUES (?, ?)");
        $stmt->execute([$originalLink, $shortedId]);
        return $shortedId;
    }

    function getsitekey() {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        $sitekey = $_ENV['GCAPTCHA_SITEKEY'];
        if (!$sitekey) {
            echo "reCaptcha Sitekey not configured"; die();
        }
        return $sitekey;
    }

    function getsecretkey() {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        $scrkey = $_ENV['GCAPTCHA_SECRETKEY'];
        if (!$scrkey) {
            echo "reCaptcha Sitekey not configured"; die();
        }
        return $scrkey;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $originalLink = $_POST['inputlink'];
        $captchaResponse = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
        $captchaVerificationUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $captchaData = array(
            'secret' => getsecretkey(),
            'response' => $captchaResponse
        );
        $captchaOptions = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($captchaData),
            ),
        );
        $captchaContext = stream_context_create($captchaOptions);
        $captchaResult = file_get_contents($captchaVerificationUrl, false, $captchaContext);
        $captchaResult = json_decode($captchaResult);
    
        if (!$captchaResult->success) {
            echo "CAPTCHA verification failed!";
            die();
        }
    
        $shortedId = shortenlinks($originalLink);
        $shortLink = "https://pasteyourweburlinhere/$shortedId";
    }    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Shortener</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.7/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @media (min-width: 768px) {
            .box {
                max-width: 550px;
            }
        }
    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white shadow-md rounded-lg p-8 mx-4 box w-full sm:w-auto">
            <h1 class="text-2xl font-semibold mb-6">Link Shortener</h1>
            <form action="#" method="POST" class="flex flex-col">
                <input type="url" name="inputlink" placeholder="Enter your link" class="rounded-l-lg p-2 focus:outline-none mb-2" required>
                <div class="g-recaptcha mb-2" data-sitekey="<?php echo getsitekey() ?>"></div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg">Shorten</button>
            </form>

            <?php if (!empty($shortLink)) { ?>
                <div class="mt-6">
                    <p class="text-sm">Shortened link:</p>
                    <a href="<?php echo $shortLink; ?>" class="text-blue-500 hover:text-blue-600 font-semibold"><?php echo $shortLink; ?></a>
                </div>
            <?php } ?>
        </div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>