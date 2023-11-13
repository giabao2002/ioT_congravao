<div class="box-container">
    <div class="box-user" id="btn-open-sub-page-information">
        <div class="avatar">
            <img src="./Assets/Images/<?= $user->gender ?>.png" alt="">
        </div>
        <div class="info">
            <p>Xin chào,</p>
            <div class="name"><?= $user->name ?></div>
        </div>
    </div>
    <div class="line-category">Học tập</div>
    <div class="box-study">
        <div class="item">
            <img src="./Assets/Images/bookshelf.png" alt="">
            <p>Thư viện</p>
        </div>
        <div class="item">
            <img src="./Assets/Images/document.png" alt="">
            <p>Công tác sinh viên</p>
        </div>
        <div class="item">
            <img src="./Assets/Images/calendar.png" alt="">
            <p>Thời khóa biểu</p>
        </div>
        <div class="item">
            <img src="./Assets/Images/heart.png" alt="">
            <p>Thể dục online</p>
        </div>
    </div>
    <div class="line-category">Dịch vụ</div>
    <div class="box-study">
        <div class="item">
            <img src="./Assets/Images/wheel.png" alt="">
            <p>Phương tiện</p>
        </div>
    </div>
    <?php if ($user->role == 'admin'): ?>
    <div class="line-category">Quản lý</div>
    <div class="box-study">
        <div class="item" id="btn-manager">
            <img src="./Assets/Images/management.png" alt="">
            <p>QL Sinh viên</p>
        </div>
    </div>
    <?php 
    endif;
    if ($user->money > 0): ?>
        <div class="pay-the-bill">
            <div class="head">
                <div class="title">Thanh toán hóa đơn</div>
                <ion-icon name="chevron-forward-outline" id="btn-show-payment"></ion-icon>
            </div>
            <div class="info">
                <div class="row">
                    <label>Mã thẻ</label>
                    <span><?= $user->access_token ?></span>
                </div>
                <div class="row">
                    <label>Phương tiện</label>
                    <span>Xe máy</span>
                </div>
                <div class="row">
                    <label>Phí gửi xe</label>
                    <span><?= number_format($user->money) ?>đ</span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>