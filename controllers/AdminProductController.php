<?php

/**
 * Контроллер  AdminProductController
 * Управление товарами в админпанели
 */
class AdminProductController extends AdminBase {

    /**
     * Проверка доступа на права админа
     */
    public function __construct() {
        self::checkAdmin();
    }

    /**
     * Action для страницы "Управления товарами"
     * @return boolean <p>Результат выполнения метода<p>
     */
    public function actionIndex() {

        //Получаем список товаров
        $productsList = array();
        $productsList = Product::getProductList();

        require_once ROOT . '/views/admin_product/index.php';
        return true;
    }

    /**
     * Action для старницы "Удалить товар"
     * @param int $id <p>id товара</p>
     * @return boolean <p>Результат выполнения метода<p>
     */
    public function actionDelete($id) {

        Product::deleteById($id);
        header('Location: /admin/product');

        return true;
    }

    /**
     * Action для страницы "Редактировать товар"
     * @param int $id <p>id товара</p>
     * @return boolean <p>Результат выполнения метода<p>
     */
    public function actionUpdate($id) {

        //Получаем массив катеегорий для формы
        $categoriesList = array();
        $categoriesList = Category::getCategoriesListAdmin();

        //Получаем данные о конкретном продукте
        $product = Product::getProductById($id);

        if (isset($_POST['update'])) {
            //Если форма отправлена
            //Получаем данные из формы редактирования.
            $options = array();

            $options['name'] = filter_input(INPUT_POST, 'name');
            $options['code'] = filter_input(INPUT_POST, 'code');
            $options['price'] = filter_input(INPUT_POST, 'price');
            $options['category_id'] = filter_input(INPUT_POST, 'category_id');
            $options['brand'] = filter_input(INPUT_POST, 'brand');
            $options['availability'] = filter_input(INPUT_POST, 'availability');
            $options['description'] = filter_input(INPUT_POST, 'description');
            $options['is_new'] = filter_input(INPUT_POST, 'is_new');
            $options['is_recommended'] = filter_input(INPUT_POST, 'is_recommended');
            $options['status'] = filter_input(INPUT_POST, 'status');

            //Флаг ошибок формы
            $errors = false;

            //Валидация полей (можно расширить)
            if (!isset($options['name']) or empty($options['name'])) {
                $errors[] = "Заполните поля";
            }

            if ($errors == false) {
                //Сохраняем изменения
                if (Product::updateProductById($id, $options)) {
                    //Проверим, загружалось ли изображение через форму
                    if (is_uploaded_file($_FILES['image']["tmp_name"])) {
                        //Если загружалось, перемещаем его в нужную папку, даем новое имя
                        move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.jpg");
                    }
                }
                header("Location: /admin/product");
            }
        }

        require_once '/views/admin_product/update.php';
        return true;
    }

    /**
     * Action для страницы "Добавить товар"
     * @return boolean <p>Результат выполнения метода</p>
     */
    public function actionCreate() {

        //Получаем массив категорий для формы
        $categoriesList = array();
        $categoriesList = Category::getCategoriesListAdmin();

        //Обработка формы
        if (isset($_POST['add'])) {
            //Если форма отправлена

            $options = array();

            $options['name'] = filter_input(INPUT_POST, 'name');
            $options['code'] = filter_input(INPUT_POST, 'code');
            $options['price'] = filter_input(INPUT_POST, 'price');
            $options['category_id'] = filter_input(INPUT_POST, 'category_id');
            $options['brand'] = filter_input(INPUT_POST, 'brand');
            $options['availability'] = filter_input(INPUT_POST, 'availability');
            $options['description'] = filter_input(INPUT_POST, 'description');
            $options['is_new'] = filter_input(INPUT_POST, 'is_new');
            $options['is_recommended'] = filter_input(INPUT_POST, 'is_recommended');
            $options['status'] = filter_input(INPUT_POST, 'status');

            //Флаг ошибок формы
            $errors = false;

            //Валидация полей (можно расширить)
            if (!isset($options['name']) or empty($options['name'])) {
                $errors[] = "Заполните поля";
            }

            if ($errors == false) {
                //Если ошибок нет
                //Добавляем новый товар
                //Если запись прошла успешно, возвращает id добавленной записи
                $id = Product::createProduct($options);
                //Если запись добавлена
                if ($id) {
                    //Проверим, загружалось ли изображение через форму
                    if (is_uploaded_file($_FILES['image']["tmp_name"])) {
                        //Если загружалось, перемещаем его в нужную папку, даем новое имя
                        move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.jpg");
                    }
                }
                //Перенаправляем на страницу управления товарами
                header("Location: /admin/product");
            }
        }

        require_once '/views/admin_product/create.php';
        return true;
    }

}
