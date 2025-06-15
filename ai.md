# local olarak nasıl test ederim kodumu?

✅ GEREKENLER
🧰 1. Yazılım Gereksinimleri
Bilgisayarında şu yazılımlar olmalı:

XAMPP (veya alternatif olarak WAMP / MAMP) → https://www.apachefriends.org/index.html

🧪 ADIM ADIM LOCAL TEST
🔹 1. XAMPP’i Kur ve Başlat
XAMPP’ı indir ve kur.

“Apache” ve “MySQL” servislerini başlat.

🔹 2. Proje klasörünü doğru yere yerleştir
Tüm dosyalarını (config.php, gamerecord.php, user.php, header.php, database.sql) bir klasöre koy. Örn: game-tracker

Bu klasörü C:\xampp\htdocs içine taşı:

pgsql
Kopyala
Düzenle
C:\xampp\htdocs\game-tracker\
    ├── config.php
    ├── gamerecord.php
    ├── user.php
    ├── header.php
    └── database.sql
🔹 3. Veritabanını oluştur
Tarayıcında http://localhost/phpmyadmin adresine git.

Yeni veritabanı oluştur:

İsim: game_tracker (ya da sen ne istiyorsan)

Üstteki menüden Import (İçe Aktar) → database.sql dosyanı seç → Go.

🔹 4. config.php dosyasını local'e göre düzenle
php
Kopyala
Düzenle
<?php
class DatabaseConfig {
    private static $hosting_config = [
        'host' => 'localhost',
        'dbname' => 'game_tracker', // phpMyAdmin'de oluşturduğun isimle aynı olmalı
        'username' => 'root',
        'password' => '' // XAMPP'de varsayılan olarak boş şifre
    ];

    public static function getConfig() {
        return self::$hosting_config;
    }
}

define('SITE_URL', 'http://localhost/game-tracker');
?>
🔍 TEST ETME
Tarayıcıda şunu aç:
👉 http://localhost/game-tracker/user.php
veya
👉 http://localhost/game-tracker/gamerecord.php

Eğer veritabanı bağlantısı doğruysa ve dosyalar düzgün çalışıyorsa sayfa yüklenir. Hatalar çıkarsa mesaj verir (örneğin tablo bulunamadı, bağlantı sağlanamadı vs.)

🛠️ Hata alırsan ne yapmalı?
Beyaz sayfa → PHP hataları bastırılmış olabilir. php.ini içinde display_errors = On yap.

“Table doesn't exist” → Veritabanını düzgün import etmedin demektir.

Fatal error: Cannot redeclare... → Aynı sınıf/dosya birden fazla include edilmiş olabilir.

# username.php kodumda boş ekran alıyorum hataları nasıl görebilirim?

1. Hata gösterimini açalım
user.php dosyanın en başına şu satırları ekle:

php
Kopyala
Düzenle
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
Bu sayede hata varsa ekranda göreceğiz.

2. Dosyanın tam halini paylaşabilir misin?
Eğer mümkünse user.php dosyasının tamamını buraya kopyala, inceleyeyim.

3. Örnek basit bir user.php test dosyası
Şu basit kodu user.php olarak kaydet ve yine http://localhost/VTforGame/user.php’de aç:

php
Kopyala
Düzenle
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

try {
    $pdo = DatabaseConfig::getConnection();
    $stmt = $pdo->query("SELECT id, username, email FROM users");

    echo "<h2>Kullanıcılar</h2><ul>";
    while ($row = $stmt->fetch()) {
        echo "<li>{$row['username']} ({$row['email']})</li>";
    }
    echo "</ul>";
} catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
}
?>
4. Sonrası
Bu sayfa eğer yine boşsa, config.php dosyanda veritabanı bilgileri doğru mu?

Veritabanında users tablosu var mı, phpMyAdmin’den kontrol ettin mi?

# veritabanını oluşturdum dosyalarımı içeri aktardım user.# php ve test.php kodlarımı tarayıcıda açmaya çalışıyorum # ancak hala ekran boş görünüyor 


1. Hata raporlamayı kesin açtığından emin ol
test.php dosyasının en başına şu satırları koyduğundan emin ol (ki zaten koymuştun ama tekrar kontrol):

php
Kopyala
Düzenle
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
2. Basit bir echo ile PHP çalışıyor mu test et
test.php dosyanın içeriğini sadece şu şekilde değiştirip kaydet:

php
Kopyala
Düzenle
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "PHP çalışıyor!";
Tarayıcıda http://localhost/VTforGame/test.php açınca "PHP çalışıyor!" yazısını görüyorsan PHP dosyan doğru çalışıyor.

3. user.php ve config.php dosya yolu doğru mu?
test.php içinde şu satırı kullanıyorsun:

