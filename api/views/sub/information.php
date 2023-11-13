<div class="box-information">
    <div class="head">
        <div class="icon" id="btn-close-sub-page-information">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </div>
        <div id="btn-logout">Đăng xuất</div>
        <div class="background">
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="border-avatar">
        <div class="avatar">
            <img src="./Assets/Images/<?= $user->gender ?>.png" alt="">
        </div>
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
        </div>
    </div>
</div>