<?php require_once(ROOT_PATH . "/views/header.php"); ?>
    <main role="main">

        <section class="jumbotron text-center">
            <div class="container">
                <h1>Test Shop</h1>
                <p class="lead text-muted">Введите Ваш Логин и пароль</p>
                <p>
                    <!--<a href="/cart.php" class="btn btn-secondary my-2">Go to Cart</a>-->
                </p>
            </div>
        </section>

        <div class="album py-5 bg-light">
            <div class="container">
                <form class="form-group input-group" method="POST" action="/login.php">
                    <fieldset>
                        <div class="row">
                            <div class="form-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
                                </div>
                                <div class="col-sm-8">
                                    <input class="form-control <?php if (!empty($error['email'])) echo 'is-invalid';?>" type="email" placeholder="Enter Your Email" required
                                           name="email" id="email">
                                </div>
                                <div class="col-sm-3">
                                    <small id="" class="text-danger">
                                        <?php
                                            $errorField = $error['email'] ?? '';
                                            include ROOT_PATH . "/views/error_validation_message.php";
                                        ?>
                                    </small>
                                </div>
                            </div>
                            <div class="form-group input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                </div>
                                <div class="col-sm-8">
                                    <input class="form-control <?php if (!empty($error['password'])) echo 'is-invalid';?>" type="password" placeholder="Create password" required
                                           name="password" id="password">
                                </div>
                                <div class="col-sm-3">
                                    <small id="" class="text-danger">
                                        <?php
                                            $errorField = $error['password'] ?? '';
                                            include ROOT_PATH . "/views/error_validation_message.php";
                                        ?>
                                    </small>
                                </div>
                            </div>
                            <div class="form-group input-group">
                                <div class="col-sm-8">
                                    <input class="btn btn-primary btn-block" type="submit" name="login"
                                           value="Login">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>

    </main>
<?php require_once(ROOT_PATH . "/views/footer.php"); ?>