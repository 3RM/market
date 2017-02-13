<?php

/**
 * Description of AdminController
 *
 * @author rodnoy
 */
class AdminController extends AdminBase{

    /**
     * Action для стартовой страницы "Админпанели"
     */
    public function actionIndex() {
        //Проверка доступа
        self::checkAdmin();
        
        require_once ROOT."/views/admin/index.php";
        return true;
    }

}
