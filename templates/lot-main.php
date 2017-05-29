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
    <section class="lot-item container">
        <h2><?= $equip_item['title'] ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?= $equip_item['image'] ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= $equip_item['name'] ?></span></p>
                <p class="lot-item__description"><?= htmlspecialchars($equip_item['description']) ?></p>
            </div>
            <div class="lot-item__right">
                <?php if ($is_auth && !$my_bet && !$is_my_lot): ?>
                    <div class="lot-item__state">
                        <div class="lot-item__timer timer">
                            <?= show_left_time(htmlspecialchars($equip_item['expire'])); ?>
                        </div>
                        <div class="lot-item__cost-state">
                            <div class="lot-item__rate">
                                <span class="lot-item__amount">Текущая цена</span>
                                <span class="lot-item__cost"><?= $equip_item['price'] ?></span>
                            </div>
                            <div class="lot-item__min-cost">
                                Мин. ставка <span><?= $equip_item['price'] + $equip_item['step'] ?> р</span>
                            </div>
                        </div>
                        <form class="lot-item__form" action="lot.php?lot_id=<?= $equip_item['id'] ?>" method="post">
                            <p class="lot-item__form-item <?= $cost['class'] ?>">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="number" name="cost" min="<?= $equip_item['price'] + $equip_item['step'] ?>" placeholder="<?= $equip_item['price'] + $equip_item['step'] ?>">
                                <span class="form__error"><?= $cost['error'] ?></span>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    </div>
                <?php endif; ?>
                <div class="history">
                    <h3>История ставок</h3>
                    <!-- заполните эту таблицу данными из массива $bets-->
                    <table class="history__list">
                        <?php foreach ($bets as $bet): ?>
                            <tr class="history__item">
                                <td class="history__name"><?= htmlspecialchars($bet['name']) ?></td>
                                <td class="history__price"><?= $bet['sum'] . ' р.' ?></td>
                                <td class="history__time"><?= ts_2_relative(strtotime($bet['date'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>