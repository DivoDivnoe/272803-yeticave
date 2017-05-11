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
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach ($my_bets as $id => $bet): ?>
                <tr class="rates__item">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="../img/rate<?= $id + 1 ?>.jpg" width="54" height="40" alt="Сноуборд">
                        </div>
                        <h3 class="rates__title"><a href="lot.php?lot_id=<?= $id ?>"><?= $equip_items[$id]['title'] ?></a></h3>
                    </td>
                    <td class="rates__category">
                        <?= $equip_items[$id]['category'] ?>
                    </td>
                    <td class="rates__timer">
                        <div class="timer timer--finishing">07:13:34</div>
                    </td>
                    <td class="rates__price">
                        <?= $bet['cost'] ?>
                    </td>
                    <td class="rates__time">
                        <?= ts2relative($bet['date']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>