php
Kopyala
Düzenle
require_once 'config.php';
require_once 'user.php';
test.php, config.php ve user.php aynı klasörde mi?

Eğer değilse dosya yolunu düzeltmelisin. Mesela config.php VTforGame klasöründe değil başka klasördeyse, göreli yol vermelisin:

php
Kopyala
Düzenle
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/user.php';
Ya da tam yol ver:

php
Kopyala
Düzenle
require_once 'C:/xampp/htdocs/VTforGame/config.php';
require_once 'C:/xampp/htdocs/VTforGame/user.php';
4. login fonksiyonunda doğru şifreyi kullanıyor musun?
Senin örnek kullanıcı ekleme kodunda (SQL'de) şifre hash şu:

swift
Kopyala
Düzenle
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
Bu, password_hash('password', PASSWORD_DEFAULT) ile oluşturulmuş default "password" kelimesinin hash'idir.

Yani test.php'deki:

php
Kopyala
Düzenle
$loginResult = $user->login('admin', 'password'); // 'password' kelimesi doğru
Doğru ama farklıysa şifreyi değiştirdiysen ona göre değiştir.

5. test.php dosyasını şu şekilde güncelle, daha fazla çıktı alalım:
php
Kopyala
Düzenle
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';
require_once 'user.php';

echo "Config ve User sınıfı yüklendi.<br>";

try {
    $user = new User();
    echo "User sınıfı örneği oluşturuldu.<br>";

    $loginResult = $user->login('admin', 'password');
    echo "Login fonksiyonu çalıştırıldı.<br>";

    echo "<pre>";
    print_r($loginResult);
    echo "</pre>";
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
6. Son olarak Apache loglarına bakabilirsin
XAMPP kontrol panelinde Apache > Logs > error.log dosyasını aç ve son satırlara bak.

Yapacakların:

test.php basit echo testi yap (PHP çalışıyor mu).

Dosya yollarını kontrol et.

Gelişmiş test.php ile adım adım çıktıları gör.

Hata varsa Apache log'a bak.

# Fatal error: Uncaught Error: Class "SecurityConfig" not found in C:\xampp\htdocs\VTforGame\user.php:59 Stack trace: #0 C:\xampp\htdocs\VTforGame\test.php(11): User->login('admin', 'password') #1 {main} thrown in C:\xampp\htdocs\VTforGame\user.php on line 59

Bu hata, SecurityConfig sınıfının tanımlanmadığını gösteriyor. Yani user.php içinde SecurityConfig kullanıyorsun ama PHP dosyalarında SecurityConfig sınıfı yok ya da dahil edilmemiş.


# işte benim kodum :<?php
// Veritabanı bağlantı ayarları
class DatabaseConfig {
    public static function getConnection() {
        $config = [
            'host' => 'localhost',
            'dbname' => 'game_tracker_db',
            'username' => 'root',
            'password' => ''
        ];
        
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
            $pdo = new PDO($dsn, $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            die("Veritabanı bağlantı hatası: " . $e->getMessage());
        }
    }
}








Yapman gerekenler:
1. config.php dosyana şu SecurityConfig sınıfını da ekle:
php
Kopyala
Düzenle
<?php
// Veritabanı bağlantı ayarları
class DatabaseConfig {
    public static function getConnection() {
        $config = [
            'host' => 'localhost',
            'dbname' => 'game_tracker_db',
            'username' => 'root',
            'password' => ''
        ];
        
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
            $pdo = new PDO($dsn, $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            die("Veritabanı bağlantı hatası: " . $e->getMessage());
        }
    }
}

// Güvenlik ayarları
class SecurityConfig {
    public static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on');
            ini_set('session.use_only_cookies', 1);
            session_start();
        }
    }
    
    public static function regenerateSessionId() {
        session_regenerate_id(true);
    }
    
    public static function cleanInput($data) {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    public static function isLoggedIn() {
        self::startSession();
        return isset($_SESSION['user_id']) && isset($_SESSION['username']);
    }
    
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: login.php');
            exit();
        }
    }
    
    public static function logout() {
        self::startSession();
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
}
?>


# oyuncu giriş  arayüzüne benzetmek için ne yapmalıyım

