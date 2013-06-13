<!DOCTYPE html>
<html lang="en">
<head>
  <?php echo $this->Html->charset(); ?>
  <title><?php echo $title_for_layout; ?></title>
  <!--  meta info -->
  <?php
    echo $this->Html->meta(array("name"=>"viewport",
      "content"=>"width=device-width,  initial-scale=1.0"));
    echo $this->Html->meta(array("name"=>"description",
      "content"=>"this is the description"));
    echo $this->Html->meta(array("name"=>"author",
      "content"=>"TheHappyDeveloper.com - @happyDeveloper"))
  ?>

  <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
  <!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <!--JavaScript-->
  <script type="text/javascript">
	host = "<?php echo Configure::read("HOST");?>";
  </script>
  <?php
    echo $this->Html->script('jquery-1.10.1.min');
    echo $this->Html->script('url-shortener');
  ?>

  <!-- styles -->
  <?php
    echo $this->Html->css('bootstrap');
    echo $this->Html->css('bootstrap-responsive');
    echo $this->Html->css('docs');
    echo $this->Html->css('prettify');
  ?>
  <!-- icons -->
  <?php
    echo  $this->Html->meta('icon',$this->webroot.'img/favicon.ico');
    echo $this->Html->meta(array('rel' => 'apple-touch-icon',
      'href'=>$this->webroot.'img/apple-touch-icon.png'));
    echo $this->Html->meta(array('rel' => 'apple-touch-icon',
      'href'=>$this->webroot.'img/apple-touch-icon.png',  'sizes'=>'72x72'));
    echo $this->Html->meta(array('rel' => 'apple-touch-icon',
      'href'=>$this->webroot.'img/apple-touch-icon.png',  'sizes'=>'114x114'));
  ?>
  <!-- page specific scripts -->
    <?php echo $scripts_for_layout; ?>
</head>

<body data-spy="scroll" data-target=".subnav" data-offset="50">
  <div id="container">
  <!-- Navbar ============================================= -->
  <div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container">
        <a class="brand" href="./index.html">Welcome To The Url Shortening Service</a>
      </div>
    </div>
  </div>
   <div class="hero-unit">
    <?php echo $this->Session->flash(); ?>
    <?php echo $content_for_layout; ?>
   <div>
  </div>
  <?php echo $this->element('sql_dump'); ?>
</body>
</html>
