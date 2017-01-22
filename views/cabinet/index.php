<?php include ROOT.'/views/layouts/header.php'; ?>

<section>
    <div style="height: 538px;">
    <div class="container">
        <div class="row">
            <h1>Кабинет пользователя</h1>
            <h2>Добро пожаловать <?= $user['name'] ?></h2>
            <ul>
                <li><a href="/cabinet/edit">Редактировать пользователя</a></li>
                <li><a href="/user/history">Список покупок</a></li>
            </ul>
        </div>
    </div>
    </div>
</section>

<?php include ROOT.'/views/layouts/footer.php'; ?>