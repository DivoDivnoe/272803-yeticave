<table class="history__list">
    <?php foreach ($bets as $bet): ?>
        <tr class="history__item">
            <td class="history__name"><?= $bet['name'] ?></td>
            <td class="history__price"><?= $bet['price'] . ' р.' ?></td>
            <td class="history__time"><?= ts2relative($bet['ts']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>