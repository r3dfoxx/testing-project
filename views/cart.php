<?php require_once(ROOT_PATH . "/views/header.php"); ?>
    <main role="main">

        <section class="jumbotron text-center">
            <div class="container">
                <h1>Учебный магазин </h1>
                <p class="lead text-muted">Ваш Заказ </p>
                <p>
                    <a href="/index.php" class="btn btn-secondary my-2">Продолжить покупки</a>
                </p>
            </div>
        </section>

        <div class="album py-5 bg-light">
            <div class="container">
                <form method="POST" action="/cart.php">
                <div class="row">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4">
                            <div class="card mb-4 shadow-sm">
                                <?php if (!empty($product['image'])): ?>
                                    <img class="product-img" src="<?php echo $product['image'];?>">
                                <?php else:?>
                                    <img class="product-img" src="<?php echo PRODUCT_DEFAULT_IMAGE;?>">
                                <?php endif;?>
                                <div class="card-body">
                                    <p class="card-text"><?php echo $product['name'];?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="form-group chechbox-block">
                                            <label class="" for="exampleCheck1">Quantity</label>
                                            <input class="form-control" type="number"
                                                   value="<?php echo $product['selected_quantity']; ?>" id="quantity"
                                                   name="quantity_<?php echo $product['id'];?>" min="0" max="<?php echo $product['quantity']; ?>">
                                        </div>
                                        <small class="text-muted">UAH <?php echo money_format('%i', $product['price']);?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                    <div class="row">
                        <div class="col-md-2 center-block">
                        </div>
                        <div class="col-md-8 center-block">
                            <input type="submit" name="update" class="btn btn-primary order-button" value="UPDATE"/>
                            <input type="submit" name="reset" class="btn btn-primary order-button" value="RESET"/>
                        </div>
                        <div class="col-md-2 center-block">
                        </div>
                    </div>
                </form>
            </div>
            <hr>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                    </div>
                    <div class="col-md-2">
                        <h2>Всего к оплате:</h2>
                    </div>
                    <div class="col-md-4">
                        <h2>$<?php echo money_format('%i', $totalPrice);?></h2>
                    </div>
                </div>
            </div>
        </div>

    </main>
<?php require_once(ROOT_PATH . "/views/footer.php"); ?>