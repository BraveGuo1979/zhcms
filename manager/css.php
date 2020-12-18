<?php
if (! isset($_SESSION)) {
    session_start();
}
include (ZH_PATH . DS . 'manager' . DS . 'islogin' . ZH);
$CssRand='braveguo5';
?>
<link rel="stylesheet" href="<?php echo DS . 'manager' . DS?>css1/bootstrap.min.css?id=<?php echo $CssRand;?>" />
<link rel="stylesheet" href="<?php echo DS . 'manager' . DS?>css1/bootstrap-responsive.min.css?id=<?php echo $CssRand;?>" />
<link rel="stylesheet" href="<?php echo DS . 'manager' . DS?>css1/fullcalendar.css?id=<?php echo $CssRand;?>" />
<link rel="stylesheet" href="<?php echo DS . 'manager' . DS?>css1/matrix-style.css?id=<?php echo $CssRand;?>" />
<link rel="stylesheet" href="<?php echo DS . 'manager' . DS?>css1/matrix-media.css?id=<?php echo $CssRand;?>" />
<link rel="stylesheet" href="<?php echo DS . 'manager' . DS?>css1/colorpicker.css?id=<?php echo $CssRand;?>" />
<link rel="stylesheet" href="<?php echo DS . 'manager' . DS?>css1/datepicker.css?id=<?php echo $CssRand;?>" />
<link rel="stylesheet" href="<?php echo DS . 'manager' . DS?>css1/uniform.css?id=<?php echo $CssRand;?>" />
<link rel="stylesheet" href="<?php echo DS . 'manager' . DS?>css1/select2.css?id=<?php echo $CssRand;?>" />
<link rel="stylesheet" href="<?php echo DS . 'manager' . DS?>font-awesome/css/font-awesome.css?id=<?php echo $CssRand;?>" />
<link rel="stylesheet" href="<?php echo DS . 'manager' . DS?>css1/jquery.gritter.css?id=<?php echo $CssRand;?>" />
<LINK rel="stylesheet" href="<?php echo DS . 'manager' . DS?>css1/pagecss.css?id=<?php echo $CssRand;?>" type="text/css">
<style>
#content-header {
    z-index: 0;
}
</style>