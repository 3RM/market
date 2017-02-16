<?php

/**
 * Класс Product - модель для работы с товарами
 */
class Product extends Db {

    // Количество отображаемых товаров по умолчанию
    const SHOW_BY_DEFAULT = 3;

    /**
     * Возвращает массив последних товаров
     * @param int $count [optional] <p>Количество</p>
     * @return array <p>Массив с товарами</p>
     */
    public static function getLatestProducts($count = self::SHOW_BY_DEFAULT) {

        $count = intval($count);

        $productList = array();

        $result = self::getConnection()->query('SELECT id, name, price, image, is_new FROM product '
                . 'WHERE status = "1" '
                . 'ORDER BY id DESC '
                . 'LIMIT ' . $count);

        $i = 0;
        while ($row = $result->fetch()) {
            $productList[$i]['id'] = $row['id'];
            $productList[$i]['name'] = $row['name'];
            $productList[$i]['price'] = $row['price'];
            $productList[$i]['image'] = $row['image'];
            $productList[$i]['is_new'] = $row['is_new'];
            $i++;
        }

        return $productList;
    }

    /**
     * Возвращает продукт с указанным id
     * @param integer $id <p>id товара</p>
     * @return array <p>Массив с информацией о товаре</p>
     */
    public static function getProductById($id) {

        $id = intval($id);

        $result = self::getConnection()->query("SELECT * FROM product "
                . "WHERE id=$id ");
        $result->setFetchMode(PDO::FETCH_ASSOC);

        return $result->fetch();
    }

    /**
     * Возвращает список товаров в указанной категории
     * @param int $categoryId <p>id категории</p>
     * @param int $page [optional] <p>Номер страницы</p>
     * @return array <p>Массив с товарами</p>
     */
    public static function getProductsListByCategory($categoryId = false, $page = 1) {

        if ($categoryId) {

            $offset = ((int) $page - 1) * self::SHOW_BY_DEFAULT;

            $products = array();

            $result = self::getConnection()->query("SELECT id, name, price, image, is_new FROM product "
                    . "WHERE status = '1' AND category_id = '$categoryId' "
                    . "ORDER BY id ASC "
                    . "LIMIT " . self::SHOW_BY_DEFAULT
                    . " OFFSET " . $offset);

            $i = 0;
            while ($row = $result->fetch()) {
                $products[$i]['id'] = $row['id'];
                $products[$i]['name'] = $row['name'];
                $products[$i]['price'] = $row['price'];
                $products[$i]['image'] = $row['image'];
                $products[$i]['is_new'] = $row['is_new'];
                $i++;
            }

            return $products;
        }
    }

    /**
     * Возвращаем количество товаров в указанной категории
     * @param integer $categoryId <p>id категории</p>
     * @return integer <p>Колличество товаров</p>
     */
    public static function getTotalProductsInCategory($categoryId) {

        $result = self::getConnection()->query("SELECT count(id) AS count FROM product "
                . "WHERE status = '1' AND category_id = '$categoryId'");
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $row = $result->fetch();

        return $row['count'];
    }

    /**
     * Возвращает список товаров с указанными индентификторами
     * @param array $idsArray <p>Массив с идентификаторами</p>
     * @return array <p>Массив со списком товаров</p>
     */
    public static function getProductsByIds($idsArray) {

        $products = array();

        $idsString = implode(',', $idsArray);

        $sql = "SELECT * FROM product WHERE status = '1' AND id IN ($idsString)";

        $result = self::getConnection()->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        $i = 0;
        while ($row = $result->fetch()) {
            $products[$i]['id'] = $row['id'];
            $products[$i]['code'] = $row['code'];
            $products[$i]['name'] = $row['name'];
            $products[$i]['price'] = $row['price'];
            $i++;
        }
        return $products;
    }

    /**
     * Возвращает список рекомендуемых товаров
     * @return array <p>Массив с товарами</p>
     */
    public static function getRecommendedProductList() {

        $products = array();

        $sql = "SELECT * FROM product WHERE status = '1' AND "
                . "is_recommended = '1'";

        $result = self::getConnection()->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        $i = 0;
        while ($row = $result->fetch()) {
            $products[$i]['id'] = $row['id'];
            $products[$i]['image'] = $row['image'];
            $products[$i]['name'] = $row['name'];
            $products[$i]['price'] = $row['price'];
            $products[$i]['is_new'] = $row['is_new'];
            $i++;
        }
        return $products;
    }

