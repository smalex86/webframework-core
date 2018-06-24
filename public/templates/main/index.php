<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
        <meta name="rights" content="">
        <meta name="author" content="">
        
        <?php /*$title = $application->getPageTitle(); if ($title) { 
            echo sprintf('<meta name="title" content="%s">', $title); 
            echo sprintf('<meta property="og:title" content="%s" />', $title); }*/
        ?>
        
        <?php /*$description = $application->getPageDescription(); if ($description) { 
            echo sprintf('<meta name="description" content="%s">', $description); 
            echo sprintf('<meta property="og:description" content="%s" />', $description); }*/
        ?>
        
        <?php /*$image = $application->getPageImage(); if ($image) { 
            echo sprintf('<link rel="image_src" href="%s" />', $image); 
            echo sprintf('<meta property="og:image" content="%s" />', $image); }*/
        ?>
        
        <!--<link rel="shortcut icon" href="templates/favicon.ico">-->
          
        <title><?php echo $application->getPageTitle(); ?></title>

    <!-- Bootstrap core CSS -->
    <link href="templates/main/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="templates/main/js/bootstrap.js"></script>
  </head>

<body>
    
  <div class="navbar navbar-default" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Навигация</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php" title="<?php echo $application->getSiteName() ?>"><?php echo $application->getSiteName() ?></a>
      </div>
              <div class='navbar-collapse collapse'>
                <?php 
                  echo $application->getMenu('main');
                  echo $application->getComponent('user', 'info');
                ?>
              </div><!--/.navbar-collapse -->
    </div>
  </div>

  <div class="container" role="main">
      <div class="row">
        <div class="col-xs-12">
          <?php echo $application->getSession()->checkPostMsg(); // вывод пост-сообщений ?>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <!--<h1><?php echo $application->getPageTitle(); ?></h1>-->
          <?php echo $application->getPageContent(); ?>
        </div>  
      </div>
  </div>
  <div class="footer"><!-- Подвал -->
  </div> <!-- Подвал -->

  </div> <!-- /container -->      
    <?php if ($_SERVER['SERVER_NAME'] != 'localhost') : ?>
        <!-- Код счетчиков -->
    <?php endif; ?>
  
  <?php //echo $botServer->getPageScriptList() ?>
        
</body>
</html>
