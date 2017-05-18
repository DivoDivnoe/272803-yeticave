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
            <?php foreach ($my_bets as $bet): ?>
                <tr class="rates__item">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="../img/rate<?= $bet['id'] ?>.jpg" width="54" height="40" alt="<?= $bet['title'] ?>">
                        </div>
                        <h3 class="rates__title"><a href="lot.php?lot_id=<?= $bet['id'] ?>"><?= $bet['title'] ?></a></h3>
                    </td>
                    <td class="rates__category">
                        <?= $bet['name'] ?>
                    </td>
                    <td class="rates__timer">
                        <div class="timer timer--finishing"><?= show_left_time(htmlspecialchars($bet['expire'])) ?></div>
                    </td>
                    <td class="rates__price">
                        <?= $bet['sum'] ?>
                    </td>
                    <td class="rates__time">
                        <?= ts2relative(strtotime($bet['date'])) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>