<?php

include_once ROOT.'/models/Category.php';
include_once ROOT.'/models/Product.php';

/**
 * Description of SiteController
 *
 * @author rodnoy
 */
class SiteController {
    
    public function actionIndex(){
        
        $categories = array();
        $categories = Category::getCategoriesList();
        
        $latestProducts = array();
        $latestProducts = Product::getLatestProducts(5);
        
        require_once ROOT.'/views/site/index.php';
        
        return true;
    }
}
