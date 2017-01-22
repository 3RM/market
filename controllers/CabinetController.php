<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cabinetcontroller
 *
 * @author rodnoy
 */
class CabinetController {

    public function actionIndex() {

        //Получаем идентификатор пользователя из сессии
        $userId = User::checkLogged();

        //Получаем инфу о пользователе из БД
        $user = User::getUserById($userId);

        require_once ROOT . '/views/cabinet/index.php';

        return true;
    }

    public function actionEdit() {

        //Получаем идентификатор пользователя из сессии
        $userId = User::checkLogged();

        //Получаем данные о пользователе из БД
        $user = User::getUserById($userId);

        $name = $user['name'];
        //Текущий пароль в БД
        $currentPasswordDb = $user['password'];

        $result = false;

        if (isset($_POST['edit'])) {

            $name = filter_input(INPUT_POST, 'name');
            //Новый пароль принятый из формы для сравнения с текущим
            $currentPasswordForm = filter_input(INPUT_POST, 'password');
            //Новый пароль для обновления текущего в БД
            $newPassword = filter_input(INPUT_POST, 'new_password');

            $errors = false;

            if (!User::checkName($name)) {
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }

            if (!User::checkPassword($newPassword)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }

            if (!User::passwordMatch($currentPasswordDb, $currentPasswordForm)) {
                $errors[] = 'Не верный текущий пароль';
            }

            if($errors == false){
                $result = User::edit($userId, $name, $newPassword);
            }
        }

        require_once ROOT . '/views/cabinet/edit.php';

        return true;
    }

}
