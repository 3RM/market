<?php include ROOT . '/views/layouts/header.php'; ?>

<section>
    <div class="container">
        <div class="row">

            <div class="col-sm-4 col-sm-offset-4 padding-right">

                <?php if ($result): ?>
                    <p>Данные отредактированы</p>
                <?php else: ?>
                    <?php if (isset($errors) && is_array($errors)): ?>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li> - <?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <div class="signup-form"><!--sign edit form-->
                        <h2>Редактирование данных</h2>
                        <form action="#" method="post">
                            <input type="text" name="name" placeholder="Имя" value="<?php echo $name; ?>"/>
                            <input type="password" name="password" placeholder="Текущий пароль" value=""/>
                            <input type="password" name="new_password" placeholder="Новый пароль" value=""/>
                            <input type="submit" name="edit" class="btn btn-default" value="Edit" />
                        </form>
                    </div><!--/sign edit form-->

                <?php endif; ?>
                <br/>
                <br/>
            </div>
        </div>
    </div>


</section>

<?php include ROOT . '/views/layouts/footer.php'; ?>