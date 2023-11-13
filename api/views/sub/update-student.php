<div class="box-payment">
    <div class="head">
        <div class="icon" id="btn-close-sub-page-update-student">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </div>
        <h3>Cập nhật thông tin</h3>
        <span></span>
    </div>
    <div class="information-student">
        <div class="form-input">
            <label for="name">Họ tên</label>
            <input type="text" name="name" id="name" value="<?= $student['name'] ?>">
        </div>
        <div class="form-input">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= $student['email'] ?>">
        </div>
        <div class="form-input">
            <label for="phone">Số điện thoại</label>
            <input type="text" name="phone" id="phone" value="<?= $student['phone'] ?>">
        </div>
        <div class="form-input">
            <label for="gender">Giới tính</label>
            <select name="" id="gender">
                <option value="male" <?= $student['gender'] == 'male' ? 'selected' : '' ?> >Nam</option>
                <option value="female" <?= $student['gender'] == 'female' ? 'selected' : '' ?> >Nữ</option>
            </select>
        </div>
        <div class="form-input">
            <label for="money">Số tiền</label>
            <input type="number" name="money" id="money" value="<?= $student['money'] ?>">
        </div>
        <div class="form-button">
            <button id="btn-save-update-information" data-id="<?= $student['id'] ?>">Lưu</button>
        </div>
    </div>
</div>