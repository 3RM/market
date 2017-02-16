<?php

class Order extends Db {
    /**
     * Сохраняет новый заказ в БД
     * @param string $userName <p>Имя</p>
     * @param string $userPhone <p>Телефон</p>
     * @param string $userComment <p>Комментарий</p>
     * @param int $userId <p>id пользователя</p>
     * @param array $products <p>Массив с товарами</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function save($userName, $userPhone, $userComment, $userId, $products) {

        $sql = "INSERT INTO product_order (user_name, user_phone, user_comment, user_id, products) "
                . "VALUES (:user_name, :user_phone, :user_comment, :user_id, :products)";

        $products = json_encode($products);

        $result = self::getConnection()->prepare($sql);
        $result->bindParam(":user_name", $userName, PDO::PARAM_STR);
        $result->bindParam(":user_phone", $userPhone, PDO::PARAM_STR);
        $result->bindParam(":user_comment", $userComment, PDO::PARAM_STR);
        $result->bindParam(":user_id", $userId, PDO::PARAM_STR);
        $result->bindParam(":products", $products, PDO::PARAM_STR);

        return $result->execute();
    }
    
    public static function updateOrderById($id, $userName, $userPhone, $userComment, $date, $status){
        
        $sql = "UPDATE product_order"
                . " SET "
                . "user_name = :user_name, "
                . "user_phone = :user_phone, "
                . "user_comment =:user_comment, "
                . "date = :date, "
                . "status =:status"
                . " WHERE id =:id";
        
        $result = self::getConnection()->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':user_name', $userName, PDO::PARAM_STR);
        $result->bindParam(':user_phone', $userPhone, PDO::PARAM_STR);
        $result->bindParam(':user_comment', $userComment, PDO::PARAM_STR);
        $result->bindParam(':date', $date, PDO::PARAM_STR);
        $result->bindParam(':status', $status, PDO::PARAM_INT);
        
        return $result->execute();
    }
    
    /**
     * Возвращает массив с заказами
     * @return array <p>Массив заказов</p>
     */
    public static function getOrderList() {

        $orderList = array();

        $sql = "SELECT id, user_name, user_phone, date, status FROM product_order";

        $result = self::getConnection()->query($sql);

        $i = 0;
        foreach ($result as $row) {
            $orderList[$i]['id'] = $row['id'];
            $orderList[$i]['user_name'] = $row['user_name'];
            $orderList[$i]['user_phone'] = $row['user_phone'];
            $orderList[$i]['date'] = $row['date'];
            $orderList[$i]['status'] = $row['status'];
            $i++;
        }

        return $orderList;
    }
    
    /**
     * Удаляет заказ с заданым id
     * @param int $id <p>id заказа</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function deleteOrderById($id){
        
        $sql = "DELETE FROM product_order WHERE id = :id";
        
        $result = self::getConnection()->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $result->execute();
    }
    
    /**
     * Возвращает текстое пояснение статуса для заказа :<br/>
     * <i>1 - Новый заказ, 2 - В обработке, 3 - Доставляется, 4 - Закрыт</i>
     * @param integer $status <p>Статус</p>
     * @return string <p>Текстовое пояснение</p>
     */
    public static function getStatusText($status) {
        switch ($status) {
            case '1':
                return 'Новый заказ';
                break;
            case '2':
                return 'В обработке';
                break;
            case '3':
                return 'Доставляется';
                break;
            case '4':
                return 'Закрыт';
                break;
        }
    }
    
    /**
     * Возвращает заказ с заданым id
     * @param int $id <p>id заказа</p>
     * @return array <p>Массив с информацией о заказе</p>
     */
    public static function getOrderById($id){
        
        $sql = "SELECT * FROM product_order WHERE id =:id";
        
        $result = self::getConnection()->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();        
        
        return $result->fetch();
        
    }

}
