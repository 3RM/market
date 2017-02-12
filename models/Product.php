<?php

/**
 * Description of Product
 *
 * @author rodnoy
 */
class Product extends Db{

    const SHOW_BY_DEFAULT = 3;

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

    public static function getProductById($id) {

        $id = intval($id);

        $result = self::getConnection()->query("SELECT * FROM product "
                . "WHERE id=$id ");
        $result->setFetchMode(PDO::FETCH_ASSOC);

        return $result->fetch();
    }

    public static function getProductsListByCategory($categoryId = false, $page = 1) {

        if ($categoryId) {

            $offset = ((int) $page - 1) * self::SHOW_BY_DEFAULT;

            $db = Db::getConnection();
            $products = array();
            $result = $db->query("SELECT id, name, price, image, is_new FROM product "
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

    public static function getTotalProductsInCategory($categoryId) {

        $result = self::getConnection()->query("SELECT count(id) AS count FROM product "
                . "WHERE status = '1' AND category_id = '$categoryId'");
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $row = $result->fetch();

        return $row['count'];
    }

    public static function getProductsByIds($idsArray) {
        
        $products = array();
        
        $idsString = implode(',',$idsArray);

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
    public static function getRecommendedProductList(){
        
        $products = array();
        
        $sql = "SELECT * FROM product WHERE status = '1' AND "
                . "is_recommended = '1'";
        
        $result = self::getConnection()->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        
        $i = 0;
        while($row = $result->fetch()){
            $products[$i]['id'] = $row['id'];
            $products[$i]['image'] = $row['image'];
            $products[$i]['name'] = $row['name'];
            $products[$i]['price'] = $row['price'];
            $products[$i]['is_new'] = $row['is_new'];
            $i++;
        }
        return $products;
    }

}
