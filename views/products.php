<?php require_once(ROOT_PATH . "/views/header.php"); ?>
    <main role="main">

        <section class="jumbotron text-center">
            <div class="container">
                <h1>Test Shop</h1>
                <p class="lead text-muted">Something short and leading about the collection below—its contents, the
                    creator, etc. Make it short and sweet, but not too short so folks don’t simply skip over it
                    entirely.</p>
                <p>
                    <a href="/cart.php" class="btn btn-secondary my-2">Go to Cart</a>
                </p>
            </div>
        </section>

        <div class="album py-5 bg-light">
            <div class="container">
                <div class="row left-side-bar">
                    <div class="col-md-2">
                        <form method="POST" action="/index.php">
                            <h5>Categories:</h5>
                            <?php foreach ($categories as $category): ?>
                                <input class="form-check-input" <?php if (!empty($_POST['categories']) && in_array(
                                        $category['id'],
                                        $_POST['categories']
                                    )) {
                                    echo "checked";
                                } ?> type="checkbox" name="categories[]"
                                       value="<?php echo $category['id'] ?>"><?php echo $category['category_name']; ?>
                            <?php endforeach; ?>
                            </ul>
                            <input type="submit" class="btn btn-secondary btn-sm" value="Apply"/>
                        </form>
                    </div>
                </div>
                <form method="POST" action="/index.php">
                <div class="row">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4">
                            <div class="card mb-4 shadow-sm">
                                <?php if (!empty($product['image'])): ?>
                                    <img class="img-fluid product-img" src="<?php echo $product['image'];?>">
                                <?php else:?>
                                    <img class="img-fluid product-img" src="<?php echo PRODUCT_DEFAULT_IMAGE;?>">
                                <?php endif;?>
                                <div class="card-body">
                                    <p class="card-text"><?php echo $product['name'];?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group chechbox-block">
                                            <label class="form-check-label" for="exampleCheck1">Check me</label>
                                            <input
                                                class="form-check-input" <?php if (!empty($_SESSION['products']) && in_array(
                                                    $product['id'],
                                                    array_column($_SESSION['products'], "id")
                                                )) {
                                                echo "checked";
                                            } ?> type="checkbox" name="products[]"
                                                value="<?php echo $product['id']; ?>">
                                        </div>
                                        <small class="text-muted">UAH <?php echo money_format('%i', $product['price']);?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                    <div class="row">
                        <div class="col-md-4 center-block">
                        </div>
                        <div class="col-md-4 center-block">
                            <input type="submit" class="btn btn-primary order-button" value="Order"/>
                        </div>
                        <div class="col-md-4 center-block">
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </main>
<?php require_once(ROOT_PATH . "/views/footer.php"); ?>