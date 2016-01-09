

function loginFbPost(token){
    
    $.ajax({
        type: "POST",
        url: "services/loginFb.php",
        data: {"token":token},
        success: function (data) {
            
            if ($.trim(data) === '1')
                window.location.assign("home.php");
            else {
            }
        },
        error: function (data) {
            console.log("erro fatal");
        }
    });
    
}

function statusChangeCallback(response) {
    
    if (response.status === 'connected') {
        loginFbPost(response.authResponse.accessToken)
    }
}

function checkLoginState() {
    FB.getLoginStatus(function (response) {
        statusChangeCallback(response);
    });
}

window.fbAsyncInit = function () {
    FB.init({
        appId: '1678302572454101',
        cookie: true, // enable cookies to allow the server to access 
        // the session
        xfbml: true, // parse social plugins on this page
        version: 'v2.2' // use version 2.2
    });
    
    // Verifica se usuário está no facebook e autorizou acesso aos seus dados
    //Auto login
    //checkLoginState();
};

// Load the SDK asynchronously
(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id))
        return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


function fb_login(){
    FB.login(function(response) {
        if (response.authResponse) {
            access_token = response.authResponse.accessToken;
            loginFbPost(access_token);
        } else {
            
        }
    });
}