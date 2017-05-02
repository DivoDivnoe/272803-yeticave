<main>
    <nav class="nav">
        <ul class="nav__list container">
            <li class="nav__item">
                <a href="all-lots.html">Доски и лыжи</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Крепления</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Ботинки</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Одежда</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Инструменты</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Разное</a>
            </li>
        </ul>
    </nav>
    <form class="form form--add-lot container <?= $valid ?>" action="../add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item <?= $title['class'] ?>"> <!-- form__item--invalid -->
                <label for="lot-name">Наименование</label>
                <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" required>
                <span class="form__error"><?= $title['error'] ?></span>
            </div>
            <div class="form__item <?= $category['class'] ?>">
                <label for="category">Категория</label>
                <select id="category" name="category" required>
                    <option>Выберите категорию</option>
                    <option>Доски и лыжи</option>
                    <option>Крепления</option>
                    <option>Ботинки</option>
                    <option>Одежда</option>
                    <option>Инструменты</option>
                    <option>Разное</option>
                </select>
                <span class="form__error"><?= $category['error'] ?></span>
            </div>
        </div>
        <div class="form__item form__item--wide <?= $message['class'] ?>">
            <label for="message">Описание</label>
            <textarea id="message" name="message" placeholder="Напишите описание лота" required></textarea>
            <span class="form__error"><?= $message['error'] ?></span>
        </div>
        <div class="form__item form__item--file"> <!-- form__item--uploaded -->
            <label>Изображение</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="../img/avatar.jpg" width="113" height="113" alt="Изображение лота">
                </div>
            </div>
            <div class="form__input-file <?= $user_file['class'] ?>">
                <input class="visually-hidden" type="file" id="photo2" value="" name="user_file">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
                <span class="form__error"><?= $user_file['error'] ?></span>
            </div>
        </div>
        <div class="form__container-three">
            <div class="form__item form__item--small <?= $lot_rate['class'] ?>">
                <label for="lot-rate">Начальная цена</label>
                <input id="lot-rate" type="number" name="lot-rate" placeholder="0" required>
                <span class="form__error"><?= $lot_rate['error'] ?></span>
            </div>
            <div class="form__item form__item--small <?= $lot_step['class'] ?>">
                <label for="lot-step">Шаг ставки</label>
                <input id="lot-step" type="number" name="lot-step" placeholder="0" required>
                <span class="form__error"><?= $lot_step['error'] ?></span>
            </div>
            <div class="form__item <?= $lot_date['class'] ?>">
                <label for="lot-date">Дата заверщения</label>
                <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="20.05.2017" required>
                <span class="form__error"><?= $lot_date['error'] ?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>