<?php
header('Content-Type: text/html; charset=utf-8');

$host = 'localhost';
$dbname = 'finance_feedback';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = htmlspecialchars($_POST['name'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $message = htmlspecialchars($_POST['message'] ?? '');
        
        if (empty($name) || empty($email) || empty($message)) {
            throw new Exception("Все поля обязательны для заполнения");
        }
        
        $stmt = $conn->prepare("INSERT INTO feedback (name, email, message) VALUES (:name, :email, :message)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);
        $stmt->execute();
        
        echo '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Успешно</title>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="styles.css">
        </head>
        <body>
            <div style="text-align:center;padding:50px;">
                <h2>Сообщение отправлено!</h2>
                <p>Мы ответим вам в ближайшее время.</p>
                <a href="page1.html">На главную</a>
            </div>
        </body>
        </html>';
        exit();
    }
    
} catch(Exception $e) {
    error_log("Error: " . $e->getMessage());
    
    echo '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Ошибка</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div style="text-align:center;padding:50px;">
            <h2 style="color:red">Ошибка</h2>
            <p>При отправке сообщения произошла ошибка.</p>
            <p>'.htmlspecialchars($e->getMessage()).'</p>
            <a href="feedback.html">Вернуться к форме</a>
        </div>
    </body>
    </html>';
    exit();
}
?>