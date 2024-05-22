<?php
  $host = 'localhost';
  $user = 'u67297';
  $password = '5665219';
  $database = 'u67297';

  $conn = mysqli_connect($host, $user, $password, $database);

 

  // Проверка подключения
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
  
  // Получение данных из формы
  $name = $_POST['name'];
  $surname = $_POST['surname'];
  $number = $_POST['number'];
  $email = $_POST['email'];
  $date = $_POST['date'];
  $gender = $_POST['gen'];
  $about = $_POST['about'];
  $document = isset($_POST['document']) ? 1 : 0;
  $languages = $_POST['leng'];
  
  $errors = []; // Массив для хранения ошибок

// Валидация данных
if (!preg_match("/^[a-zA-Zа-яА-Я\s]{1,150}$/u", $name)) {
    $errors['name'] = "Ошибка: Поле ФИО должно содержать только буквы и пробелы и быть не длиннее 150 символов.";
}

if ($gender != 'Мужчина' && $gender != 'Женщина') {
    $errors['gender'] = "Ошибка: Некорректное значение поля Пол.";
}

// Проверка наличия ошибок
if (!empty($errors)) {
    setcookie('form_errors', json_encode($errors), time() + 3600); // Сохранение ошибок в Cookies на 1 час
    header('Location: index.html'); // Перенаправление на страницу формы
    exit;
}

// Если ошибок нет, продолжаем выполнение кода

// Удаление Cookies с ошибками после использования
if (isset($_COOKIE['form_errors'])) {
    unset($_COOKIE['form_errors']);
    setcookie('form_errors', null, -1);
}
// Удаление Cookies с ошибками после использования
if (isset($_COOKIE['form_errors'])) {
    unset($_COOKIE['form_errors']);
    setcookie('form_errors', null, -1, '/');
}
  
  // Подготовленный запрос для вставки данных в таблицу пользователей
  $stmt = $conn->prepare("INSERT INTO users (name, surname, number, email, date, gender, about, document) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssissssi", $name, $surname, $number, $email, $date, $gender, $about, $document);
  
  // Выполнение запроса
  if ($stmt->execute()) {
      $last_id = $conn->insert_id; // Получение ID последней вставленной записи
  
      // Вставка выбранных языков программирования в отдельную таблицу
      $stmt_lang = $conn->prepare("INSERT INTO user_languages (user_id, language) VALUES (?, ?)");
      $stmt_lang->bind_param("is", $user_id, $language);
  
      foreach ($languages as $language) {
          if (in_array($language, ['Pascal', 'C', 'C++', 'JavaScript', 'PHP', 'Python', 'Java', 'Haskel', 'Clojure', 'Prolog', 'Scara'])) {
              $user_id = $last_id;
              $stmt_lang->execute();
          } else {
              echo "Ошибка: Некорректное значение в списке ЯП.";
              exit;
          }
      }
  
      echo "Данные успешно сохранены.";
  } else {
      echo "Ошибка при сохранении данных: " . $conn->error;
  }
  // Проверка наличия ошибок
if (empty($errors)) {
    // Сохранение значений в Cookies на один год
    setcookie('name', $name, time() + 31536000, '/');
    setcookie('gender', $gender, time() + 31536000, '/');
    
    // Перенаправление на страницу успешной отправки
    header('Location: index.html');
    exit;
}

  // Закрытие подключения
  $conn->close();
  ?>