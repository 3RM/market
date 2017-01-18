<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cabinetcontroller
 *
 * @author rodnoy
 */
class CabinetController {
    
    public function actionIndex(){
        
        $userId = User::checkLogged();
        
        echo $userId;
        
        require_once ROOT.'/views/cabinet/index.php';
        
        return true;
    }
    
}
