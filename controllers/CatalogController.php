<?php

include_once ROOT.'/models/Category.php';
include_once ROOT.'/models/Product.php';

/**
 * Description of SiteController
 *
 * @author rodnoy
 */
class CatalogController {
    
    public function actionIndex(){
        
        $categories = array();
        $categories = Category::getCategoriesList();
        
        $latestProducts = array();
        $latestProducts = Product::getLatestProducts(12);
        
        require_once ROOT.'/views/catalog/index.php';
        
        return true;
    }
    
    public function actionCategory($categoryId){
        
        $categories = array();
        $categories = Category::getCategoriesList();
        
        $categoryProducts = array();
        $categoryProducts = Product::getProductsListByCategory($categoryId,12);
        
        require_once ROOT.'/views/catalog/category.php';
        
        return true;        
    }
}
