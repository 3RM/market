<?php

/**
 * Контроллер AdminOrderController
 * Управление заказами в админпанели
 */
class AdminOrderController extends AdminBase {

    /**
     * Проверка на права доступа админа
     */
    public function __construct() {
        self::checkAdmin();
    }

    /**
     * Action для страницы "Управление заказами"
     */
    public function actionIndex() {

        $ordersList = Order::getOrderList();

        require_once ROOT . '/views/admin_order/index.php';

        return true;
    }
    
    /**
     * 
     * Action для страницы "Просмотр товара"
     */
    public function actionView($id) {
        
        //Получаем информацию о конкретном заказе
        $order = Order::getOrderById($id);

        //Получаем массив с идентификаторами и колличеством товаров
        $productsQuantity = json_decode($order['products'], true);
        
        //Получаем массив иденкификаторов товаров
        $productsIds = array_keys($productsQuantity);
        
        //Получаем массив товаров по идентификаторам в заказе
        $products = Product::getProductsByIds($productsIds);

        require_once ROOT . '/views/admin_order/view.php';

        return true;
    }
    
    /**
     * Action для страницы "Редактирование заказа"
     */
    public function actionUpdate($id){
        
        $order = Order::getOrderById($id);
        
        if(isset($_POST['update'])){
            
            //Считываем данные из формы
            $userName = filter_input(INPUT_POST, 'userName');
            $userPhone = filter_input(INPUT_POST, 'userPhone');
            $userComment = filter_input(INPUT_POST, 'userComment');
            $date = filter_input(INPUT_POST, 'date');
            $status = filter_input(INPUT_POST, 'status');
            
            // Сохраняем изменения
            Order::updateOrderById($id, $userName, $userPhone, $userComment, $date, $status);

            // Перенаправляем пользователя на страницу управлениями заказами
            header("Location: /admin/order/view/$id");
            
        }
        
        require_once ROOT.'/views/admin_order/update.php';
        
        return true;
    }

    /**
     * Action для страницы "Удалить заказ"
     */
    public function actionDelete($id) {

        Order::deleteOrderById($id);
        header('Location: /admin/order/');
    }

}
