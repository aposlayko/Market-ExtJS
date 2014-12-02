<?php

class ImageController extends Controller {

    /**
     * Default sizes of thumbnail image
     */
    const THUMBNAIL_WIDTH = 100;
    const THUMBNAIL_HEIGHT = 100;

    /**
     * Max allowed image amount
     */
    const MAX_IMAGE_AMOUNT = 20;

    private $_imageMaxSize;
    private $_imageFolder;
    private $_itemsImageFolder;

    /**
     * List of allowed img extensions
     * @var array
     */
    private $_arrAllowedExts = array('.jpg', '.png', '.gif');

    public function init() {
        $this->_imageFolder = Yii::app()->params['img_folder'];
        $this->_itemsImageFolder = Yii::app()->params['items_imgfolder'];

        $this->_imageMaxSize = ini_get('upload_max_filesize') * 1024;

        parent::init();
    }

    /**
     * Returns the list of images for specified item.
     * @param int $itemId
     */
    public function actionList() {

        // get item id
        $itemId = Yii::app()->request->getParam('itemId');

        $arrImages = array();

        // create img folder path
        $imgFolderPath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $this->_imageFolder . DIRECTORY_SEPARATOR . $this->_itemsImageFolder . DIRECTORY_SEPARATOR . $itemId;

        if (file_exists($imgFolderPath)) {
            // read images from folder
            if ($handle = @opendir($imgFolderPath)) {
                while (false !== ($filename = readdir($handle))) {
                    if (is_dir($imgFolderPath . DIRECTORY_SEPARATOR . $filename) || $filename == '.' || $filename == '..' || $filename == '_thmb.jpg' || $filename == '_thmb_av.jpg')
                        continue;

                    // get image name
                    $name = substr($filename, 0, strpos($filename, '.'));
                    $size = getimagesize($imgFolderPath . DIRECTORY_SEPARATOR . $filename);
                    $arrSize = $this->getResized($size[0], $size[1], 100, 100);

                    // create image params array
                    $arrImages[] = array(
                        'folder' => $itemId,
                        'url' => Yii::app()->getBaseUrl(true) . '/' . $this->_imageFolder . '/' . $this->_itemsImageFolder . '/' . $itemId . '/' . $filename,
                        'name' => $name,
                        'imageName' => $filename,
                        'width' => $arrSize['width'],
                        'height' => $arrSize['height']
                    );
                }
            } else
                throw new HttpException("Could not open folder '{$imgFolderPath}'.");
        }

        $json = CJSON::encode(array('arrImages' => $arrImages));
        header('Content-type: application/x-json');
        echo $json;
    }

    /**
     * Saves uploaded image.
     */
    public function actionUpload() {
        // get item id
        $itemId = Yii::app()->request->getParam('itemId');

        $success = false;
        $message = '';

        if (isset($_FILES['imageFile'])) {
            // get original file name
            $originalImageName = $_FILES['imageFile']['name'];
            // transliterate in case of russian name
            $imageName = $this->transliterate($originalImageName);
            // get file path
            $imageTmpPath = $_FILES['imageFile']['tmp_name'];

            // get image extension
            $ext = strtolower(strrchr($imageName, '.'));

            // check if the image is valid
            if (!is_uploaded_file($imageTmpPath))
                $message = Yii::t('php', 'Неверный формат изображения. Вероятно размер изображения слишком велик. Максимальный размер изображения не должен превышать 2 Mb.');

            // check if extension is correct
            else if (!in_array($ext, $this->_arrAllowedExts))
                $message = Yii::t('php', 'Неверный тип изображения:') . ' ' . $ext;

            if (empty($message)) {
                // create img folder path
                $imgFolderPath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $this->_imageFolder . DIRECTORY_SEPARATOR . $this->_itemsImageFolder . DIRECTORY_SEPARATOR . $itemId;

                if ($this->folderExists($imgFolderPath, $message)) {
                    // get current images amount
                    $imageCount = count(scandir($imgFolderPath)) - 2;

                    // check if max allowed image amount is reached
                    if ($imageCount == self::MAX_IMAGE_AMOUNT) {
                        $message = Yii::t('php', 'Максимальное количество изображений: ' . self::MAX_IMAGE_AMOUNT);
                        $success = false;
                    } else {
                        // create img path
                        $imagePath = $imgFolderPath . DIRECTORY_SEPARATOR . $imageName;

                        // check if such image name already exists
                        if (file_exists($imagePath)) {
                            $message = Yii::t('php', 'Изображение с названием "{image_name}" уже существует.', array('{image_name}' => $originalImageName));
                            $success = false;
                        } else {
                            // save image
                            if (move_uploaded_file($imageTmpPath, $imagePath)) {
                                if ($imageCount == 0) {
                                    $this->getThumbnail($imagePath, 100, 100, '_thmb.jpg', $itemId);
                                    //добавить первой картинке префикс
                                }
                                $success = true;
                            }
                        }
                    }
                }
            }
        }
        $json = CJSON::encode(array('success' => $success, 'message' => $message));
        header('Content-type: text/html');
        echo $json;
    }

