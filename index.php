<?php
include "include/init.php";

// TODO move this to init
include "app/login/LoginController.php";
include "app/login/LoginModel.php";
include "app/login/LoginView.php";

include "app/register/RegisterController.php";
include "app/register/RegisterModel.php";
include "app/register/RegisterView.php";

if (is_page('image')) {
    include "app/image.php";
} else {

    include "include/header.php";
    if (is_page('register') && !is_logged_in()) {

        // Register page
//        include "app/register.php";

        $register_model = new RegisterModel();
        $register_controller = new RegisterController($register_model);
        $register_controller->onRegister();

        $register_view = new RegisterView($register_controller, $register_model);

        echo $register_view->output();


    } else if (is_page('login') || !is_logged_in()) {
        // Login page
        $login_model = new LoginModel();
        $login_controller = new LoginController($login_model);
        $login_view = new LoginView($login_controller, $login_model);

        if (is_logged_in()) {
            $login_controller->onLogout();
        } else {
            $login_controller->onLogin();
        }

        echo $login_view->output();

    } else if (is_logged_in()) {

        if(isset($_GET['page'])) {
            // Generic page
            include('app/' . $_GET['page'] . '.php');
        } else {

            // Include frontpage
            include "include/navigation.php";
            include "app/profile_header.php";
            include "app/post_page.php";
        }

    }

    include "include/footer.php";

}


function is_logged_in() {
    return isset($_SESSION["name"]);
}

function is_page($name) {
    return isset($_GET['page']) && $_GET['page'] == $name;
}





