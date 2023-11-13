<div class="box-notification">
    <div class="title">Thông báo</div>
    <div class="list">
        <?php foreach($histories as $history): ?>
            <div class="item">
                <div class="icon">
                    <img src="./Assets/Images/<?= $history['type'] == 'in'? 'login' : 'exit-door'  ?>.png" alt="">
                </div>
                <div class="content">
                    <div class="time">
                        <label><?= $history['type'] == 'in'? 'Xe vào cổng' : 'Xe ra cổng'  ?></label>
                        <span><?= date('d/m/Y', strtotime($history['created_at'])) ?></span>
                    </div>
                    <div class="description"><?= date('H:i d/m/Y', strtotime($history['created_at'])) ?>: <?= $history['type'] == 'in'? 'Xe vào cổng' : 'Xe ra cổng'  ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>