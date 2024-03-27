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
  
if (!preg_match('/^[а-яёА-ЯЁa-zA-Z\s]+$/', $name)) {
    echo "Ошибка: Поле ФИО должно содержать только буквы русского и английского алфавита и пробелы";
    exit;
}

if ($gender != '1' && $gender != '2') {
    echo "Ошибка: Некорректное значение поля Пол.";
    exit;
}

if (strlen($name) > 100){
    echo "Ошибка: Имя содержит слишком много символов, убедитесь, что вы все ввели верно.<br>";
    exit;
}

if (strlen($surname) > 100){
    echo "Ошибка: Фамилия содержит слишком много символов, убедитесь, что вы все ввели верно.<br>";
    exit;
}

if (strlen($number) > 10){
    echo "Ошибка: Номер содержит слишком много символов, убедитесь, что вы все ввели верно.<br>";
    exit;
}

  
  // Подготовленный запрос для вставки данных в таблицу пользователей
  $stmt = $conn->prepare("INSERT INTO users (name, surname, number, email, date, gender, about, document) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssissssi", $name, $surname, $number, $email, $date, $gender, $about, $document);
  
  // Выполнение запроса
  if ($stmt->execute()) {
      $last_id = $conn->insert_id; // Получение ID последней вставленной записи
  
      // Вставка выбранных языков программирования в отдельную таблицу
      $stmt_lang = $conn->prepare("INSERT INTO lang (user_id, language) VALUES (?, ?)");
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
  
  // Закрытие подключения
  $conn->close();
  ?>
