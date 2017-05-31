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
    <div class="container">
        <section class="lots">
            <section class="lots">
                <h2>Все лоты в категории <span>&laquo;<?= $categories[$category_id - 1]['name'] ?>&raquo;</span></h2>
                    <ul class="lots__list">
                        <?php foreach ($lots as $index => $item): ?>
                            <li class="lots__item lot">
                                <div class="lot__image">
                                    <img src="<?= $item['image'] ?>" width="350" height="260" alt="<?= htmlspecialchars($item['title']) ?>">
                                </div>
                                <div class="lot__info">
                                    <span class="lot__category"><?= $item['name'] ?></span>
                                    <h3 class="lot__title"><a class="text-link" href="lot.php?lot_id=<?= $item['id'] ?>"><?= htmlspecialchars($item['title']) ?></a></h3>
                                    <div class="lot__state">
                                        <div class="lot__rate">
                                            <span class="lot__amount"><?= $item['num_of_bets'] ? format_bets_string($item['num_of_bets']): $item['start_price'] ?></span>
                                            <span class="lot__cost"><?= $item['start_price'] ?><b class="rub">р</b></span>
                                        </div>
                                        <div class="lot__timer timer <?= strtotime($item['expire']) < 3600 ? 'timer--finishing' : ''?>">
                                            <?= show_left_time(htmlspecialchars($item['expire'])); ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
            </section>
            <ul class="pagination-list">
                <li class="pagination-item pagination-item-prev"><a <?= $page - 1 ? 'href="all_lots.php?page=' . ($page - 1) . "&category_id=$category_id" . '"' : '' ?>>Назад</a></li>
                <?php for ($i = 1; $i <= $num_of_pages; $i++): ?>
                    <li class="pagination-item <?= intval($page) === $i ? 'pagination-item-active' : ''?>"><a <?= intval($page) === $i ? '' : "href=\"{$_SERVER['SCRIPT_NAME']}?page=" . $i . "&category_id=$category_id" . '"'?>><?= $i ?></a></li>
                <?php endfor; ?>
                <li class="pagination-item pagination-item-next"><a <?= $page < $num_of_pages ? 'href="all_lots.php?page=' . ($page + 1) . "&category_id=$category_id" . '"' : '' ?>>Вперед</a></li>
            </ul>
    </div>
</main>