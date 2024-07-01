<?php

header('Content-Type: text/html; charset=UTF-8');

$db;

include('database.php');

function isp($value){
  if(isset($value)) return $value;
  return;
}


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $name = (!empty($_COOKIE['name_error']) ? $_COOKIE['name_error'] : ''); 
  $surname = (!empty($_COOKIE['surname_error']) ? $_COOKIE['surname_error'] : '');
  $number = (!empty($_COOKIE['number_error']) ? $_COOKIE['number_error'] : '');
  $email = (!empty($_COOKIE['email_error']) ? $_COOKIE['email_error'] : '');
  $date = (!empty($_COOKIE['date_error']) ? strtotime($_COOKIE['date_error']) : '');
  $gender = (!empty($_COOKIE['gender_error']) ? $_COOKIE['gender_error'] : '');
  $selectedLangs = (!empty($_COOKIE['selectedLangs_error']) ? $_COOKIE['selectedLangs_error'] : '');
  $about = (!empty($_COOKIE['about_error']) ? $_COOKIE['about_error'] : '');
  $document = (!empty($_COOKIE['document_error']) ? $_COOKIE['document_error'] : '');

  $errors = array();
  $messages = array();
  $values = array();

    
  function value_empty($pName, $val){
    global $errors, $values, $messages;
    $errors[$pName] = !empty($_COOKIE[$pName.'_error']);
    $messages[$pName] = "<div class='messageError'>$val</div>";
    $values[$pName] = empty($_COOKIE[$pName.'_value']) ? '' : $_COOKIE[$pName.'_value'];
    setcookie($pName.'_error', '', time() - 30 * 24 * 60 * 60);
    return;
  }

  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    $messages['success'] = '<div class="message">Информация сохранена.</div>';
  }

  value_empty("name", $name);
  value_empty("surname", $surname);
  value_empty("number", $number);
  value_empty("email", $email);
  value_empty("date", $date);
  value_empty("gender", $gender);
  value_empty("selectedLangs", $selectedLangs);
  value_empty("about", $about);
  value_empty("document",$document);

  $langsarray = explode(',', $values['selectedLangs']);

  include('form.php');
}
// post
else {


    $name = (!empty($_POST['name']) ? $_POST['name'] : '');
    $surname = (!empty($_POST['surname']) ? $_POST['surname'] : '');
    $number = (!empty($_POST['number']) ? $_POST['number'] : '');
    $email = (!empty($_POST['email']) ? $_POST['email'] : '');
    $date = (!empty($_POST['date']) ? strtotime($_POST['date']) : '');
    $gender = (!empty($_POST['gender']) ? $_POST['gender'] : '');
    $selectedLangs = (!empty($_POST['selectedLangs']) ? $_POST['selectedLangs'] : '');
    $about = (!empty($_POST['about']) ? $_POST['about'] : '');
    $document = (!empty($_POST['document']) ? $_POST['document'] : '');
    $error = false;

    $number1 = preg_replace('/\D/', '', $number);

    function value_empty($cook, $comment, $usl){
      global $error;
      $res = false;
      $setVal = $_POST[$cook];
      if ($usl) {
        setcookie($cook.'_error', $comment, time() + 24 * 60 * 60); 
        $error = true;
        $res = true;
      }

    if($cook == 'selectedLangs'){
      global $selectedLangs;
      $setVal = ($selectedLangs != '') ? implode(",", $selectedLangs) : '';
    }

    setcookie($cook.'_value', $setVal, time() + 30 * 24 * 60 * 60);
    return $res;
  }


  if(!value_empty('name', 'Заполните поле', empty($name))){
    if(!value_empty('name', 'Длина поля > 75 символов', strlen($name) > 75)){
      value_empty('name', 'Поле не соответствует требованиям: <i>Имя</i>, кириллица', !preg_match('/^([а-яА-ЯёЁ]+-?[а-яА-ЯёЁ]+)( [а-яА-ЯёЁ]+-?[а-яА-ЯёЁ]+){0,2}$/u', $name));
    }
  }

  if(!value_empty('surname', 'Заполните поле', empty($surname))){
    if(!value_empty('surname', 'Длина поля > 75 символов', strlen($surname) > 75)){
      value_empty('surname', 'Поле не соответствует требованиям: <i>Фаимилия</i>, кириллица', !preg_match('/^([а-яА-ЯёЁ]+-?[а-яА-ЯёЁ]+)( [а-яА-ЯёЁ]+-?[а-яА-ЯёЁ]+){0,2}$/u', $surname));
    }
  }

 
  if(!value_empty('number', 'Заполните поле', empty($number))){
    if(!value_empty('number', 'Длина поля некорректна', strlen($number) != 11)){
      value_empty('number', 'Поле должен содержать только цифры"', ($number != $number1));
    }
  }


  if(!value_empty('email', 'Заполните поле', empty($email))){
    if(!value_empty('email', 'Длина поля > 255 символов', strlen($email) > 255)){
      value_empty('email', 'Поле не соответствует требованию example@mail.ru', !preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $email));
    }
  }
 
  if(!value_empty('date', "Выберите дату рождения", empty($date))){
    value_empty('date', "Неверно введена дата рождения, дата больше настоящей", (strtotime("now") < $date));
  }
 
  value_empty('gender', "Выберите пол", (empty($gender)));

 

  

  $allowedLangs = array("Pascal", "C", "C++", "JavaScript", "PHP", "Python", "Java", "Haskel", "Clojure", "Prolog", "Scara");
    if (empty($selectedLangs) || !is_array($selectedLangs) || count(array_diff($selectedLangs, $allowedLangs)) > 0) {
      value_empty('selectedLangs', "Выберите хотя бы один язык", 1);
    }


  



  if(!value_empty('about', 'Заполните поле', empty($about))){
    value_empty('about', 'Длина текста > 400 символов', strlen($about) > 400);
  }
  value_empty('document', "Ознакомьтесь с контрактом", empty($document));

  if ($error) {
    
    header('Location: index.php');
    exit();

  }

  else {
    setcookie('name_error', '', time() - 30 * 24 * 60 * 60);
    setcookie('surname_error', '', time() - 30 * 24 * 60 * 60);
    setcookie('number_error', '', time() - 30 * 24 * 60 * 60);
    setcookie('email_error', '', time() - 30 * 24 * 60 * 60);
    setcookie('date_error', '', time() - 30 * 24 * 60 * 60);
    setcookie('gender_error', '', time() - 30 * 24 * 60 * 60);
    setcookie('allowedLangs_error', '', time() - 30 * 24 * 60 * 60);
    setcookie('about_error', '', time() - 30 * 24 * 60 * 60);
    setcookie('document_error', '', time() - 30 * 24 * 60 * 60);

  }

  try {
    $stmt = $db->prepare("INSERT INTO Users (name, surname, number, email, date, gender, about, document)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $surname, $number, $email, $date, $gender, $about, $document]);
    $fid = $db->lastInsertId();
    $stmt1 = $db->prepare("INSERT INTO UserLanguages (user_id, language_id) VALUES (?, ?)");
    try {
      $inQuery = implode(',', array_fill(0, count($selectedLangs), '?'));
      $dbLangs = $db->prepare("SELECT id, language_name FROM languages WHERE language_name IN ($inQuery)");
      foreach ($selectedLangs as $key => $value) {
        $dbLangs->bindValue(($key+1), $value);
      }
      $dbLangs->execute();
      $languages = $dbLangs->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();
    }
    foreach($languages as $row){
        $stmt1->execute([$fid, $row['id']]);
    }
  }
  catch(PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
  }
  
  setcookie('name_value', $name, time() + 24 * 60 * 60 * 365);
  setcookie('surname_value', $surname, time() + 24 * 60 * 60 * 365);
  setcookie('number_value', $number, time() + 24 * 60 * 60 * 365);
  setcookie('email_value', $email, time() + 24 * 60 * 60 * 365);
  setcookie('date_value', $date, time() + 24 * 60 * 60 * 365);
  setcookie('gender_value', $gender, time() + 24 * 60 * 60 * 365);
  setcookie('allowedLangs_value', implode(",", $allowedLangs), time() + 24 * 60 * 60 * 365);
  setcookie('about_value', $about, time() + 24 * 60 * 60 * 365);
  setcookie('document_value', $document, time() + 24 * 60 * 60 * 365);

  setcookie('save', '1');

  header('Location: index.php');
}

?>
