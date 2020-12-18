<?php
if (! isset($_SESSION)) {
    session_start();
}
include (ZH_PATH . DS . 'manager' . DS . 'islogin' . ZH);
$CssRand='braveguo2';
?>
<script src="<?php echo DS . 'manager' . DS?>js1/jquery.min.js?id=<?php echo $CssRand;?>"></script> 
<script src="<?php echo DS . 'manager' . DS?>js1/jquery.ui.custom.js?id=<?php echo $CssRand;?>"></script> 
<script src="<?php echo DS . 'manager' . DS?>js1/bootstrap.min.js?id=<?php echo $CssRand;?>"></script> 
<script src="<?php echo DS . 'manager' . DS?>js1/bootstrap-datepicker.js?id=<?php echo $CssRand;?>"></script> 
<script src="<?php echo DS . 'manager' . DS?>js1/locales/bootstrap-datepicker.zh-CN.js?id=<?php echo $CssRand;?>" charset="UTF-8"></script>
<script src="<?php echo DS . 'manager' . DS?>js1/masked.js?id=<?php echo $CssRand;?>"></script> 
<script src="<?php echo DS . 'manager' . DS?>js1/jquery.uniform.js?id=<?php echo $CssRand;?>"></script> 
<script src="<?php echo DS . 'manager' . DS?>js1/matrix.js?id=<?php echo $CssRand;?>"></script>
<script src="<?php echo DS . 'manager' . DS?>js1/jquery.sparkline.js?id=<?php echo $CssRand;?>"></script> 
<script src="<?php echo DS . 'manager' . DS?>js1/jquery.spinner.js?id=<?php echo $CssRand;?>"></script>
<script src="<?php echo DS . 'manager' . DS?>js1/jquery.plugin.js?id=<?php echo $CssRand;?>"></script>
<script src="<?php echo DS . 'manager' . DS?>js1/jquery.timeentry.js?id=<?php echo $CssRand;?>"></script>
<script src="<?php echo DS . 'manager' . DS?>js1/matrix.form_common1.js?id=<?php echo $CssRand;?>"></script> 
<script src="<?php echo DS . 'manager' . DS?>js1/jquery.peity.min.js?id=<?php echo $CssRand;?>"></script>
<script src="<?php echo DS . 'manager' . DS?>js1/jquery.validate.js?id=<?php echo $CssRand;?>"></script>
<script src="<?php echo DS . 'manager' . DS?>js1/multiselect.min.js?id=<?php echo $CssRand;?>"></script>
<script src="<?php echo DS . 'common' . DS . 'js' . DS . 'layer' . DS?>layer.js"></script>
