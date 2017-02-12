<?php

/**
 * Description of Cart
 *
 * @author rodnoy
 */
class Cart {

    /**
     * Добавление товара в корзину
     * @param string $id
     */
    public static function addProduct($id) {

        $id = intval($id);

        $productsInCart = array();

        if (isset($_SESSION['products'])) {
            $productsInCart = $_SESSION['products'];
        }

        if (array_key_exists($id, $productsInCart)) {
            $productsInCart[$id] ++;
        } else {
            $productsInCart[$id] = 1;
        }

        $_SESSION['products'] = $productsInCart;

        return self::countItems();
    }

    /**
     * Подсчет количества товаров в корзине (в сессии)
     * @return int
     */
    public static function countItems() {
        if (isset($_SESSION['products'])) {
            $count = 0;
            foreach ($_SESSION['products'] as $id => $quantity) {
                $count = $count + $quantity;
            }
            return $count;
        } else {
            return 0;
        }
    }

    public static function getProducts() {

        if ($_SESSION['products']) {
            return $_SESSION['products'];
        } else {
            return false;
        }
    }

    public static function getTotalPrice($products) {

        $productsInCart = self::getProducts();

        $total = 0;

        if ($productsInCart) {
            foreach ($products as $item) {
                $total += $item['price'] * $productsInCart[$item['id']];
            }
        }

        return $total;
    }

    /**
     * Очищает корзину
     */
    public static function clear() {
        if (isset($_SESSION['products'])) {
            unset($_SESSION['products']);
            //После выполнения unset массив products удалится и появится ошибка,
            //что переменной products не существует в представлении /views/cart/index/php
            //поэтому нужно после unset присвоить products пустой массив
            $_SESSION['products'] = array();
        }
    }

    /**
     * Удаляет товар с указанным id из корзины
     * @param integer $id <p>id товара</p>
     */
    public static function deleteItemFromCart($id) {

        if (isset($_SESSION['products'])) {
            //Получим идентификаторы и количество товаров в корзине 
            $productsInCart = self::getProducts();
            //Ищем совпадение переданого id в массиве
            if (array_key_exists($id, $productsInCart)) {
                //Если найден id - удаляем
                unset($productsInCart[$id]);
                //Перезаписываем новый массив продуктов в сессию
                $_SESSION['products'] = $productsInCart;
            }
        }

        return $productsInCart;
    }

}
