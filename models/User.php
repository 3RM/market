<?php

/**
 * Description of User
 *
 * @author rodnoy
 */
class User extends Db{

    /**
     * Записываем пользователя в БД 
     * @param string $name
     * @param string $password
     * @param string $email
     * @return boolean
     */
    public static function register($name, $password, $email) {

        $sql = 'INSERT INTO user (name, password, email) '
                . 'VALUES (:name, :password, :email)';

        $result = self::getConnection()->prepare($sql);

        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);

        return $result->execute();
    }
    
    /**
     * редактирование данных пользователя
     * @param string $userId
     * @param string $name
     * @param string $password
     * @return boolean
     */
    public static function edit($userId, $name, $password) {
        
        $sql = "UPDATE user"
                . " SET name = :name, password = :password"
                . " WHERE id = :id";
        $result = self::getConnection()->prepare($sql);
        $result->bindParam('id', $userId, PDO::PARAM_INT);
        $result->bindParam('name', $name, PDO::PARAM_STR);
        $result->bindParam('password', $password, PDO::PARAM_STR);
        
        return $result->execute();
        
    }

    /**
     * Проверяет имя: не меньше , чем 2 символа
     * @param string $name
     * @return boolean
     */
    public static function checkName($name) {
        if (strlen($name) >= 2) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет пароль: не меньше, чем 6 символов
     * @param string $password
     * @return boolean
     */
    public static function checkPassword($password) {
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет email
     * @param string $email
     * @return boolean
     */
    public static function checkEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Проверят, существует ли такой же(введеный при регистрации) $email в базе данных
     * @param string $email
     * @return boolean
     */
    public static function checkEmailExists($email) {

        $sql = "SELECT count(*) FROM user WHERE email = :email";

        $result = self::getConnection()->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();

        if ($result->fetchColumn())
            return true;
        return false;
    }

    /**
     * Проверяем существует ли пользователь с заданными $email и $password
     * @param string $email
     * @param string $password
     * @return mised: integer user id or false
     */
    public static function checkUserData($email, $password) {

        $sql = "SELECT * FROM user WHERE email = :email AND password = :password";

        $result = self::getConnection()->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->execute();

        $user = $result->fetch();
        if ($user) {
            return $user['id'];
        }

        return false;
    }

    /**
     * Запоминаем пользователя
     * @param integer $userId
     */
    public static function auth($userId) {

        $_SESSION['user'] = $userId;
    }

    /**
     * Проверяем залогинилася ли пользователь, если да,
     *  то возвращаем идентификатор
     * @return mixed: bollean or redirection
     */
    public static function checkLogged() {

        //Если сессия есть - вернем идентификатор поользователя
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        } else {
            header('Location: /user/login');
        }
    }

    /**
     * Проверяем наличие сессии пользователя,
     * для правильного отображения кнопок
     * управления кабинетом
     * @return boolean
     */
    public static function isGuest() {

        if (isset($_SESSION['user'])) {
            return false;
        }
        return true;
    }

    /**
     * Почаем id, name, password пользователя
     * @param int $id
     * @return array
     */
    public static function getUserById($id) {

        if ($id) {

            $sql = "SELECT id, name, password FROM user WHERE id = :id";

            $result = self::getConnection()->prepare($sql);
            $result->bindParam(':id', $id, PDO::PARAM_INT);
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $result->execute();

            return $result->fetch();
        }
    }

    /**
     * Сравнивает два пароля
     * @param string $first
     * @param string $second
     * @return boolean
     */
    public static function passwordMatch($first, $second) {

        if ($first === $second) {
            return true;
        }
        return false;
    }    

}
