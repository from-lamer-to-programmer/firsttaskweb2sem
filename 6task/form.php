<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurnosov 4 task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <h1 class="text-center mt-5">Форма записи в базу данных</h1>



    <form action="" method="POST" class="mx-auto mt-5" style="max-width: 600px;">
    <div class="message"><?php if(isset($messages['success'])) echo $messages['success']; ?></div>
    <div class="message message_info"><?php if(isset($messages['info'])) echo $messages['info']; ?></div>
        <div class="info">
            <div class="mb-3">
            <input class="form-control input <?php echo (isset($errors['name']) && !empty($errors['name'])) ? 'is-invalid' : ''; ?>" value="<?php echo isp($values['name']); ?>" type="text" name="name" placeholder="Имя">
                <div class="invalid-feedback">
                        <?php echo $messages['name'] ?? ''; ?>
                </div>
            </div>

            <div class="mb-3">
            <input class="form-control input <?php echo (isset($errors['surname']) && !empty($errors['surname'])) ? 'is-invalid' : ''; ?>" value="<?php echo isp($values['surname']); ?>" type="text" name="surname" placeholder="Фамилия">
                <div class="invalid-feedback">
                        <?php echo $messages['surname'] ?? ''; ?>
                </div>
            </div>

            <div class="mb-3">
    <input name="number" id="number" type="text" class="form-control input <?php echo (isset($errors['number']) && !empty($errors['number'])) ? 'is-invalid' : ''; ?>" value="<?php echo isp($values['number']); ?>" placeholder="Номер" required>
        <div class="invalid-feedback">
            <?php echo $messages['number'] ?? ''; ?>
        </div>
    </div>

    <div class="mb-3">
        <input name="email" id="email" type="email" class="form-control <?php echo (isset($errors['email']) && !empty($errors['email'])) ? 'is-invalid' : ''; ?>" value="<?php echo isp($values['email']); ?>" placeholder="Почта" required>
        <div class="invalid-feedback">
                <?php echo $messages['email'] ?? ''; ?>
        </div>
    </div>

    <div class="mb-3">
    <label>
            <input class="input <?php echo (isp($errors['date']) != NULL) ? 'borred' : ''; ?>" value="<?php echo isp($values['date']); ?>" type="date" name="date">
            <div class="errpodinp"><?php echo $messages['date']?></div>
          </label>
    </div>

    <h4>Выберите пол:</h4>
<div class="form-check">
    <input name="gender" value="1" type="radio" class="form-check-input <?php echo (isset($errors['gender']) && !empty($errors['gender'])) ? 'is-invalid' : ''; ?>" id="male" <?php if($values['gender'] == '1') echo 'checked'; ?> required>
    <label class="form-check-label" for="male">Мужчина</label>
    <div class="invalid-feedback">
        <?php echo $messages['gender'] ?? ''; ?>
    </div>
</div>
<div class="form-check">
    <input name="gender" value="2" type="radio" class="form-check-input <?php echo (isset($errors['gender']) && !empty($errors['gender'])) ? 'is-invalid' : ''; ?>" id="female" <?php if($values['gender'] == '2') echo 'checked'; ?> required>
    <label class="form-check-label" for="female">Женщина</label>
    <div class="invalid-feedback">
        <?php echo $messages['gender'] ?? ''; ?>
    </div>
</div>

        <div>
          <label class="input">
            Любимый язык программирования<br />
            <select  id="selectedLangs" class="my-2 <?php echo (isp($errors['selectedLangs']) != NULL) ? 'borred' : ''; ?>"  name="selectedLangs[]" multiple="multiple">
              <option value="Pascal" <?php echo (in_array('Pascal', $langsa)) ? 'selected' : ''; ?>>Pascal</option>
              <option value="C" <?php echo (in_array('C', $langsa)) ? 'selected' : ''; ?>>C</option>
              <option value="C++" <?php echo (in_array('C++', $langsa)) ? 'selected' : ''; ?>>C++</option>
              <option value="JavaScript" <?php echo (in_array('JavaScript', $langsa)) ? 'selected' : ''; ?>>JavaScript</option>
              <option value="PHP" <?php echo (in_array('PHP', $langsa)) ? 'selected' : ''; ?>>PHP</option>
              <option value="Python" <?php echo (in_array('Python', $langsa)) ? 'selected' : ''; ?>>Python</option>
              <option value="Java" <?php echo (in_array('Java', $langsa)) ? 'selected' : ''; ?>>Java</option>
              <option value="Haskel" <?php echo (in_array('Haskel', $langsa)) ? 'selected' : ''; ?>>Haskel</option>
              <option value="Clojure" <?php echo (in_array('Clojure', $langsa)) ? 'selected' : ''; ?>>Clojure</option>
              <option value="Scala" <?php echo (in_array('Scara', $langsa)) ? 'selected' : ''; ?>>Scala</option>
            </select>
            <div class="errpodinp"><?php echo $messages['selectedLangs']?></div>
          </label>
        </div>
            <div class="mb-3">
    <h4>Напишите о себе:</h4>
        <textarea name="about" class="form-control" cols="30" rows="8" required><?php echo isp($values['about']); ?></textarea>
        <div class="invalid-feedback">
             <?php echo $messages['about'] ?? ''; ?>
        </div>
    </div>

    <div >
            <input type="checkbox" name="document"  <?php echo ( isp($values['document']) != NULL) ? 'checked' : ''; ?>>
            <label for="document" class="<?php echo (isp($errors['document']) != NULL) ? 'colred' : ''; ?>">С контрактом ознакомлен (а)</label>
            <div class="errpodinp"><?php echo $messages['document']?></div>
        </div>

        <?php
            if($log) echo '<button type="submit" class="form_button">Изменить</button>';
            else echo '<button type="submit" class="form_button">Отправить</button>';
        ?>

    <div class="mt-3">
      <?php 
            if($log) echo '<button type="submit" class="logout_form" name="logout_form">Выйти</button>'; 
            else echo '<a href="login.php" class="form_button" name="logout_form">Войти</a>';
        ?>
      </div>
        </div>


    </form>
</body>
</html>
