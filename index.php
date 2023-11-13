<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID</title>
    <link rel="shortcut icon" href="./Assets/Images/favicon.png" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <!-- Ion-Icons -->
    <script type="module" src="https://unpkg.com/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
<script nomodule="" src="https://unpkg.com/ionicons@latest/dist/ionicons/ionicons.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <link rel="stylesheet" href="./Assets/Css/style.css">
    <script src="./Assets/Js/main.js"></script>
</head>
<body>
    <div class="loading none">
        <div class="scene">
            <div class="cube-wrapper">
                <div class="cube">
                    <div class="cube-faces">
                        <div class="cube-face shadow"></div>
                        <div class="cube-face bottom"></div>
                        <div class="cube-face top"></div>
                        <div class="cube-face left"></div>
                        <div class="cube-face right"></div>
                        <div class="cube-face back"></div>
                        <div class="cube-face front"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sub-page none">
        <div class="sub-container">
        </div>
    </div>
    <div class="container">
        <div class="body">
        </div>
        <div class="menu">
            <div class="item selected" data-layout="home">
                <ion-icon name="home-outline"></ion-icon>
                <label>Home</label>
            </div>
            <div class="item" data-layout="history">
                <ion-icon name="timer-outline"></ion-icon>
                <label>Lịch sử GD</label>
            </div>
            <div class="item" data-layout="qr">
                <div class="hexagon">
                    <ion-icon name="qr-code-outline"></ion-icon>
                </div>
            </div>
            <div class="item" data-layout="notification">
                <ion-icon name="notifications-outline"></ion-icon>
                <label>Thông báo</label>
            </div>
            <div class="item" data-layout="setting">
                <ion-icon name="settings-outline"></ion-icon>
                <label>Cài đặt</label>
            </div>
        </div>
    </div>
</body>
</html>