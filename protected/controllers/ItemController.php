<?php

class ItemController extends Controller {
    private $_imageFolder;
    private $_itemsImageFolder;

    public function actionList()  //filters
    {
        $this->_imageFolder = Yii::app()->params['img_folder'];
        $this->_itemsImageFolder = Yii::app()->params['items_imgfolder'];
        
        $category = Yii::app()->request->getParam('category');
        $brand = Yii::app()->request->getParam('brand');
        $in_stock = Yii::app()->request->getParam('in_stock');
        
        $result = array();
        
        $criteria = new CDbCriteria();
        if (isset($category))
        {
            $criteria->compare('id_category', $category);
            $result['selected']['category'] = $category;
        }
        if (isset($brand))
        {
            $criteria->compare('id_brand', $brand);
            $result['selected']['brand'] = $brand;
        }
        if (isset($in_stock)) 
        {          
            $criteria->compare('in_stock', $in_stock);
            $result['selected']['in_stock'] = $in_stock;
        } 
        
        $count = Item::model()->count($criteria);
        $pages=new CPagination($count);
        if (isset($result['selected']))
        {
            $pages->params = $result['selected'];
        }
        
        $pages->pageSize=3;
        $pages->applyLimit($criteria);
        $arrData = Item::model()->findAll($criteria);
        
        foreach ($arrData as $index => $item) {
            $result[$index]['id'] = $item->id;
            $thumbnailPath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $this->_imageFolder . DIRECTORY_SEPARATOR . $this->_itemsImageFolder . DIRECTORY_SEPARATOR . $item->id . DIRECTORY_SEPARATOR . "_thmb.jpg";
            $thumbnailUrl = Yii::app()->getBaseUrl(true) . '/' . $this->_imageFolder . '/' . $this->_itemsImageFolder . '/' . $item->id . '/' . "_thmb.jpg";
            if (file_exists($thumbnailPath)) {
                $result[$index]['imgpath'] = $thumbnailUrl;
            }
            $result[$index]['name'] = $item->name;
            $result[$index]['category'] = $item->category->name;
            $result[$index]['brand'] = $item->brand->name;
            $result[$index]['price'] = $item->price;
            $result[$index]['description'] = $this->truncate($item->description, 15);
            $result[$index]['in_stock'] = $item->in_stock;
        }
        $category_options = Category::model()->findAll();
        array_unshift($category_options, '');
        $brand_options = Brand::model()->findAll();
        array_unshift($brand_options, '');
        $result['category_options'] = CHtml::listData($category_options, 'id', 'name');
        $result['brand_options'] = CHtml::listData($brand_options, 'id', 'name');
        $result['pages'] =  $pages;
        
//        print_r($result);
//        exit();
        
        $this->render('list', array('arrData'=>$result));
    }

    public function actionView()
    {
        Yii::app()->clientScript->registerScriptFile('/js/lib/js/jquery.lightbox-0.5.js');
        Yii::app()->clientScript->registerCssFile('/js/lib/css/jquery.lightbox-0.5.css');
        Yii::app()->clientScript->registerScriptFile('/js/lib/item/lightbox_runner.js');

        
        $itemId = Yii::app()->request->getParam('id');
        $item = Item::model()->findByPk($itemId);
        $arrImages = $this->getImeges($itemId);
        
        $result['id'] = $item->id;
        $result['name'] = $item->name;
        $result['category'] = $item->category->name;
        $result['brand'] = $item->brand->name;
        $result['price'] = $item->price;
        $result['description'] = $item->description;
        $result['in_stock'] = $item->in_stock;
        $result['images'] = $arrImages;
        $result['main_image_index'] = $this->getMainIndex($arrImages, 'main_');
        
        $this->render('view', array('item' => $result));
    }
    
    private function getMainIndex($arrImages, $prefixName) {
    $index = 0;
    foreach ($arrImages as $key => $value) { //strpos
//        array_map($callback, $arr1)
//            array_filter($input, $callback)
        if(preg_match("/^{$prefixName}\w*/", $value['name']))
        {
            $index = $key;
        }
    }
    return $index;
}

    public function truncate($text, $limit = 0) {
        $help_str = "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя";
        if (!empty($limit) && (str_word_count($text, 0, $help_str) > $limit)) {
            $arrWords = str_word_count($text, 2, $help_str);
            $arrWordsPositions = array_keys($arrWords);
            $text = substr($text, 0, $arrWordsPositions[$limit]) . '...';
        }

        return $text;
    }

    public function getImeges($itemId) 
    {
        $arrImages = array();
        $this->_imageFolder = Yii::app()->params['img_folder'];
        $this->_itemsImageFolder = Yii::app()->params['items_imgfolder'];

        // create img folder path
        $imgFolderPath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $this->_imageFolder . DIRECTORY_SEPARATOR . $this->_itemsImageFolder . DIRECTORY_SEPARATOR . $itemId;

        if (file_exists($imgFolderPath)) {
            // read images from folder
            if ($handle = @opendir($imgFolderPath)) {
                while (false !== ($filename = readdir($handle))) {
                    if (is_dir($imgFolderPath . DIRECTORY_SEPARATOR . $filename) || $filename == '.' || $filename == '..' || $filename == '_thmb.jpg')
                        continue;

                    // get image name
                    $name = substr($filename, 0, strpos($filename, '.'));

                    $size = getimagesize($imgFolderPath . DIRECTORY_SEPARATOR . $filename);

                    // create image params array
                    $arrImages[] = array(
                        'folder' => $itemId,
                        'url' => Yii::app()->getBaseUrl(true) . '/' . $this->_imageFolder . '/' . $this->_itemsImageFolder . '/' . $itemId . '/' . $filename,
                        'name' => $name,
                        'imageName' => $filename
                    );
                }
            } else
                throw new HttpException("Could not open folder '{$imgFolderPath}'.");
        }

//        $json = CJSON::encode(array('arrImages' => $arrImages));
//        header('Content-type: application/x-json');
//        echo $json;
        return $arrImages;
    }

}