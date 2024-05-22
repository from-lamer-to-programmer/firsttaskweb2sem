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
  $number = (!empty($_COOKIE['number_error']) ? $_COOKIE['number_error'] : '');
  $email = (!empty($_COOKIE['email_error']) ? $_COOKIE['email_error'] : '');
  $data = (!empty($_COOKIE['data_error']) ? strtotime($_COOKIE['data_error']) : '');
  $radio = (!empty($_COOKIE['radio_error']) ? $_COOKIE['radio_error'] : '');
  $lang = (!empty($_COOKIE['lang_error']) ? $_COOKIE['lang_error'] : '');
  $biography = (!empty($_COOKIE['biography_error']) ? $_COOKIE['biography_error'] : '');
  $check_mark = (!empty($_COOKIE['check_mark_error']) ? $_COOKIE['_error'] : '');

  $errors = array();
  $messages = array();
  $values = array();
  
  function val_empty($pName, $val){
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

  val_empty("name", $name);
  val_empty("number", $number);
  val_empty("email", $email);
  val_empty("data", $data);
  val_empty("radio", $radio);
  val_empty("lang", $lang);
  val_empty("biography", $biography);
  val_empty("check_mark",$check_mark);

  $langsarray = explode(',', $values['lang']);

  include('form.php');
}
//POST
else{ 
  $name = (!empty($_POST['name']) ? $_POST['name'] : '');
  $number = (!empty($_POST['number']) ? $_POST['number'] : '');
  $email = (!empty($_POST['email']) ? $_POST['email'] : '');
  $data = (!empty($_POST['data']) ? strtotime($_POST['data']) : '');
  $radio = (!empty($_POST['radio']) ? $_POST['radio'] : '');
  $lang = (!empty($_POST['lang']) ? $_POST['lang'] : '');
  $biography = (!empty($_POST['biography']) ? $_POST['biography'] : '');
  $check_mark = (!empty($_POST['check_mark']) ? $_POST['check_mark'] : '');
  $error = false;

  $number1 = preg_replace('/\D/', '', $number);

  function val_empty($cook, $comment, $usl){
    global $error;
    $res = false;
    $setVal = $_POST[$cook];
    if ($usl) {
      setcookie($cook.'_error', $comment, time() + 24 * 60 * 60); 
      $error = true;
      $res = true;
    }
    
    if($cook == 'lang'){
      global $lang;
      $setVal = ($lang != '') ? implode(",", $lang) : '';
    }
    
    setcookie($cook.'_value', $setVal, time() + 30 * 24 * 60 * 60); 
    return $res;
  }
  
  if(!val_empty('name', 'Заполните поле', empty($name))){
    if(!val_empty('name', 'Длина поля > 255 символов', strlen($name) > 255)){
      val_empty('name', 'Поле не соответствует требованиям: <i>Фаимлмя Имя (Отчество)</i>, кириллица', !preg_match('/^([а-яё]+-?[а-яё]+)( [а-яё]+-?[а-яё]+){1,2}$/Diu', $name));
    }
  }
  if(!val_empty('number', 'Заполните поле', empty($number))){
    if(!val_empty('number', 'Длина поля некорректна', strlen($number) != 11)){
      val_empty('number', 'Поле должен содержать только цифры"', ($number != $number1));
    }
  }
  if(!val_empty('email', 'Заполните поле', empty($email))){
    if(!val_empty('email', 'Длина поля > 255 символов', strlen($email) > 255)){
      val_empty('email', 'Поле не соответствует требованию example@mail.ru', !preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $email));
    }
  }
  if(!val_empty('data', "Выберите дату рождения", empty($data))){
    val_empty('data', "Неверно введена дата рождения, дата больше настоящей", (strtotime("now") < $data));
  }
  val_empty('radio', "Выберите пол", (empty($radio) || !preg_match('/^(m|f)$/', $radio)));
  if(!val_empty('lang', "Выберите хотя бы один язык", empty($lang))){
   
    try {
      $inQuery = implode(',', array_fill(0, count($lang), '?'));
      $dbLangs = $db->prepare("SELECT id, name FROM languages WHERE name IN ($inQuery)");
      foreach ($lang as $key => $value) {
        $dbLangs->bindValue(($key+1), $value);
      }
      $dbLangs->execute();
      $languages = $dbLangs->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();
    }
    
    val_empty('lang', 'Неверно выбраны языки', $dbLangs->rowCount() != count($lang));
  }
  if(!val_empty('biography', 'Заполните поле', empty($biography))){
    val_empty('biography', 'Длина текста > 65 535 символов', strlen($biography) > 65535);
  }
  val_empty('check_mark', "Ознакомьтесь с контрактом", empty($check_mark));
  
  if ($error) {
    header('Location: index.php');
    exit();
  }
  else {
    setcookie('name_error', '', time() - 30 * 24 * 60 * 60);
    setcookie('number_error', '', time() - 30 * 24 * 60 * 60);
    setcookie('email_error', '', time() - 30 * 24 * 60 * 60);
    setcookie('data_error', '', time() - 30 * 24 * 60 * 60);
    setcookie('radio_error', '', time() - 30 * 24 * 60 * 60);
    setcookie('lang_error', '', time() - 30 * 24 * 60 * 60);
    setcookie('biography_error', '', time() - 30 * 24 * 60 * 60);
    setcookie('check_mark_error', '', time() - 30 * 24 * 60 * 60);
    
  }
  
  try {
    $stmt = $db->prepare("INSERT INTO form_data (name, number, email, data, radio, biography) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $number, $email, $data, $radio, $biography]);
    $fid = $db->lastInsertId();
    $stmt1 = $db->prepare("INSERT INTO form_data_lang (id_form, id_lang) VALUES (?, ?)");
    foreach($languages as $row){
        $stmt1->execute([$fid, $row['id']]);
    }
  }
  catch(PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
  }
  setcookie('name_value', $name, time() + 24 * 60 * 60 * 365);
  setcookie('number_value', $number, time() + 24 * 60 * 60 * 365);
  setcookie('email_value', $email, time() + 24 * 60 * 60 * 365);
  setcookie('data_value', $data, time() + 24 * 60 * 60 * 365);
  setcookie('radio_value', $radio, time() + 24 * 60 * 60 * 365);
  setcookie('lang_value', implode(",", $lang), time() + 24 * 60 * 60 * 365);
  setcookie('biography_value', $biography, time() + 24 * 60 * 60 * 365);
  setcookie('check_mark_value', $check_mark, time() + 24 * 60 * 60 * 365);

  setcookie('save', '1');

  header('Location: index.php');
}
?>