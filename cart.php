<?php
if (!empty($_SESSION['products']) && in_array($product['id'], $_SESSION['products'])) {
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Корзина покупателя</title>
</head>
<body>
    <div class="row">
    <div class="col-lg-3 col-sm-3 col-xs-12" style="height: 100px; line-height: 100px;">
   <? php echo $product['id']; ?>
    </div>
    <div  class="col-lg-3 col-sm-3 col-xs-12 mob-fix" style="height: 100px; line-height: 100px;">
    </div>
    
    
    <div class="col-lg-2 col-sm-2 col-xs-12 mob-fix" style="height: 100px; line-height: 100px;">
    
    </div>

</body>
</html>


