<div class="box-payment">
    <div class="head">
        <div class="icon" id="btn-close-sub-page-information">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </div>
        <h3>QL Sinh viên</h3>
        <button id="btn-open-create-student">Thêm</button>
    </div>
    <table class="table-manager">
        <thead>
            <tr>
                <td>ID</td>
                <td>Họ tên</td>
                <td>Tiền nợ</td>
                <td>Thao tác</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?= $student['id'] ?></td>
                <td><?= $student['name'] ?></td>
                <td><?= number_format($student['money']) ?>đ</td>
                <td>
                    <button class="btn-update-user" data-id="<?= $student['id'] ?>">Cập nhật</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>