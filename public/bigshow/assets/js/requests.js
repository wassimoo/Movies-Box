$(document).ready(function () {
    $("#login").click(function (e) {
        e.preventDefault();

        var username = $("#username").val();
        var password = $("#password").val();
        var stayLoggedIn = $("#stayIn").prop('checked');

        var msg = $(".login-msg");

        if (username == "") {
            msg.html("please specify a username");
            msg.show();
            return;
        }
        if (password == "") {
            msg.html("please enter a password");
            msg.show();
            return;
        }

        $.ajax({
            url: "login/performLogin",
            type: "POST",
            data: {
                username: username,
                password: password,
                stayLoggedIn: stayLoggedIn
            },
            dataType: "html",
            beforeSend: function () {
                msg.html("Logging you in ...");
                msg.show();
            },
            success: function (html) {
                if (html == "true")
                    window.location.replace(" ");
                else {
                    if (html == "can't establish connection to server") {
                        msg.html(html);
                        msg.show();
                    }
                    else {
                        msg.html("Invalid username/password !");
                        msg.show();
                    }
                }
            }
        })
    });

    $("#register").click(function (e) {
        e.preventDefault();

        var msg = $(".login-msg");

        var data = [
            $("#username").val(),
            $("#password").val(),
            $("#first_name").val(),
            $("#last_name").val(),
            $("#email").val()
        ]

        data.forEach(info => {
            if (info == "")
                msg.html("please provide all required informations");
            msg.show();
            return;
        });

        $.ajax({
            url: "signup/performsignup",
            type: "POST",
            data: {
                username: data[0],
                password: data[1],
                first_name: data[2],
                last_name: data[3],
                email: data[4]
            },
            dataType: "html",
            beforeSend: function () {
                msg.html("Signing you up ...");
                msg.show();
            },
            success: function (html) {
                if (html == "true")
                    window.location.replace(" ");
                else if (html == "can't establish connection to server") {
                    msg.html(html);
                    msg.show();
                }
                else {
                    msg.html("Inknown error occured !");
                    msg.show();
                }
            }
            })

    })
});