    public function actionDeleteImage() {
        $folder = Yii::app()->request->getParam('folder');
        $imageName = Yii::app()->request->getParam('imageName');

        $success = false;
        $message = '';

        $path = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $this->_imageFolder . DIRECTORY_SEPARATOR . $this->_itemsImageFolder . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR;
        $fullPath = $path . $imageName;
        $thumbnailPath = $path . '_thmb.jpg';
        $thumbnailBigPath = $path . '_thmb_av.jpg';
        if (file_exists($fullPath)) {
            if (unlink($fullPath)) {
                $success = true;
                $message = "Файл успешно удален";
                $imageCount = count(scandir($path)) - 3;
                if ($imageCount === 1) {
                    unlink($thumbnailPath);
                    unlink($thumbnailBigPath);
                }
            }
        } else {
            $success = false;
            $message = "Файл не найден";
        }
        $message = $path;

        $json = CJSON::encode(array('success' => $success, 'message' => $message));
        header('Content-type: text/html');
        echo $json;
    }

    public function actionChooseAvatar() {
        $itemFolder = Yii::app()->request->getParam('folder');
        $imageName = Yii::app()->request->getParam('imageName');

        $success = false;
        $message = '';
        $perfixName = 'main_';

        $folderPath = Yii::getPathOfAlias('webroot') . '/' . $this->_imageFolder . '/' . $this->_itemsImageFolder . '/' . $itemFolder . '/';
        $thumbnailPath = $folderPath . '_thmb.jpg';
        $imagePath = $folderPath . $imageName;

        if (file_exists($thumbnailPath))
        {
            unlink($thumbnailPath);
        }
        
        $this->getThumbnail($imagePath, 100, 100, '_thmb.jpg', $itemFolder);
        
        $this->deletePrefixes($folderPath, $perfixName);
        if(!file_exists($folderPath . $imageName))
        {
            $imageName = substr($imageName, strlen($perfixName));
        }
        $newName = $this->tooglePrefix($folderPath, $imageName, $perfixName, true);
        
//        $json = CJSON::encode(array('newPictureName' => $newName));
//        header('Content-type: application/x-json');
//        echo $json;
    }
    
    /**
     * Delete all prefixes in file names of selected folder.
     * @param string $folderPath
     * @param string $prefixName
     * @return int
     */
    private function deletePrefixes($folderPath, $prefixName)
    {
        $prefixesChanged = 0;
        if ($handle = @opendir($folderPath))
        {
            while (false !== ($filename = readdir($handle))) 
            {
                if (is_dir($folderPath . DIRECTORY_SEPARATOR . $filename) || $filename == '.' || $filename == '..' || $filename == '_thmb.jpg' || $filename == '_thmb_av.jpg')
                    continue;

                $this->tooglePrefix($folderPath, $filename, $prefixName, false);
            }
        }
    }
    
    /**
     * Delete or add prefix depending on $status (true - add, false - delete).
     * @param string $folderName
     * @param string $fileName
     * @param string $prefixName
     * @param bool $status
     * @return bool
     */
    private function tooglePrefix($folderName, $fileName, $prefixName, $status)
    {
        $success = true;
        $newName = '';
        if($status)
        {
            if(!$this->hasPrefix($fileName, $prefixName))
            {
                rename($folderName . $fileName, $folderName . $prefixName . $fileName);
                $newName = $prefixName . $fileName;
            }            
        }
        else 
        {
            if($this->hasPrefix($fileName, $prefixName))
            {
                $prefixLength = strlen($prefixName);
                $newFileName = substr($fileName, $prefixLength);
                rename($folderName . $fileName, $folderName . $newFileName);
                $newName = $newFileName;
            }
            else
            {
                $success = false;
                $newName = $fileName;
            }
        }
        return $newName;
    }

