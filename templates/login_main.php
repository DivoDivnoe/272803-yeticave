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
    <form class="form container <?= $form_class ?>" action="login.php" method="post"> <!-- form--invalid -->
        <h2>Вход</h2>
        <div class="form__item <?= $email['class'] ?>"> <!-- form__item--invalid -->
            <label for="email">E-mail*</label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $email['value'] ?>">
            <span class="form__error"><?= $email['error'] ?></span>
        </div>
        <div class="form__item form__item--last <?= ($auth ? $auth['class'] : $pass['class']) ?>">
            <label for="password">Пароль*</label>
            <input id="password" type="text" name="password" placeholder="Введите пароль" value="<?= $pass['value'] ?>">
            <span class="form__error"><?= ($auth ? $auth['error'] : 'Введите пароль') ?></span>
        </div>
        <button type="submit" class="button" name="submit">Войти</button>
    </form>
</main>