    /**
     * Возвращает весь список товаров
     * @return array <p>Массив с товарами</p>
     */
    public static function getProductList() {

        $products = array();

        $sql = "SELECT id, name, price, code FROM product ORDER BY id ASC";

        $result = self::getConnection()->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        $i = 0;
        while ($row = $result->fetch()) {
            $products[$i]['id'] = $row['id'];
            $products[$i]['name'] = $row['name'];
            $products[$i]['price'] = $row['price'];
            $products[$i]['code'] = $row['code'];
            $i++;
        }
        return $products;
    }

    /**
     * Удаляет товар с указанным id
     * @param int $id <p>id товара</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function deleteById($id) {

        $id = intval($id);

        $sql = "DELETE FROM product WHERE id = :id";

        $result = self::getConnection()->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        return true;
    }

    /**
     * Редактирует товар с заданным id
     * @param int $id <p>id товара</p>
     * @param array $option <p>Массив с информацией о товаре</p>
     * @return boolean <p>Результат выполнения метода<p>
     */
    public static function updateProductById($id, $options) {

        $id = intval($id);

        $sql = "UPDATE product
            SET 
                name = :name, 
                code = :code, 
                price = :price, 
                category_id = :category_id, 
                brand = :brand, 
                availability = :availability, 
                description = :description, 
                is_new = :is_new, 
                is_recommended = :is_recommended, 
                status = :status
            WHERE id = :id";

        $result = self::getConnection()->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':code', $options['code'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':category_id', $options['category_id'], PDO::PARAM_INT);
        $result->bindParam(':brand', $options['brand'], PDO::PARAM_STR);
        $result->bindParam(':availability', $options['availability'], PDO::PARAM_INT);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':is_new', $options['is_new'], PDO::PARAM_INT);
        $result->bindParam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);

        return $result->execute();
    }

    /**
     * Добавляет новый товар
     * @param array $options <p>Массив с информацией о товаре</p>
     * @return int <p>id добавленой в таблицу записи</p>
     */
    public static function createProduct($options) {
        //!!
        $db = Db::getConnection();
        //!!

        $sql = "INSERT INTO product "
                . "(name, code, price, category_id, brand, availability, "
                . "description, is_new, is_recommended, status) "
                . "VALUES "
                . "(:name, :code, :price, :category_id, :brand, :availability, "
                . ":description, :is_new, :is_recommended, :status)";
        //Проблема с статическим методом подключения к БД.
        //Если использовать статическое подключение, lastInsertId() не возвращает id последней записи
        //и не отрабатывает сохранение картинки для продукта в AdminProductcontroller
        //!!
        //$result = self::getConnection()->prepare($sql);
        //!!
        $result = $db->prepare($sql);
        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':code', $options['code'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':category_id', $options['category_id'], PDO::PARAM_INT);
        $result->bindParam(':brand', $options['brand'], PDO::PARAM_STR);
        $result->bindParam(':availability', $options['availability'], PDO::PARAM_INT);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':is_new', $options['is_new'], PDO::PARAM_INT);
        $result->bindParam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);

        if ($result->execute()) {
            //Если запрос выполнен успешно, возвращает id добавленной записи
            //!!
            //return self::getConnection()->lastInsertId();
            //!!
            return $db->lastInsertId();
        }
        //Если запрос не вы полнен успешно, возвращает ноль
        return 0;
    }

    /**
     * Возвращает id последнего записаного товара
     * @return array
     */
    public static function getLastProduct() {

        $result = self::getConnection()->query("SELECT id FROM product ORDER BY id DESC LIMIT 1");
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $row = $result->fetch();

        return $row['id'];
    }
    
    /**
     * Возвращает путь к изображению
     * @param int $id <p>id продукта</p>
     * @return string <p>Путь к изображению</p>
     */
    public static function getImage($id){
        
        //Название изображения, если его нет
        $noImage = 'http://tour-fly.com/uploadedFiles/images/tourfly/no_photo.gif';
        
        //Путь к папке с изображениями
        $folderPath = '/upload/images/products/';
        
        //путь к изображению товара
        $imagePath = $folderPath.$id.'.jpg';
        
        if(file_exists($_SERVER['DOCUMENT_ROOT'].$imagePath)){
            //если изображения для товара существует
            //возвращаем путь к изображению товара
            return $imagePath;
        }
        //если изображения не существует, возвращаем фото-заглушку
        return $noImage;
    }

}
