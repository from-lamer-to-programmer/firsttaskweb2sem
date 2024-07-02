<?php

header('Content-Type: text/html; charset=UTF-8');
session_start();
if(strpos($_SERVER['REQUEST_URI'], 'index.php') === false){
  header('Location: index.php');
  exit();
}

$log = !empty($_SESSION['login']);
$adminLog = !empty($_SERVER['PHP_AUTH_USER']);
$uid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$getUid = isset($_GET['uid']) ? strip_tags($_GET['uid']) : '';

if($adminLog){
  if(preg_match('/^[0-9]+$/', $getUid)){
    $uid = $getUid;
    $log = true;
  }
}

$db;

include('database.php');
function isp($value){
  if(isset($value)) return $value;
  return;
}

function del_cook($cook, $vals = 0){
  setcookie($cook.'_error', '', 100000);
  if($vals) setcookie($cook.'_value', '', 100000);
}


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  if(($adminLog && !empty($getUid)) || !$adminLog){
    $cookAdmin = (!empty($_COOKIE['admin_value']) ? $_COOKIE['admin_value'] : '');
    if($cookAdmin == '1'){
      del_cook('name', 1);
      del_cook('surname', 1);
      del_cook('number', 1);
      del_cook('email', 1);
      del_cook('data', 1);
      del_cook('gender', 1);
      del_cook('selectedLangs', 1);
      del_cook('about', 1);
      del_cook('document', 1);
      del_cook('admin', 1);
    }
  }
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
  $error = true;

  function setVal($enName, $param){
    global $values;
    $values[$enName] = empty($param) ? '' : strip_tags($param);
  }
    
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
    setcookie('login', '', 100000);
    setcookie('password', '', 100000);
    $messages['success'] = 'Данные сохранены';
    if (!empty($_COOKIE['password'])) {
      $messages['info'] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['password']));
    }
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

  $langsa = explode(',', $values['selectedLangs']);

  if ($error && $log ) {
    try {
      $dbFD = $db->prepare("SELECT * FROM Users WHERE id = ?");
      $dbFD->execute([$_SESSION['user_id']]);
      $fet = $dbFD->fetchAll(PDO::FETCH_ASSOC)[0];
      $user_id = $fet['id'];
      $_SESSION['user_id'] = $user_id;
      $dbL = $db->prepare("SELECT l.language_name FROM UserLanguages fdl
                            JOIN Languages l ON l.id = fdl.language_id
                            WHERE user_id = ?");
      $dbL->execute([$user_id]);
      $langsa = [];
      foreach($dbL->fetchAll(PDO::FETCH_ASSOC) as $item){
        $langsa[] = $item['name'];
      }
      setVal('name', $fet['name']);
      setVal('number', $fet['number']);
      setVal('email', $fet['email']);
      setVal('data', date("Y-m-d", $fet['data']));
      setVal('radio', $fet['radio']);
      setVal('lang', $lang);
      setVal('biography', $fet['biography']);
      setVal('check_mark', '1');
    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();
    }
  }
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

    if(isset($_POST['logout_form'])){
    if($adminLog && empty($_SESSION['login'])){
      header('Location: admin.php');
    }
  
    else{
      del_cook('name', 1);
      del_cook('surname', 1);
      del_cook('number', 1);
      del_cook('email', 1);
      del_cook('data', 1);
      del_cook('gender', 1);
      del_cook('selectedLangs', 1);
      del_cook('about', 1);
      del_cook('document', 1);
      session_destroy();
      header('Location: index.php'.(($getUid != NULL) ? '?uid='.$uid : ''));
    }
      exit();
    } 
    

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
    if ($error) {
    
      header('Location: index.php');
      exit();
  
    }

    try {
      $inQuery = implode(',', array_fill(0, count($selectedLangs), '?'));
      $dbLangs = $db->prepare("SELECT id, language_name FROM Languages WHERE language_name IN ($inQuery)");
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



  if(!value_empty('about', 'Заполните поле', empty($about))){
    value_empty('about', 'Длина текста > 400 символов', strlen($about) > 400);
  }
  value_empty('document', "Ознакомьтесь с контрактом", empty($document));

  if ($error) {
    
    header('Location: index.php');
    exit();

  }

  else {
    del_cook('name');
      del_cook('surname');
      del_cook('number');
      del_cook('email');
      del_cook('data');
      del_cook('gender');
      del_cook('selectedLangs');
      del_cook('about');
      del_cook('document');
  }

  // Проверяем меняются ли ранее сохраненные данные или отправляются новые.
  if ($log) { 
      
    $stmt = $db->prepare("UPDATE Users SET name=?, surname=?, number=?, email=?, date=?, gender=?, about=?, document=? WHERE id = ?");
    $stmt->execute([$name, $surname, $number, $email, $date, $gender, $about, $document, $_SESSION['user_id']]);
    var_dump ($data);
    print_r($db->errorInfo());

    $stmt = $db->prepare("DELETE FROM UserLanguages WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);

    $stmt1 = $db->prepare("INSERT INTO UserLanguages (user_id, language_id) VALUES (?, ?)");
    foreach($languages as $row){
        $stmt1->execute([$_SESSION['user_id'], $row['id']]);
    }

    if($adminLog) 
        setcookie('admin_value', '1', time() + 30 * 24 * 60 * 60);
    // TODO: перезаписать данные в БД новыми данными,
    // кроме логина и пароля.
  }
  else {
    echo '1';
    // Генерируем уникальный логин и пароль.
    // TODO: сделать механизм генерации, например функциями rand(), uniquid(), md5(), substr().
    $login = substr(uniqid(), 0, 4).rand(10, 100);
    $password = rand(100, 1000).substr(uniqid(), 4, 10);
    // Сохраняем в Cookies.
    setcookie('login', $login);
    setcookie('password', $password);
    $mpassword = md5($password);



  try {
    $stmt=$db->prepare("INSERT INTO user(login, password) VALUES (?, ?)");
    $stmt->execute([$login, md5($password) ]);


    $stmt = $db->prepare("INSERT INTO Users (name, surname, number, email, date, gender, about, document)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $surname, $number, $email, $date, $gender, $about, $document]);
    $fid = $db->lastInsertId();
    $stmt1 = $db->prepare("INSERT INTO UserLanguages (user_id, language_id) VALUES (?, ?)");


    
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
  }
  setcookie('save', '1');

  header('Location: index.php');
}

?>