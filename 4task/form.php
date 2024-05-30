<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurnosov 4 task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="skript.js" defer></script>
</head>
<body>
    <h1 class="text-center mt-5">Форма записи в базу данных</h1>



    <form action="index.php" method="POST" class="mx-auto mt-5" style="max-width: 600px;">
    <div class="message"><?php if(isset($messages['success'])) echo $messages['success']; ?></div>
        <div class="info">
            <div class="mb-3">
            <label>
                <input class="input <?php echo (isp($errors['name']) != NULL) ? 'is-invalid' : ''; ?>" value="<?php echo isp($values['name']); ?>" type="text" name="name" placeholder="Имя">
                <div class="invalid-feedback"><?php echo $messages['name']?></div>
              </label>
            </div>

            <div class="mb-3">
            <input class="form-control input <?php echo (isset($errors['surname']) && $errors['surname'] !== '') ? 'is-invalid' : ''; ?>" value="<?php echo isp($values['surname']); ?>" type="text" name="surname" placeholder="Фамилия">
                <div class="invalid-feedback">
                        <?php echo $errors['surname'] ?? ''; ?>
                </div>
            </div>

            <div class="mb-3">
    <input name="number" id="number" type="text" class="form-control <?php echo (isset($errors['number']) && $errors['number'] !== '') ? 'is-invalid' : ''; ?>" placeholder="Номер" required>
        <div class="invalid-feedback">
            <?php echo $errors['number'] ?? ''; ?>
        </div>
    </div>

    <div class="mb-3">
        <input name="email" id="email" type="email" class="form-control <?php echo (isset($errors['email']) && $errors['email'] !== '') ? 'is-invalid' : ''; ?>" placeholder="Почта" required>
        <div class="invalid-feedback">
                <?php echo $errors['email'] ?? ''; ?>
        </div>
    </div>

    <div class="mb-3">
        <input name="date" id="date" type="date" class="form-control <?php echo (isset($errors['date']) && $errors['date'] !== '') ? 'is-invalid' : ''; ?>" required>
            <div class="invalid-feedback">
                <?php echo $errors['date'] ?? ''; ?>
            </div>
    </div>

                <h4>Выберите пол:</h4>
        <div class="form-check">
            <input name="gender" value="1" type="radio" class="form-check-input <?php echo (isset($errors['gender']) && $errors['gender'] !== '') ? 'is-invalid' : ''; ?>" id="male" required>
            <label class="form-check-label" for="male">Мужчина</label>
            <div class="invalid-feedback">
                <?php echo $errors['gender'] ?? ''; ?>
            </div>
        </div>
        <div class="form-check">
            <input name="gender" value="2" type="radio" class="form-check-input <?php echo (isset($errors['gender']) && $errors['gender'] !== '') ? 'is-invalid' : ''; ?>" id="female" required>
            <label class="form-check-label" for="female">Женщина</label>
            <div class="invalid-feedback">
                <?php echo $errors['gender'] ?? ''; ?>
            </div>
        </div>

        <div class="mb-3">
            <h4>Выберите язык программирования:</h4>
                <select multiple name="langs[]" class="form-select <?php echo (isset($errors['selectedLangs']) && $errors['selectedLangs'] !== '') ? 'is-invalid' : ''; ?>" required>
                <option value="Pascal">Pascal</option>
                <option value="C">C</option>
                    <option value="C++">C++</option>
                    <option value="JavaScript">JavaScript</option>
                    <option value="PHP">PHP</option>
                    <option value="Python">Python</option>
                    <option value="Java">Java</option>
                    <option value="Haskel">Haskel</option>
                    <option value="Clojure">Clojure</option>
                    <option value="Prolog">Prolog</option>
                    <option value="Scara">Scara</option>
                </select>
            </div>
            <div class="mb-3">
    <h4>Напишите о себе:</h4>
        <textarea name="about" class="form-control <?php echo (isset($errors['about']) && $errors['about'] !== '') ? 'is-invalid' : ''; ?>" cols="30" rows="8" required></textarea>
        <div class="invalid-feedback">
            <?php echo $errors['about'] ?? ''; ?>
        </div>
    </div>

    <div class="mb-3 form-check">
        <input class="form-check-input <?php echo (isset($errors['document']) && $errors['document'] !== '') ? 'is-invalid' : ''; ?>" type="checkbox" name="document" id="document" required>
        <label class="form-check-label" for="document">Я согласен(а) с условиями <a href="#">конфиденциальности</a></label>
        <div class="invalid-feedback">
            <?php echo $errors['document'] ?? ''; ?>
        </div>
    </div>

<button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </form>
</body>
</html>
