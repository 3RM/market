<?php

/**
 * Контроллер  AdminCategoryController
 * Управление категориями в админпанели
 */
class AdminCategoryController extends AdminBase{
    
    /**
     * Проверка доступа на права админа
     */
    public function __construct() {
        self::checkAdmin();
    }
    
    /**
     * Action для страницы "Управления категориями"
     * @return boolean <p>Результат выполнения метода<p>
     */
    public function actionIndex(){
        
        $categoriesList = array();
        $categoriesList = Category::getCategoriesListAdmin();
        
        require_once ROOT.'/views/admin_category/index.php';
        return true;
        
    }
    
    /**
     * Action для страницы "Удалить категорию"
     * @param int $id <p>id категории</p>
     * @return boolean <p>Результат выполнения метода<p>
     */
    public function actionDelete($id){
        Category::deleteCategoryById($id);
        header('Location: /admin/category');
    }
    
    /**
     * Action для страницы "Добавить категорию"
     * @return boolean <p>Результат выполненеия метода</p>
     */
    public function actionCreate(){
        
        //Если форма отправлена
        if(isset($_POST['create'])){
            
            $options = array();
            
            $options['name'] = filter_input(INPUT_POST, 'name');
            $options['sort_order'] = filter_input(INPUT_POST, 'sort_order');
            $options['status'] = filter_input(INPUT_POST, 'status');
            
            $errors = false;
            //Валидация полей
            if(!isset($options['name']) or empty($options['name'])){
                $errors[] = 'Заполните поле name';
            }
            
            //Если ошибок нет, сохраняем категорию
            if($errors == false){
                Category::createCategory($options);
                header('Location: /admin/category');
            }
            
        }
        require_once '/views/admin_category/create.php';
        return true;
    }
    
    /**
     * Action для страницы "Редактировать категорию"
     * @param int $id <p>id категории</p>
     * @return boolean <p>Результат выполнения метода<p>
     */
    public function actionUpdate($id) {

        //Получаем данные о конкретной категории
        $category = Category::getCategoryById($id);

        if (isset($_POST['update'])) {
            //Если форма отправлена
            //Получаем данные из формы редактирования.
            $options = array();

            $options['name'] = filter_input(INPUT_POST, 'name');
            $options['sort_order'] = filter_input(INPUT_POST, 'sort_order');
            $options['status'] = filter_input(INPUT_POST, 'status');           

            //Флаг ошибок формы
            $errors = false;

            //Валидация полей (можно расширить)
            if (!isset($options['name']) or empty($options['name'])) {
                $errors[] = "Заполните поля";
            }

            if ($errors == false) {
                //Сохраняем изменения
                Category::updateCategoryById($id,$options);
                header("Location: /admin/category");
            }
        }

        require_once '/views/admin_category/update.php';
        return true;
    }
}
