<?php

class AdminController extends Controller
{
    public function actionIndex()
    {
        $this->layout = false;
        
        // get Yii js component
        $cs = Yii::app()->clientScript;
        
        // register ExtJS css file
        $cs->registerCssFile(Yii::app()->request->baseUrl . 'js/extjs/resources/css/ext-all.css');
        // register ExtJS core file
        $cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/extjs/ext-debug.js');
        
        $cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/app.js');
        
        $this->render('index');
    }
    
//    public function actionCreate()
//    {
//        $request_body = file_get_contents('php://input');
//        $request_body = json_decode($request_body, true);
//        
//        $user = new User();
//        $user->attributes = $request_body;
//        if(!$user->save()) 
//        {
//            print_r($user->getErrors());
//        }
//        
//    }
    
//    public function actionList()
//    {
//        $arrData = User::model()->findAll();
//        
//        $arrUsers = CJSON::encode($arrData);
//        header('Content-type: application/x-json');
//        echo $arrUsers;
//    }
    
//    public function actionUpdate() 
//    {
//        $request_body = file_get_contents('php://input');
//        $request_body = json_decode($request_body, true);
//        
//        $user = User::model()->findByPk($request_body['id']);
//        $user->name = $request_body['name'];
//        $user->email = $request_body['email'];
//        
//        if(!$user->save())
//        {
//            print_r($user->getErrors());
//        }
//    }
    
//    public function actionDelete()
//    {
//        $request_body = file_get_contents('php://input');
//        $request_body = json_decode($request_body, true);
//        
//        $user = User::model()->findByPk($request_body['id']);
//        
//        if(!$user->delete()) 
//        {
//            print_r($user->getErrors());
//        }
//    }
    
    public function actionList()
    {
        $offset = Yii::app()->request->getParam('start');
        $limit = Yii::app()->request->getParam('limit');
        
        $category = Yii::app()->request->getParam('category');
        $brand = Yii::app()->request->getParam('brand');
        $in_stock = Yii::app()->request->getParam('in_stock');
        
        $search = Yii::app()->request->getParam('search_text');
        $query = Yii::app()->request->getParam('query');
        $single_id = Yii::app()->request->getParam('single_id');
        
        $criteria = new CDbCriteria;
        $criteria->offset = $offset;
        $criteria->limit = $limit;
        
        if(!isset($search))
        {
            $criteria->compare('id_category', $category);
            $criteria->compare('id_brand', $brand);
            $criteria->compare('in_stock', $in_stock);
        } 
        else 
        {
            $criteria->compare('name', $search, true);
        }
        
        if(isset($query))
        {
            $criteria->compare('name', $query, true);
            $criteria->limit = 10;
        }
        
        if(isset($single_id))
        {
            $criteria->compare('id', $single_id);
        }
        
        $arrItems = Item::model()->getItemsList($criteria);
        $total = Item::model()->count($criteria);
        
        $result = array('items' => $arrItems, 'total' => $total);
        
        $result = CJSON::encode($result);
        header('Content-type: application/x-json');
        echo $result;
    }
    
    public function actionCategory() {
        $arrData = Category::model()->findAll();
        
        $arrCategories = CJSON::encode($arrData);
        header('Content-type: application/x-json');
        
        echo $arrCategories;
    }
    
    public function actionBrand() {
        $arrData = Brand::model()->findAll();
        
        $arrBrand = CJSON::encode($arrData);
        header('Content-type: application/x-json');
        
        echo $arrBrand;
    }
    
    public function actionCreate()
    {
        $name = Yii::app()->request->getParam('name');
        $category = Yii::app()->request->getParam('category');
        $brand = Yii::app()->request->getParam('brand');
        $price = Yii::app()->request->getParam('price');
        $description = Yii::app()->request->getParam('description');
        $in_stock = Yii::app()->request->getParam('in_stock');
        
        $item = new Item();
        
        $item->name = $name;
        $item->id_category = $category;
        $item->id_brand = $brand;
        $item->price = $price;
        $item->description = $description;
        $item->in_stock = $in_stock;
        
        if(!$item->save()) 
        {
            print_r($item->getErrors());
        }        
    }
    
    public function actionUpdate() 
    {
        $id = Yii::app()->request->getParam('id');
        $name = Yii::app()->request->getParam('name');
        $category = Yii::app()->request->getParam('category');
        $brand = Yii::app()->request->getParam('brand');
        $price = Yii::app()->request->getParam('price');
        $description = Yii::app()->request->getParam('description');
        $in_stock = Yii::app()->request->getParam('in_stock', 0);
        
        $item = Item::model()->findByPk($id);
        
        $item->name = $name;
        $item->id_category = $category;
        $item->id_brand = $brand;
        $item->price = $price;
        $item->description = $description;
        $item->in_stock = $in_stock;
        
        if(!$item->save()) 
        {
            print_r($item->getErrors());
        }           
    }
    
    public function actionGetItem() 
    {
        $id = Yii::app()->request->getParam('id');
        
        $item = Item::model()->findByPk($id);
        $item = CJSON::encode($item);
        echo $item;
    }
    
    public function actionDelItem() {
        $id = Yii::app()->request->getParam('id');
        $item = Item::model()->findByPk($id);
        
        if(!$item->delete()) 
        {
            print_r($item->getErrors());
        }
    }
    
    public function actionAutocomplete() 
    {
        $query = Yii::app()->request->getParam('query');
        
        $criteria = new CDbCriteria;
        $criteria->compare('name', $query, true);
        
        $arrItems = Item::model()->getItemsList($criteria);

        $result = array();
        foreach ($arrItems as $key => $value)
        {
            $result[$key]['id'] = $arrItems[$key]['id'];
            $result[$key]['name'] = $arrItems[$key]['name'];
        }
        
        $result = CJSON::encode($result);
        header('Content-type: application/x-json');
        echo $result;
    }
}