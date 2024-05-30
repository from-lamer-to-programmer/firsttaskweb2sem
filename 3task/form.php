<?php
// Подключение к базе данных
$servername = "localhost";
$username = "u67297";
$password = "5665219";
$dbname = "u67297";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Проверка, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Получение данных из формы
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $number = $_POST["number"];
    $email = $_POST["email"];
    $date = $_POST["date"];
    $gender = $_POST["gender"];
    $about = $_POST["about"];
    $document = isset($_POST["document"]) ? 1 : 0;
    $selectedLangs = $_POST["langs"];

   $errors = array(); // массив для хранения ошибок

    // Валидация поля "name"
    if (strlen($name) > 75 & !preg_match("/^[a-zA-Zа-яА-Я\s]+$/", $name)) {
        $errors["name"] = "Поле 'Имя' не должно превышать 75 символов и содержит только буквы и пробелы";
    }

    // Валидация поля "surname"
    if (strlen($name) > 75 & !preg_match("/^[a-zA-Zа-яА-Я\s]+$/", $name)){
        $errors["surname"] = "Поле 'Фамилия' не должно превышать 75 символов.";
    }

    // Валидация поля "number"
    if (!preg_match("/^(\+7|8)[0-9]{10}$/", $number)) {
        $errors["number"] = "Поле 'Номер телефона' должно содержать 11 цифр.";
    }

    // Валидация поля "email"
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Поле 'Email' должно содержать корректный email-адрес.";
    }

    // Валидация поля "gender"
    if ($gender !== "1" && $gender !== "2") {
        $errors["gender"] = "Поле 'Пол' должно содержать значение 1 (Мужской) или 2 (Женский).";
    }

    // Валидация поля "langs"
    $allowedLangs = array("Pascal", "C", "C++", "JavaScript", "PHP", "Python", "Java", "Haskel", "Clojure", "Prolog", "Scara");
    if (empty($selectedLangs) || !is_array($selectedLangs) || count(array_diff($selectedLangs, $allowedLangs)) > 0) {
        $errors["langs"] = "Поле 'Языки' должно содержать один или более из следующих языков: " . implode(", ", $allowedLangs) . ".";
    }

    // Валидация поля "about"
    if (strlen($about) > 400) {
        $errors["about"] = "Поле 'О себе' не должно превышать 400 символов.";
    }

    // Если ошибок нет, то данные можно использовать
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    } else {
        // если есть ошибки, то выводим их



    // Подготовка SQL-запроса
    $sql = "INSERT INTO Users (name, surname, number, email, date, gender, about, document)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $name, $surname, $number, $email, $date, $gender, $about, $document);

    // Выполнение запроса
    if ($stmt->execute()) {
    // Вставка выбранных языков в таблицу Languages
    foreach ($selectedLangs as $lang) {
        $sql = "INSERT INTO Languages (language_name) VALUES ('$lang')";
        if ($conn->query($sql) === FALSE) {
            echo "Ошибка при вставке языка: " . $conn->error;
        }
    }
        echo "Данные успешно записаны в базу данных.";
    } else {
        echo "Ошибка при записи данных: " . $stmt->error;
    }

    // Закрытие подготовленного оператора
    $stmt->close();
}
}
// Закрытие соединения с базой данных
$conn->close();
?>

