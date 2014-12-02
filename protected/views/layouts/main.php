<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/bootstrap/css/bootstrap.min.css" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
        
</head>

<body>
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">          
          <a class="navbar-brand" href="/">Web store project</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="//market/item/list">List</a></li>
          </ul>
            
          <form class="navbar-form navbar-right" role="form">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>
        </div>
      </div>
    </div>
    
    <div class="container" style="padding: 50px 0">
        <?php echo $content; ?>
    </div>
    
    
    <div class="navbar navbar-fixed-bottom" role="navigation">
        <div class="container">
            
        </div>
    </div>

    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/lib/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>