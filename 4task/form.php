<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="index4.css" rel="stylesheet" type="text/css" />
    <title>Задание №4 Курносов</title>
  </head>
  <body class="m-4">


    <form class="popup" action="" method="post">
    <div class="message"><?php if(isset($messages['success'])) echo $messages['success']; ?></div>
    <div>
          <label>
            <input class="input <?php echo (isp($errors['name']) != NULL) ? 'borred' : ''; ?>" value="<?php echo isp($values['name']); ?>" type="text" name="name" placeholder="Ф.И.О">
            <div class="errpodinp"><?php echo $messages['name']?></div>
          </label>
        </div>

        <div>
          <label>
            <input class="input <?php echo (isp($errors['number']) != NULL) ? 'borred' : ''; ?>" value="<?php echo isp($values['number']); ?>" type="tel" name="number" placeholder="Номер телефона">
            <div class="errpodinp"><?php echo $messages['number']?></div>
          </label>
        </div>

        <div>
          <label>
            <input class="input <?php echo (isp($errors['email']) != NULL) ? 'borred' : ''; ?>" value="<?php echo isp($values['email']); ?>" type="email" name="email" placeholder="Введите почту">
            <div class="errpodinp"><?php echo $messages['email']?></div>

          </label>
        </div>

        <div>
          <label>
            <input class="input <?php echo (isp($errors['data']) != NULL) ? 'borred' : ''; ?>" value="<?php echo isp($values['data']); ?>" type="date" name="data">
            <div class="errpodinp"><?php echo $messages['data']?></div>
          </label>
        </div>

        <div class="my-3">
          Пол
          <br />
          <div class="my-2">
            <label>
              <input
                class="ml-3 <?php echo (isp($errors['radio']) != NULL) ? 'colred' : ''; ?>"
                type="radio"
                name="radio"
                value="m"
                checked
              />М
            </label>
            <label>
              <input
                class="ml-3 <?php echo (isp($errors['radio']) != NULL) ? 'colred' : ''; ?>"
                type="radio"
                name="radio"
                value="f"
              />Ж
            </label>
          </div>
        </div>

        <div>
          <label class="input">
            Любимый язык программирования<br />
            <select  id="lang" class="my-2 <?php echo (isp($errors['lang']) != NULL) ? 'borred' : ''; ?>"  name="lang[]" multiple="multiple">
              <option value="Pascal" <?php echo (in_array('Pascal', $langsarray)) ? 'selected' : ''; ?>>Pascal</option>
              <option value="C" <?php echo (in_array('C', $langsarray)) ? 'selected' : ''; ?>>C</option>
              <option value="C++" <?php echo (in_array('C++', $langsarray)) ? 'selected' : ''; ?>>C++</option>
              <option value="JavaScript" <?php echo (in_array('JavaScript', $langsarray)) ? 'selected' : ''; ?>>JavaScript</option>
              <option value="PHP" <?php echo (in_array('PHP', $langsarray)) ? 'selected' : ''; ?>>PHP</option>
              <option value="Python" <?php echo (in_array('Python', $langsarray)) ? 'selected' : ''; ?>>Python</option>
              <option value="Java" <?php echo (in_array('Java', $langsarray)) ? 'selected' : ''; ?>>Java</option>
              <option value="Haskel" <?php echo (in_array('Haskel', $langsarray)) ? 'selected' : ''; ?>>Haskel</option>
              <option value="Clojure" <?php echo (in_array('Clojure', $langsarray)) ? 'selected' : ''; ?>>Clojure</option>
              <option value="Scala" <?php echo (in_array('Scala', $langsarray)) ? 'selected' : ''; ?>>Scala</option>
            </select>
            <div class="errpodinp"><?php echo $messages['lang']?></div>
          </label>
        </div>

        <div>
          <br/>
          Биография <br />
          <label>
            <textarea name="biography" placeholder="Биография" class="input <?php echo (isp($errors['biography']) != NULL) ? 'borred' : ''; ?>"><?php echo isp($values['biography']); ?></textarea>
            <div class="errpodinp"><?php echo $messages['biography']?></div>
          </label>
        </div>

      
        <div >
            <input type="checkbox" name="check_mark" id="oznakomlen" <?php echo ( isp($values['check_mark']) != NULL) ? 'checked' : ''; ?>>
            <label for="oznakomlen" class="<?php echo (isp($errors['check_mark']) != NULL) ? 'colred' : ''; ?>">С контрактом ознакомлен (а)</label>
            <div class="errpodinp"><?php echo $messages['check_mark']?></div>
        </div>

        <button type="submit" class="form_button my-3">Отправить</button>
      </div>
    </form>
</body>
</html>