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
    
    /**
     * Action для страницы "Корзина"
     */
    public function actionIndex()
    {
        // Список категорий для левого меню
        $categories = Category::getCategoriesList();
        // Получим идентификаторы и количество товаров в корзине
        $productsInCart = Cart::getProducts();
        if ($productsInCart) {
            // Если в корзине есть товары, получаем полную информацию о товарах для списка
            // Получаем массив только с идентификаторами товаров
            $productsIds = array_keys($productsInCart);
            // Получаем массив с полной информацией о необходимых товарах
            $products = Product::getProductsByIds($productsIds);
            // Получаем общую стоимость товаров
            $totalPrice = Cart::getTotalPrice($products);
        }
        // Подключаем вид
        require_once(ROOT . '/views/cart/index.php');
        return true;
    }
    
    public function actionCheckout(){
        
        //Список категорий для левого меню
        $categories = array();
        $categories = Category::getCategoriesList();
        
        //Статус успешного оформления заказа
        $result = false;
        
        //Форма отправлена?
        if(isset($_POST['checkout'])){
            //Форма отправлена - да
            
            //Считываем данные из формы
            $userName = filter_input(INPUT_POST, 'userName');
            $userPhone = filter_input(INPUT_POST, 'userPhone');
            $userComment = filter_input(INPUT_POST, 'userComment');
            
            //Валидация полей
            $errors = false;
            if(!User::checkName($userName)){
                $errors[] = 'Неправильное имя';
            }
            if(!User::checkPhone($userPhone)){
                $errors[] = 'Неправильный номер телефона';
            }
            //Форма прошла валидацию?
            if($errors == false){
                //Форма заполенна корректно - да
                //Сохраняем заказ в БД
                
                //собираем инфу о заказе
                $productsInCart = Cart::getProducts();
                if(User::isGuest()){
                    $userId = false;
                }else{
                    $userId = User::checkLogged();
                }
                
                //Сохраняем заказ в БД
                $result = Order::save($userName,$userPhone,$userComment,$userId,$productsInCart);
                
                if($result){
                    //Очищаем корзину
                    Cart::clear();
                }
                
            }else{
                //Форма заполенна корректно - нет
                
                //Итоги: общая стоимость, количество товаров
                $productsInCart = Cart::getProducts();
                $productsIds = Cart::getProducts($productsInCart);
                $products = Product::getProductsByIds($productsIds);
                $totalPrice = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();
            }
            
        }else{
            //Форма отправлена - нет
            
            //Получаем данные из корзины
            $productsInCart = Cart::getProducts();
            
            //В корзине есть товары?
            if($productsInCart == false){
                //В корзине есть товары - нет
                header("Location: /");
            }else{
                //В корзине есть товары - да
                
                //Итоги: общая стоимость, количество товаров
                $productsIds = array_keys($productsInCart);
                $products = Product::getProductsByIds($productsIds);
                $totalPrice = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();
                
                $userName = false;
                $userPhone = false;
                $userComment = false;
                
                //Пользователь гость?
                if(User::isGuest()){
                    //true
                    //Значения для формы пустые                    
                }else{
                    //false
                    //Получаем инфу о пользователе
                    $userId = User::checkLogged();
                    $user = User::getUserById($userId);
                    //Подставляем в форму
                    $userName = $user['name'];
                }
            }
        }
        
        require_once ROOT.'/views/cart/checkout.php';
        
        return true;
    }
    
    public function actionDelete($id){
        
        Cart::deleteItemFromCart($id);
        header('Location: /cart/');
    }
    
}
