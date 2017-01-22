<?php

/**
 * Description of UserController
 *
 * @author rodnoy
 */
class UserController {

    public function actionRegister() {

        $name = '';
        $email = '';
        $password = '';
        $result = false;

        if (isset($_POST['lets_reg'])) {

            $name = filter_input(INPUT_POST, 'name');
            $email = filter_input(INPUT_POST, 'email');
            $password = filter_input(INPUT_POST, 'password');

            $errors = '';

            if (!User::checkName($name)) {
                $errors[] = "Имя не должно быть короче 2-х символов";
            }

            if (!User::checkPassword($password)) {
                $errors[] = "Пароль не должен быть короче 6-ти символов";
            }

            if (!User::checkEmail($email)) {
                $errors[] = "Email не валидный";
            }

            if (User::checkEmailExists($email)) {
                $errors[] = "Такой email уже используется";
            }

            if ($errors == false) {
                $result = User::register($name, $password, $email);
            }
        }

        require_once ROOT . '/views/user/register.php';

        return true;
    }

    public function actionLogin() {

        $email = '';
        $password = '';

        if (isset($_POST['lets_login'])) {
            $email = filter_input(INPUT_POST,'email');
            $password = filter_input(INPUT_POST,'password');
            $errors = false;
            //Валидация полей
            if (!User::checkEmail($email)) {
                $errors[] = "Неправильный email";
            }

            if (!User::checkPassword($password)) {
                $errors[] = "Пароль не должен быть короче 6-ти символов";
            }

            //Проверяем существует ли пользователь
            $userId = User::checkUserData($email, $password);

            if ($userId == false) {
                //Если данные неправильные - показать ошибку
                $errors[] = "Неправильные данные для входа";
            } else {
                //если данные правильные - записываем пользователя в сессию
                User::auth($userId);
                
                //Перенаправляем пользователя в закрытую часть
                header('Location: /cabinet/');
            }
        }
        require_once ROOT . '/views/user/login.php';
        
        return true;
    }
    
    public function actionLogout(){
        
        unset($_SESSION['user']);
        header('Location: /');
    }    

}
