<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $index => $category): ?>
                <li class="nav__item">
                    <a href="all_lots.php?category_id=<?= $category['id'] ?>"><?= $category['name'] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <form class="form container <?= $form_class ?>" action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="post"
          enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Регистрация нового аккаунта</h2>
        <div class="form__item <?= $email['class'] ?>"> <!-- form__item--invalid -->
            <label for="email">E-mail*</label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $email['value'] ?>">
            <span class="form__error"><?= $email['error'] ?></span>
        </div>
        <div class="form__item <?= $password['class'] ?>">
            <label for="password">Пароль*</label>
            <input id="password" type="text" name="password" placeholder="Введите пароль"
                   value="<?= $password['value'] ?>">
            <span class="form__error"><?= $password['error'] ?></span>
        </div>
        <div class="form__item <?= $name['class'] ?>">
            <label for="name">Имя*</label>
            <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= $name['value'] ?>">
            <span class="form__error"><?= $name['error'] ?></span>
        </div>
        <div class="form__item <?= $contacts['class'] ?>">
            <label for="message">Контактные данные*</label>
            <textarea id="message" name="message"
                      placeholder="Напишите как с вами связаться"><?= $contacts['value'] ?></textarea>
            <span class="form__error"><?= $contacts['error'] ?></span>
        </div>
        <div class="form__item form__item--file form__item--last">
            <label>Изображение</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="../img/avatar.jpg" width="113" height="113" alt="Изображение лота">
                </div>
            </div>
            <div class="form__input-file <?= $user_file['class'] ?>">
                <input class="visually-hidden" type="file" id="photo2" name="user_file">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
                <span class="form__error"><?= $user_file['error'] ?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button" name="submit">Зарегистрироваться</button>
        <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>
</main>