<?php
/**
 * Description of CartController
 *
 * @author rodnoy
 */
class CartController {
    
    public function actionAdd($id){
        //Добавляем товар в корзину
        Cart::addProduct($id);        
               
        $referrer = $_SERVER['HTTP_REFERER'];
        header("Location: $referrer");
    }
    
    public function actionAddAjax($id){
        //добавление товара в корзину
        echo Cart::addProduct($id);
        return true;
    }
    
    public function actionIndex(){
        
        $categories = array();
        $categories = Category::getCategoriesList();
        
        $productsInCart = false;
        
        //получим данные из корзины
        $productsInCart = Cart::getProducts();
        
        if($productsInCart){
            //получаем полную информацию о товарах списка
            $productsIds = array_keys($productsInCart);
            
            $products = Product::getProductsByIds($productsIds);
            
            //получаем обущую стоимость товаров
            $totalPrice = Cart::getTotalPrice($products);
        }
        
        require_once ROOT.'/views/cart/index.php';
        
        return true;
        
    }
    
}
