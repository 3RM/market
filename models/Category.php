<?php

/**
 * Модель для работы с категориями
 */
class Category extends Db {

    /**
     * Возвращает массив включенных категорий
     * @return array <p>Массив категорий</p>
     */
    public static function getCategoriesList() {

        $categoryList = array();

        $result = self::getConnection()->query('SELECT id, name FROM category '
                . 'WHERE status = "1" '
                . 'ORDER BY sort_order, name ASC');

        $i = 0;
        while ($row = $result->fetch()) {
            $categoryList[$i]['id'] = $row['id'];
            $categoryList[$i]['name'] = $row['name'];
            $i++;
        }
        return $categoryList;
    }

    /**
     * Возвращает массив категорий для списка в админпанели
     * (при этом в результат попадают и вкл. и выкл. категории)
     * @return array <p>Массив категорий</p>
     */
    public static function getCategoriesListAdmin() {

        $categoryList = array();

        $result = self::getConnection()->query('SELECT id, name, sort_order, status FROM category '
                . 'ORDER BY sort_order ASC');

        $i = 0;
        while ($row = $result->fetch()) {
            $categoryList[$i]['id'] = $row['id'];
            $categoryList[$i]['name'] = $row['name'];
            $categoryList[$i]['sort_order'] = $row['sort_order'];
            $categoryList[$i]['status'] = $row['status'];
            $i++;
        }
        return $categoryList;
    }

    /**
     * Возвращает категорию с указанным id
     * @param integer $id <p>id категории</p>
     * @return array <p>Массив с информацией о категории</p>
     */
    public static function getCategoryById($id) {

        $id = intval($id);

        $sql = "SELECT id, name, sort_order, status FROM category WHERE id=$id ";

        $result = self::getConnection()->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        return $result->fetch();
    }

    /**
     * Возвращает текстое пояснение статуса для категории :<br/>
     * <i>0 - Скрыта, 1 - Отображается</i>
     * @param integer $status <p>Статус</p>
     * @return string <p>Текстовое пояснение</p>
     */
    public static function getStatusText($status) {
        switch ($status) {
            case '1':
                return 'Отображается';
                break;
            case '0':
                return 'Скрыта';
                break;
        }
    }

    /**
     * Удаляет категорию с указанным id
     * @param int $id <p>id категории</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function deleteCategoryById($id) {

        $sql = "DELETE FROM category WHERE id = :id";

        $result = self::getConnection()->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    /**
     * Добавляет новую категорию
     * @param array $options <p>Массив данных</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function createCategory($options) {

        $sql = 'INSERT INTO category (name, sort_order, status) '
                . 'VALUES (:name, :sort_order, :status)';

        $result = self::getConnection()->prepare($sql);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':sort_order', $options['sort_order'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);
        return $result->execute();
    }

    public static function updateCategoryById($id, $options) {
        
        $sql = 'UPDATE category'
                . ' SET '
                . 'name = :name, '
                . 'sort_order = :sort_order, '
                . 'status = :status WHERE id = :id';

        $result = self::getConnection()->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':sort_order', $options['sort_order'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);
        return $result->execute();
        
    }

}
