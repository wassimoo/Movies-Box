$(document).ready(function () {
    $("#login").click(function (e) {
        e.preventDefault();
        
        var username = $("#username").val();
        var password = $("#password").val();
        var stayLoggedIn = $("#stayIn").prop('checked');

        var msg = $(".login-msg");

        if(username == ""){
            msg.html("please specify a username");
            msg.show();
            return;
        }
        if(password == ""){
            msg.html("please enter a password");
            msg.show();
            return;
        }

        $.ajax({
            url: "login/performLogin",
            type: "POST",
            data: { username: username,
                    password: password ,
                    stayLoggedIn: stayLoggedIn
                },
            dataType: "html",
            beforeSend: function(){
                msg.html("Logging you in ...");
                msg.show();
            },
            success: function (html) {
                if (html == "true")
                    window.location.replace(" ");
                else{
                    if(html == "can't establish connection to server"){
                        msg.html(html);
                        msg.show();
                    }
                    else{
                        msg.html("Invalid username/password !");
                        msg.show();
                    }
                }
            }
        })
    });
});