<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Şifre yanlış.";
        }
    } else {
        $error = "Kullanıcı bulunamadı.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Oyuncu Giriş</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap');

        body {
            background: radial-gradient(circle, #0f0c29, #302b63, #24243e);
            font-family: 'Orbitron', sans-serif;
            color: #00f7ff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .login-panel {
            background: rgba(0, 0, 0, 0.6);
            border: 2px solid #00f7ff;
            border-radius: 15px;
            box-shadow: 0 0 25px #00f7ff88;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(10px);
            animation: fadeIn 1s ease;
        }

        .login-panel h2 {
            text-align: center;
            color: #00f7ff;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }

        label {
            color: #a0faff;
        }

        .form-control {
            background-color: #121212;
            border: 1px solid #00f7ff;
            color: #00f7ff;
        }

        .form-control:focus {
            background-color: #1a1a1a;
            border-color: #72ffff;
            box-shadow: 0 0 10px #00f7ff;
        }

        .btn-neon {
            background: none;
            border: 2px solid #00f7ff;
            color: #00f7ff;
            width: 100%;
            padding: 10px;
            font-weight: bold;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-neon:hover {
            background-color: #00f7ff;
            color: #000;
            box-shadow: 0 0 15px #00f7ff;
        }

        .register-link {
            color: #a0faff;
            text-align: center;
            margin-top: 15px;
            display: block;
        }

        .register-link a {
            color: #72ffff;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .alert {
            font-size: 14px;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: scale(0.9);}
            to {opacity: 1; transform: scale(1);}
        }
    </style>
</head>
<body>
    <div class="login-panel">
        <h2>OYUNCU GİRİŞ</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Kullanıcı Adı</label>
                <input type="text" id="username" name="username" class="form-control" required autocomplete="off">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Şifre</label>
                <input type="password" id="password" name="password" class="form-control" required autocomplete="off">
            </div>
            <button type="submit" class="btn btn-neon">Giriş Yap</button>
        </form>
        <div class="register-link">
            Hesabın yok mu? <a href="register.php">Kayıt Ol</a>
        </div>
    </div>
</body>
</html>




# Fatal error: Uncaught mysqli_sql_exception: Unknown column 'password' in 'field list' in C:\xampp\htdocs\VTforGame\register.php:14 Stack trace: #0 C:\xampp\htdocs\VTforGame\register.php(14): mysqli->prepare('INSERT INTO use...') #1 {main} thrown in C:\xampp\htdocs\VTforGame\register.php on line 14


users tablonuzda password isimli bir sütun bulunmuyor.

Çözüm için adımlar:
Veritabanı tablonuzu kontrol edin
users tablonuzun yapısını inceleyin. password sütunu gerçekten var mı?
MySQL komutu ile bakabilirsiniz:

sql
Kopyala
Düzenle
DESCRIBE users;
ya da phpMyAdmin kullanıyorsanız tablo yapısını görebilirsiniz.

Eğer tablo yapınızda password yoksa, tabloyu aşağıdaki gibi güncellemeniz gerekebilir:

sql
Kopyala
Düzenle
ALTER TABLE users ADD COLUMN password VARCHAR(255) NOT NULL;


# Fatal error: Uncaught Error: Undefined constant "DB_HOST" in C:\xampp\htdocs\VTforGame\dashboard.php:13 Stack trace: #0 {main} thrown in C:\xampp\htdocs\VTforGame\dashboard.php on line 13


u hata, DB_HOST gibi sabitlerin (constant) tanımlanmadığını gösteriyor. Yani, dashboard.php dosyasında veritabanı bağlantısı kurmaya çalışırken, DB_HOST ve diğer benzer sabitler tanımlı değil.

Neden oluyor?
Genellikle config.php gibi bir dosyada veritabanı bağlantı bilgileri şu şekilde tanımlanır:

php
Kopyala
Düzenle
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'kullanici_adi');
define('DB_PASS', 'sifre');
define('DB_NAME', 'veritabani_adi');
?>
Ve dashboard.php gibi dosyalarda config.php dahil edilerek bu sabitler kullanılır.

Eğer config.php dosyanı dashboard.php dosyasına dahil etmezsen ya da config.php içinde bu sabitler tanımlı değilse, böyle bir hata alırsın.

Çözüm adımları
dashboard.php dosyanın en başına şu satırı ekle:

php
Kopyala
Düzenle
require_once 'config.php';
config.php dosyanda aşağıdaki gibi veritabanı bağlantı bilgileri ve sabitlerin tanımlandığından emin ol:

php
Kopyala
Düzenle
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // XAMPP için genellikle root
define('DB_PASS', '');            // Eğer şifre yoksa boş bırak
define('DB_NAME', 'game_tracker_db');  // Veritabanı adı
?>
dashboard.php içinde veritabanı bağlantısını şöyle yapabilirsin:

php
Kopyala
Düzenle
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($mysqli->connect_errno) {
    die("Veritabanı bağlantısı kurulamadı: " . $mysqli->connect_error);
}
Özet
config.php dosyasını oluştur ve içinde DB_HOST, DB_USER, DB_PASS, DB_NAME sabitlerini tanımla.

dashboard.php dosyasına require_once 'config.php'; ekle.

Sonra veritabanı bağlantısını mysqli ile yap.


# Warning: Undefined variable $stmtUser in /home/st23360859744/public_html/dashboard.php on line 16

Fatal error: Uncaught Error: Call to a member function bind_param() on null in /home/st23360859744/public_html/dashboard.php:16 Stack trace: #0 {main} thrown in /home/st23360859744/public_html/dashboard.php on line 16


ChatGPT:
Bu hata, $stmtUser değişkeninin null (yani tanımsız veya başarısız olmuş) olmasından kaynaklanıyor. Yani şu satır:

php
Kopyala
Düzenle
$stmtUser = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
başarısız olmuş ve $stmtUser değişkeni oluşturulamamış.

✅ 1. Adım: Hatanın nedenini anlamak
Bu durum genelde şu sebeplerden olur:

$conn veritabanı bağlantısı başarısız (ama bunu önceki hatalardan çözdük gibi).

users tablosu gerçekten yok (phpMyAdmin'de kontrol et).

SQL sorgusunda yazım hatası var (burada görünmüyor).

config.php düzgün yüklenmemiş.

✅ 2. Adım: Kodu kontrol ve düzeltme
Lütfen dashboard.php dosyanın ilk 20 satırını tam olarak paylaş (özellikle $stmtUser satırıyla birlikte), ama büyük ihtimalle şöyle görünmeli:

php
Kopyala
Düzenle
<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Kullanıcının adını çek
$stmtUser = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
$stmtUser->bind_param("i", $user_id);
$stmtUser->execute();
$stmtUser->bind_result($fullname);
$stmtUser->fetch();
$stmtUser->close();
Eğer bu kodu kullandıysan ve yine de $stmtUser = $conn->prepare(...) kısmı null döndürüyorsa, bu bağlantıda sorun var demektir.

✅ 3. Adım: Hatanın kesin sebebini anlamak için debug ekle
dashboard.php içinde prepare() başarısızsa hata gösterelim:

php
Kopyala
Düzenle
$stmtUser = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
if (!$stmtUser) {
    die("Hazırlama hatası: " . $conn->error);
}
Bu kodu eklersen sana tam olarak neden başarısız olduğunu gösterecek.

✅ Ekstra kontrol
phpMyAdmin → dbstorage23360859744 veritabanının içinde users tablosu gerçekten var mı?

İstersen sen dashboard.php kodunun tamamını paylaş, birlikte temiz ve çalışan halini oluşturabiliriz. Yardımcı olmaktan memnuniyet duyarım.








Şunu dedin:
<?php
require_once 'config.php';
session_start();

// Giriş yapılmamışsa login sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Kullanıcı bilgilerini al
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM game_records WHERE user_id = ?");

$stmtUser->bind_param("i", $user_id);
$stmtUser->execute();
$userResult = $stmtUser->get_result();
$user = $userResult->fetch_assoc();

// Son 5 oyun kaydını al
$stmtGames = $mysqli->prepare("
    SELECT game_name, game_mode, score, level_reached, date_played 
    FROM game_records 
    WHERE user_id = ? 
    ORDER BY date_played DESC 
    LIMIT 5
");
$stmtGames->bind_param("i", $user_id);
$stmtGames->execute();
$gamesResult = $stmtGames->get_result();
?>


ChatGPT:
Teşekkürler Elif, şimdi net şekilde görüyoruz hataları. dashboard.php dosyandaki sorunlar:

🚫 Hatalar
Tanımsız $stmtUser kullanımı:

php
Kopyala
Düzenle
$stmt = $conn->prepare(...);
$stmtUser->bind_param(...) // ← $stmtUser tanımlı değil
→ $stmtUser yerine $stmt kullanmalısın, çünkü onu tanımlamışsın.

İki farklı veritabanı bağlantı değişkeni karışıklığı:

php
Kopyala
Düzenle
$stmtGames = $mysqli->prepare(...);
→ Yukarıda $conn kullanıyorsun, burada ise $mysqli. Tutarsız.

✅ Doğru Hali
Aşağıda düzenlenmiş, hatasız ve tutarlı versiyonu:

php
Kopyala
Düzenle
<?php
require_once 'config.php';
session_start();

// Giriş yapılmamışsa login sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Kullanıcı bilgilerini al
$stmt = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$userResult = $stmt->get_result();
$user = $userResult->fetch_assoc();

// Son 5 oyun kaydını al
$stmtGames = $conn->prepare("
    SELECT game_name, game_mode, score, level_reached, date_played 
    FROM game_records 
    WHERE user_id = ? 
    ORDER BY date_played DESC 
    LIMIT 5
");
$stmtGames->bind_param("i", $user_id);
$stmtGames->execute();
$gamesResult = $stmtGames->get_result();
?>
💡 Ekstra Tavsiyeler
config.php içinde $conn olarak tanımladıysan her yerde onu kullan.

Hataları daha rahat görmek için error_reporting(E_ALL); ini_set('display_errors', 1); satırını en başa ekleyebilirsin.
