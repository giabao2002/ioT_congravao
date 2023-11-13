$(document).ready(function () {
    const BODY = $(".body");
    const SUB = $(".sub-page");
    var CURRENT_LAYOUT = "home";

    callLayoutBody(CURRENT_LAYOUT);
    callLayoutSub("create-student");

    toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: true,
        progressBar: true,
        positionClass: "toast-top-right",
        preventDuplicates: false,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "5000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
    };


    function loading(status = true) {
        if (status) {
            $(".loading").removeClass("none");
            return;
        }
        $(".loading").addClass("none");
    }

    function callLayoutBody(layout) {
        loading();
        $.ajax({
            url: "./api/?action=layout.get",
            type: "POST",
            data: {
                layout: layout,
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.sweetAlert != undefined) {
                    swal({
                        title: "Thông báo",
                        text: data.sweetAlert.message,
                        icon: data.sweetAlert.status,
                    });
                }
                if (data.status) {
                    BODY.html(data.data.html);
                    return;
                }
            },
        })
            .fail(function () {
                swal({
                    title: "Thông báo",
                    text: "Có lỗi xảy ra. Vui lòng thử lại",
                    icon: "error",
                });
            })
            .always(function () {
                loading(false);
            });
    }

    function callLayoutSub(layout, data = {}) {
        arr = {
            layout: layout,
        };
        if (Object.keys(data).length !== 0) {
            arr = {
                ...arr,
                ...data,
            };
        }

        loading();
        $.ajax({
            url: "./api/?action=layout.sub.get",
            type: "POST",
            data: arr,
            success: function (data) {
                data = JSON.parse(data);
                if (data.status) {
                    subPage(true, data.data.html);
                    return;
                }
            },
        })
            .fail(function () {
                swal({
                    title: "Thông báo",
                    text: "Có lỗi xảy ra. Vui lòng thử lại",
                    icon: "error",
                });
            })
            .always(function () {
                loading(false);
            });
    }

    function subPage(active = false, html = "") {
        if (active) {
            SUB.find(".sub-container").html(html);
            SUB.removeClass("none");
            return;
        }
        SUB.addClass("none");
    }

    $(document).on("click", "#btn-open-sub-page-information", function (event) {
        event.preventDefault();

        callLayoutSub("information");
    });

    $(document).on(
        "click",
        "#btn-close-sub-page-information",
        function (event) {
            event.preventDefault();

            subPage();
        }
    );

    $(document).on("click", ".menu>.item", function (event) {
        event.preventDefault();

        const selected = $(this).hasClass("selected");
        const layout = $(this).data("layout");
        if (selected) {
            return;
        }


        if (layout == 'qr') {
            swal({
                title: "Thông báo",
                text: "Tính năng đang phát triển",
                icon: "error",
            });
            return;
        }

        CURRENT_LAYOUT = layout;

        callLayoutBody(layout);

        $(".menu>.item").removeClass("selected");
        $(this).addClass("selected");
    });

    $(document).on("click", ".box-study>.item", function (event) {
        event.preventDefault();
        if ($(this).attr("id") != undefined) {
            return;
        }

        swal({
            title: "Thông báo",
            text: "Tính năng đang phát triển",
            icon: "error",
        });
        return;
    });

    $(document).on("click", "#btn-login", function (event) {
        event.preventDefault();

        const email = $("#email").val();
        const password = $("#password").val();
        const privacy_policy = $("#privacy-policy").is(":checked");

        if (email == "") {
            toastr.error("Vui lòng nhập email");
            return;
        }
        if (password == "") {
            toastr.error("Vui lòng nhập mật khẩu");
            return;
        }
        if (!privacy_policy) {
            toastr.error("Vui lòng đồng ý với điều khoản");
            return;
        }

        loading();

        $.ajax({
            url: "./api/?action=handle",
            type: "POST",
            data: {
                function: "login",
                email: email,
                password: password,
            },
            success: function (data) {
                data = JSON.parse(data);
                if (!data.status) {
                    toastr.error(data.message);
                    return;
                }
                toastr.success(data.message);
                callLayoutBody(CURRENT_LAYOUT);
            },
        })
            .fail(function () {
                swal({
                    title: "Thông báo",
                    text: "Có lỗi xảy ra. Vui lòng thử lại",
                    icon: "error",
                });
            })
            .always(function () {
                loading(false);
            });
    });

    $(document).on("click", "#btn-logout", function (event) {
        event.preventDefault();

        loading();

        $.ajax({
            url: "./api/?action=handle",
            type: "POST",
            data: {
                function: "logout",
            },
            success: function (data) {
                data = JSON.parse(data);
                if (!data.status) {
                    toastr.error(data.message);
                    return;
                }
                toastr.success(data.message);
                callLayoutBody('login');
                subPage();
            },
        })
            .fail(function () {
                swal({
                    title: "Thông báo",
                    text: "Có lỗi xảy ra. Vui lòng thử lại",
                    icon: "error",
                });
            })
            .always(function () {
                loading(false);
            });
    });

    $(document).on("click", "#btn-payment", function (event) {
        event.preventDefault();
        loading();

        $.ajax({
            url: "./api/?action=handle",
            type: "POST",
            data: {
                function: "payment",
            },
            success: function (data) {
                data = JSON.parse(data);
                if (!data.status) {
                    toastr.error(data.message);
                    return;
                }
                toastr.success(data.message);
                callLayoutBody(CURRENT_LAYOUT);
                subPage();

                window.open(data.data.url);
            },
        })
            .fail(function () {
                swal({
                    title: "Thông báo",
                    text: "Có lỗi xảy ra. Vui lòng thử lại",
                    icon: "error",
                });
            })
            .always(function () {
                loading(false);
            });
    });

    $(document).on("click", "#btn-show-payment", function (event) {
        event.preventDefault();

        callLayoutSub("payment");
    });

    $(document).on("click", "#btn-manager", function (event) {
        event.preventDefault();

        callLayoutSub("manager");
    });

    $(document).on("click", ".btn-update-user", function (event) {
        event.preventDefault();

        const id = $(this).data("id");
        callLayoutSub("update-student", { id: id });
    });

    $(document).on("click", "#btn-close-sub-page-update-student", function (event) {
        event.preventDefault();

        callLayoutSub("manager");
    });

    $(document).on("click", "#btn-save-update-information", function (event) {
        event.preventDefault();
        const id = $(this).data("id");
        const name = $("#name").val();
        const email = $("#email").val();
        const phone = $("#phone").val();
        const gender = $("#gender").val();
        const money = $("#money").val();


        loading();

        $.ajax({
            url: "./api/?action=handle",
            type: "POST",
            data: {
                function: "updateInformation",
                id: id,
                name: name,
                email: email,
                phone: phone,
                gender: gender,
                money: money,
            },
            success: function (data) {
                data = JSON.parse(data);
                if (!data.status) {
                    toastr.error(data.message);
                    return;
                }
                toastr.success(data.message);
            },
        })
            .fail(function () {
                swal({
                    title: "Thông báo",
                    text: "Có lỗi xảy ra. Vui lòng thử lại",
                    icon: "error",
                });
            })
            .always(function () {
                loading(false);
            });
    });

    $(document).on("click", "#btn-open-create-student", function (event) {
        event.preventDefault();

        callLayoutSub("create-student");
    });

    $(document).on("click", "#btn-create-student", function (event) {
        event.preventDefault();
        const name = $("#name").val();
        const email = $("#email").val();
        const phone = $("#phone").val();
        const gender = $("#gender").val();
        const money = $("#money").val();
        const password = $("#password").val();

        loading();

        $.ajax({
            url: "./api/?action=handle",
            type: "POST",
            data: {
                function: "createStudent",
                name: name,
                email: email,
                phone: phone,
                gender: gender,
                money: money,
                password: password,
            },
            success: function (data) {
                data = JSON.parse(data);
                if (!data.status) {
                    toastr.error(data.message);
                    return;
                }
                toastr.success(data.message);
            },
        })
            .fail(function () {
                swal({
                    title: "Thông báo",
                    text: "Có lỗi xảy ra. Vui lòng thử lại",
                    icon: "error",
                });
            })
            .always(function () {
                loading(false);
            });
    });
});
