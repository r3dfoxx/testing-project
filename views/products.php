<?php require_once(ROOT_PATH . "/views/header.php"); 
    

?>
    <main role="main">

        <section class="jumbotron text-center">
            <div class="container">
                <h1>Test Shop</h1>
                <p class="lead text-muted">Something short</p>
                <p>
                    <a href="/cart.php" class="btn btn-secondary my-2">Go to Cart</a>
                </p>
            </div>
        </section>

        <div class="album py-5 bg-light">
            <div class="container">
                <form method="POST" action="/index.php">
                <div class="row">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4">
                            <div class="card mb-4 shadow-sm">
                                    <svg class="bd-placeholder-img card-img-top" width="100%" height="225"
                                     xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice"
                                     focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>
                                        Placeholder</title>
                                        <rect width="100%" height="100%" fill="#55595c"/>
                                            <text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text>
                                    </svg>
                                <div class="card-body">
                                    <p class="card-text"><?php echo $product['name'];?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group chechbox-block">
                                            <label class="form-check-label" for="exampleCheck1">Check me</label>
                                            <input
                                                class="form-check-input" <?php if (!empty($_SESSION['products']) && in_array(
                                                    $product['id'],
                                                    $_SESSION['products']
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
<?php require_once(ROOT_PATH . "/views/footer.php"); ?><?php require_once(ROOT_PATH . "/views/header.php"); 
    

?>
    <main role="main">

        <section class="jumbotron text-center">
            <div class="container">
                <h1>Test Shop</h1>
                <p class="lead text-muted">Something short</p>
                <p>
                    <a href="/cart.php" class="btn btn-secondary my-2">Go to Cart</a>
                </p>
            </div>
        </section>

        <div class="album py-5 bg-light">
            <div class="container">
                <form method="POST" action="/index.php">
                <div class="row">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4">
                            <div class="card mb-4 shadow-sm">
                                <svg class="bd-placeholder-img card-img-top" width="100%" height="225"
                                     xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice"
                                     focusable="false" role="img" aria-label="Placeholder: Thumbnail"><title>
                                        Placeholder</title>
                                    <rect width="100%" height="100%" fill="#55595c"/>
                                    <text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text>
                                </svg>
                                <div class="card-body">
                                    <p class="card-text"><?php echo $product['name'];?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group chechbox-block">
                                            <label class="form-check-label" for="exampleCheck1">Check me</label>
                                            <input
                                                class="form-check-input" <?php if (!empty($_SESSION['products']) && in_array(
                                                    $product['id'],
                                                    $_SESSION['products']
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