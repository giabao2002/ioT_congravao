<div class="box-payment">
    <div class="head">
        <div class="icon" id="btn-close-sub-page-information">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </div>
        <h3>Vé xe</h3>
        <span></span>
    </div>
    <div class="info">
        <div class="title">Thông tin người dùng:</div>
        <div class="content">
            <div class="item">
                <ion-icon name="person"></ion-icon>
                <span><?= $user->name ?></span>
            </div>
            <div class="item">
                <ion-icon name="call"></ion-icon>
                <span><?= $user->phone ?></span>
            </div>
            <div class="item">
                <ion-icon name="mail"></ion-icon>
                <span><?= $user->email ?></span>
            </div>
            <div class="item">
                <ion-icon name="wallet-outline"></ion-icon>
                <span><?= number_format($user->money) ?>đ</span>
            </div>
            <div class="item-button">
                <div id="btn-payment">Thanh toán ngay</div>
            </div>
        </div>
    </div>
</div>