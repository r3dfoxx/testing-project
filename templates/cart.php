    <main role="main">

        <section class="jumbotron text-center">
            <div class="container">
                <h1>Test Shop</h1>
                <p class="lead text-muted">Something short and leading about the collection below—its contents, the
                    creator, etc. Make it short and sweet, but not too short so folks don’t simply skip over it
                    entirely.</p>
                <p>
                    <a href="/" class="btn btn-secondary my-2">Go to Products</a>
                </p>
            </div>
        </section>

        <div class="album py-5 bg-light">
            <div class="container">
                <form method="POST" action="/cart/update">
                <div class="row">
                    <?php foreach ($cart as $product): ?>
                        <div class="col-md-4">
                            <div class="card mb-4 shadow-sm">
                                <?php if (!empty($product->image)): ?>
                                    <img class="product-img" src="<?php echo $product->image;?>">
                                <?php else:?>
                                    <img class="product-img" src="<?php echo getenv('PRODUCT_DEFAULT_IMAGE');?>">
                                <?php endif;?>
                                <div class="card-body">
                                    <p class="card-text"><?php echo $product->name;?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="form-group chechbox-block">
                                            <label class="" for="exampleCheck1">Quantity</label>
                                            <input class="form-control" type="number"
                                                   value="<?php echo $product->selectedQuantity; ?>" id="quantity"
                                                   name="quantity_<?php echo $product->getId();?>" min="0" max="<?php echo $product->quantity; ?>">
                                        </div>
                                        <small class="text-muted">UAH <?php echo money_format('%i', $product->price);?></small>
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
                            <a name="reset" class="btn btn-primary order-button" href="/cart/reset">RESET</a>
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
                        <h2>Total Price:</h2>
                    </div>
                    <div class="col-md-4">
                        <h2>$<?php echo money_format('%i', $totalPrice);?></h2>
                    </div>
                </div>
            </div>
        </div>

    </main>