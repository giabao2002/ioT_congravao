<div class="box-history">
    <div class="title">Lịch sử GD</div>
    <div class="list">
        <?php foreach ($months as $key => $transactions): ?>
            <div class="month">
                <div class="title">Tháng <?= $key ?></div>
                <div class="transactions">
                    <?php foreach ($transactions as $transaction): ?>
                        <div class="transaction">
                            <div class="icon">
                                <img src="./Assets/Images/scooter.png" alt="">
                            </div>
                            <div class="content">
                                <div class="description">
                                    <label>Thanh toán phí gửi xe</label>
                                    <span><?= number_format($transaction['money']) ?>đ</span>
                                </div>
                                <div class="time">
                                    <div class="status">
                                        <div class="icon">
                                            <ion-icon name="checkmark-outline"></ion-icon>
                                        </div>
                                        <label>Thành công</label>
                                    </div>
                                    <span><?= date('H:i - d/m/Y', strtotime($transaction['created_at'])) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>