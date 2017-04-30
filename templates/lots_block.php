<ul class="lots__list">
    <?php foreach ($data[1] as $item): ?>
        <li class="lots__item lot">
            <div class="lot__image">
                <img src="<?= $item['url'] ?>" width="350" height="260" alt="Сноуборд">
            </div>
            <div class="lot__info">
                <span class="lot__category"><?= $item['category'] ?></span>
                <h3 class="lot__title"><a class="text-link" href=""><?= $item['title'] ?></a></h3>
                <div class="lot__state">
                    <div class="lot__rate">
                        <span class="lot__amount">Стартовая цена</span>
                        <span class="lot__cost"><?= $item['price'] ?><b class="rub">р</b></span>
                    </div>
                    <div class="lot__timer timer">
                        <?=$data[2];?>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>