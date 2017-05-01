<select class="lots__select">
    <option>Все категории</option>
    <?php foreach ($categories as $category): ?>
        <option><?= $category ?></option>
    <?php endforeach; ?>
</select>