     /**
     * Check prefix in string $name.
     * @param string $name
     * @param string $prefix
     * @return bool
     */
     public static function hasPrefix($name, $prefix)
    {
        $result = false;
        $regExp = "/^{$prefix}\w*/";
        $result = preg_match($regExp, $name);
        return $result;
    }

    /**
     * Checks if specified dir exists and tries to create it.
     * @param string $folderPath
     * @param string $message
     * @return bool
     */
    private function folderExists($folderPath, &$message) {
        // try to create folder
        if (!file_exists($folderPath))
            @mkdir($folderPath);

        if (!file_exists($folderPath)) {
            $message = Yii::t('php', 'Папка "{folder}" не существует.', array('{folder}' => $folderPath));
            return false;
        }

        return true;
    }

    /**
     * Implements transliteration of russian.
     * @param string $string
     * @return string
     */
    private function transliterate($string) {
        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
            'Ь' => '', 'Ы' => 'Y', 'Ъ' => '',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        );

        return strtr($string, $converter);
    }

    private function getResized($width, $height, $newWidth, $newHeight) {
        $arrSize = array();
//            $newHeight = self::THUMBNAIL_HEIGHT;
//            $newWidth = self::THUMBNAIL_WIDTH;
        // calculate image ratio in case if image width is bigger than height
        if ($width > $height) {
            $ratio = $newWidth / $width;
            $thmbHeight = (int) ($height * $ratio);

            $arrSize['width'] = $newWidth;
            $arrSize['height'] = $thmbHeight;
        }
        // calculate image ratio in case if image height is bigger than width
        elseif ($height > $width) {
            $ratio = $newHeight / $height;
            $thmbWidth = (int) ($width * $ratio);

            $arrSize['width'] = $thmbWidth;
            $arrSize['height'] = $newHeight;
        } else {
            $arrSize['width'] = $newWidth;
            $arrSize['height'] = $newHeight;
        }

        return $arrSize;
    }

    private function getThumbnail($imgPath, $newWidth, $newHeight, $thumbName, $itemId) {
        //здесь $imageUrl это $_FILES[img_tmp].
        $imageUrl = $imgPath;
//        $itemId = Yii::app()->request->getParam('itemId');
        $imgFolderPath = Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . $this->_imageFolder . DIRECTORY_SEPARATOR . $this->_itemsImageFolder . DIRECTORY_SEPARATOR . $itemId;

        $arrImageInfo = getimagesize($imageUrl);
        $width = $arrImageInfo[0];
        $height = $arrImageInfo[1];
        $type = $arrImageInfo['mime'];
        $srcImage = null;

        if ($type == 'image/jpeg')
            $srcImage = imagecreatefromjpeg($imageUrl);
        if ($type == 'image/gif')
            $srcImage = imagecreatefromgif($imageUrl);
        if ($type == 'image/png')
            $srcImage = imagecreatefrompng($imageUrl);

        // if image is bigger than thumbnail
        if ($width > $newWidth || $height > $newHeight) {
            // get adapted sizes
            $arrSize = $this->getResized($width, $height, $newWidth, $newHeight);
            $width_new = $arrSize['width'];
            $height_new = $arrSize['height'];

            $newImage = imagecreatetruecolor($width_new, $height_new);
            imagecopyresampled($newImage, $srcImage, 0, 0, 0, 0, $width_new, $height_new, $width, $height);

            // create movie thumbnail img path
            $thmbPath = $imgFolderPath . DIRECTORY_SEPARATOR . $thumbName;

            // save image
            @imagejpeg($newImage, $thmbPath);
        } else {
            // create movie thumbnail img path
            $thmbPath = $imgFolderPath . DIRECTORY_SEPARATOR . $thumbName;
            // save movie thumbnail image
            file_put_contents($thmbPath, @file_get_contents($imageUrl));
        }
    }

}
