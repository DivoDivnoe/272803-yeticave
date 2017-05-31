<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <?php foreach ($categories as $index => $category): ?>
                <li class="promo__item promo__item--<?= $classes[$index] ?>">
                    <a class="promo__link" href="all_lots.php?category_id=<?= $category['id'] ?>"><?= $category['name'] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
            <select class="lots__select">
                <option>Все категории</option>
                <?php foreach ($categories as $category): ?>
                    <option><?= $category['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <ul class="lots__list">
            <?php foreach ($equip_items as $index => $item): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= $item['image'] ?>" width="350" height="260" alt="<?= htmlspecialchars($item['title']) ?>">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= $item['name'] ?></span>
                        <h3 class="lot__title"><a class="text-link" href="lot.php?lot_id=<?= $item['id'] ?>"><?= htmlspecialchars($item['title']) ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= $item['start_price'] ?><b class="rub">р</b></span>
                            </div>
                            <div class="lot__timer timer">
                                <?= show_left_time(htmlspecialchars($item['expire'])); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>