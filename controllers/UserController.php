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
            
            if(User::checkEmailExists($email)){
                $errors[] = "Такой email уже используется";
            }

            if ($errors == false) {
                $result = User::register($name, $password, $email);
            }
        }

        require_once ROOT . '/views/user/register.php';

        return true;
    }

}
