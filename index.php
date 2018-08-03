<?php 
//error_reporting(E_ALL);
$pdo = new PDO("mysql:host=localhost;dbname=JLoseva;charset=utf8", "JLoseva", "neto1801");
if (!$pdo)
    {
        die('Невозможно подключиться');
    }

if (isset($_POST['add'])){
    if (empty($_POST['description'])) {
        echo 'Вы не добавили задание';
    }else {
        $description = strip_tags($_POST['description']);
        $date = date('Y-m-d H:i:s');
        $sql = "INSERT INTO tasks (description, is_done, date_added) VALUES ('$description', 0, '$date')";
        $res = $pdo->prepare($sql);
        $res->execute([$_POST['description']]); 
        header("Location: index.php");
    }
}


if (!empty($_GET['action'])) {
    if ($_GET['action'] == 'edit') {
        $sql = "SELECT * FROM tasks WHERE id = ?";
        $res = $pdo->prepare($sql);
        $res->execute([$_GET['id']]);
    }
    
    if ($_GET['action'] == 'done') {
        $sql = "UPDATE tasks SET is_done = 1 WHERE id = ?";
        $res = $pdo->prepare($sql);
        $res->execute([$_GET['id']]);
        header("Location: index.php");        
    }
    
    if ($_GET['action'] == 'delete') {
        $sql = "DELETE FROM tasks WHERE id = ?";
        $res = $pdo->prepare($sql);
        $res->execute([$_GET['id']]);
        header("Location: index.php");
    }
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Запросы SELECT, INSERT, UPDATE и DELETE</title>
    <link rel="stylesheet" href="css/style.css">  
</head>
<body>
<h1>Список дел на сегодня</h1>
<div class="action">
    <form method="POST">
        <input type="text" name="description" placeholder="Веедите задание">
         <input type="submit" name="add" value="Добавить">
    </form>
</div>
<table>
    <thead>
        <tr>
            <th>Описание задачи</th>
            <th>Дата добавления</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $sql = $pdo->query("SELECT * FROM tasks");
    foreach ($sql as $result) : ?>
        <tr>
            <td><?=$result['description']?></td>
            <td><?=$result['date_added']?></td>
            <td><?php  
                if ($result['is_done'] == 1) {
                    echo "<span class=\"done\">Выполнено</span>";
                } else  {
                    echo "<span class=\"undone\">В процессе</span>";
                }
            ?></td>
            <td>
                <a href="index.php?id=<?=$result['id']?>&action=edit">Изменить</a>
                <a href="index.php?id=<?=$result['id']?>&action=done">Выполнить</a>
                <a href="index.php?id=<?=$result['id']?>&action=delete">Удалить</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>