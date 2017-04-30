<select class="lots__select">
    <option>Все категории</option>
    <?php foreach ($data[0] as $category): ?>
        <option><?= $category ?></option>
    <?php endforeach; ?>
</select>