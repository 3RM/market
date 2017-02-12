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
        
        // Список категорий для левого меню
        $categories = array();
        $categories = Category::getCategoriesList();
        
        // Список последних товаров
        $latestProducts = array();
        $latestProducts = Product::getLatestProducts(6);
        
        // Список товаров для слайдера
        $sliderProducts = array();
        $sliderProducts = Product::getRecommendedProductList();
        
        require_once ROOT.'/views/site/index.php';
        
        return true;
    }
}
