define(['jquery'], function($) {
    return {
        init: function(){
           $("form").submit(function(e){
                username = $("#username").val();
                password = $("#password").val();
                recaptcha = $("#g-recaptcha-response").val();

                loginString = btoa(JSON.stringify({'username': username, 'password': password, 'recaptcha' : recaptcha , 'sessionKey' : sessionKey}));
                
                $("form").empty();
                $('<input>').prop('name','data').prop('type', 'hidden').val(loginString).appendTo('form');
            }); 
        }
    };
});