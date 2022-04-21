<?php 
//Нужно скачать в папку с сайтом эти библиотеки через composer
//firebase/php-jwt
//guzzlehttp:/ / guzzle
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use GuzzleHttp\Client;

$Name = htmlspecialchars($_POST['todo']);
$Name1 = htmlspecialchars($_POST['topic']);
$Name = trim($Name);
$Name1 = trim($Name1);
$jsonArray = [];
$jsonArray1 = [];

define('ZOOM_API_KEY', 'NhHMmg7xTE6Mr3wU-foKCg');
define('ZOOM_SECRET_KEY', '8TAwG1vr4EZV5FdsPYyZuyKJve5Z8fkENnNu');

//C разных ключей 
//Генерация JWT с помощью firebase/php-jwt
    function getZoomAccessToken() {
        $keyAPI = ZOOM_SECRET_KEY;
        $payload = array(
            "iss" => ZOOM_API_KEY,
            'exp' => time() + 3600,
        );
        return JWT::encode($payload, $keyAPI, 'HS256');//'HS256' я так и не понял что это но без третьего аргумента ошибка
}
function createZoomMeeting() {
    $client = new Client([
       'base_uri' => 'https://us04web.zoom.us/meeting?_x_zm_rtaid=Oj_3Qxd4Sg2HMtHM6xAMZA.1650022242521.e92591ecf4fc5582b3dd8745788e0f3d&_x_zm_rhtaid=935#/upcoming',
    ]);
 
    $response = $client->request('POST', '/v2/users/me/meetings', [
        "headers" => [
            "Authorization" => "Bearer " . getZoomAccessToken()
        ],
        'json' => [
            "topic" => $_POST['topic'],
            "type" => 2,
            "start_time" => $_POST['start_time'],//дата начала
            "duration" => $_POST['duration'] // Время
        ],
    ]);

}
//TODO: безопасность завернусть пост в спец
function deleteZoomMeeting($meeting_id) {
    $client = new Client([
        'base_uri' => 'https://us04web.zoom.us/meeting?_x_zm_rtaid=Oj_3Qxd4Sg2HMtHM6xAMZA.1650022242521.e92591ecf4fc5582b3dd8745788e0f3d&_x_zm_rhtaid=935#/upcoming',
    ]);
 
    $response = $client->request("DELETE", "/v2/meetings/$meeting_id", [
        "headers" => [
        "Authorization" => "Bearer ". getZoomAccessToken()
        ]
        ]);
        
        if (204 == $response->getStatusCode()) {
        echo "Meeting deleted.";
        }
}

//Если файл существует - получаем его содержимое
if (file_exists('todo.json')){
    $json = file_get_contents('todo.json', );//ПОЛУЧЕНИЕ В СПЕЦ ПЕРЕМЕННУЮ
    $json1 = file_get_contents('topic.json', );
    $jsonArray = json_decode($json, true);//Декодит массив
    $jsonArray1 = json_decode($json1, true);
}
// Делаем запись в файл
if ($Name){
    createZoomMeeting();
    $jsonArray[] = $Name;
    $jsonArray1[] = $Nam1;
    file_put_contents('todo.json', json_encode($jsonArray, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK));
    file_put_contents('topic.json', json_encode($jsonArray1, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK));
    header('Location: '. $_SERVER['HTTP_REFERER']);//Перезагружаем страницу

}

// Удаление записи
$key = @$_POST['todo_name'];
$key1 = @$_POST['topic_name'];
if (isset($_POST['del'])){
    unset($jsonArray[$key]);
    unset($jsonArray1[$key1]);
    deleteZoomMeeting($meeting_id);
    file_put_contents('todo.json', json_encode($jsonArray, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK));
    file_put_contents('topic.json', json_encode($jsonArray, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK));
    header('Location: '. $_SERVER['HTTP_REFERER']);

}

// Редактирование
//if (isset($_POST['save'])){
    //$jsonArray[$key] = @$_POST['title'];
    //file_put_contents('todo.json', json_encode($jsonArray, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK));
    //header('Location: '. $_SERVER['HTTP_REFERER']);
//}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <title>Встречи в Зуме</title>
    <style>
    </style>
</head>
<body>
<section>
    <div class="container mt-3">
        <div class="row justify-content-center">

            <div class="col-12">
                <button class="btn btn-success mb-1" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-plus-circle"></i></button>
                <table class="table table-bordered">
                    <thead class="table-dark">
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Дата</th>
                        <th scope="col"></th>
                        <th scope="col">Удалить</th>
                    </tr>
                    </thead>
                    <tbody>
                <?php
                foreach ($jsonArray as $key => $topic):
                    ?>
                    <tr>
                        <th scope="row"><?php echo $key + 1 ;?></th>
                        <td><?php echo $topic; ?></td>
                            <td><?
                                $client = new Client(['base_uri' => 'https://us04web.zoom.us/meeting?_x_zm_rtaid=hF3etwNRTLi56AVvtl6UFg.1650369251924.a5b3d6a8b21f6d1e9c6f71fd82cd3635&_x_zm_rhtaid=346#/upcoming']);
 
                                $response = $client->request('GET', '/v2/users/me/meetings', [
                                    "headers" => [
                                        "Authorization" => "Bearer ". getZoomAccessToken()
                                    ]
                                    ]);
  
                                $data = json_decode($response->getBody());
  
                                if (!empty($data)) {
                                    foreach ($data->meetings as $d) {
                                        $topic = $d->topic;
                                        $join_url = $d->join_url;
                                    }
                                    }
                                    echo $join_url;
                                    ?></td>
                        <td>
                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete<?php echo $key;?>"><i class="fas fa-trash-alt"></i></button>
                            <!--Modal delete-->
                            <div class="modal fade" id="delete<?php echo $key;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Вы хотите удалить запись №<?php echo $key + 1 ;?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body ml-auto">
                                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  method="post">
                                                <div class="input-group">
                                                    <input type="hidden" name="todo_name" value="<?php echo $key; ?>">
                                                </div>
                                                <button class="btn btn-danger del" name="del">Удалить</button>
                                            
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!--Modal delete-->
                            <!--Modal Edit-->
                            <div class="modal fade" id="edit<?php echo $key;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Изменить запись</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                        <form action="" method="post" class="mt-2">
                                            <div class="input-group">
                                                <form method="POST">
                                                <input type="date" class="form-control" name="title" value="<?php echo $todo; ?>" required placeholder="Дата" min="2022-01-01" max="2030-12-31">
                                                <input type="text" class="form-control" name="topic" required placeholder="Тема Встречи">
                                                <input type="number" class="form-control" name="duration"  required placeholder="Длительность">
                                                <input type="number" class="form-control" name="password"  required placeholder="Пароль">       
                                            </div>
                                            <input type="hidden" name="todo_name" value="<?php echo $key;?>">
                                            <div class="modal-footer">
                                                <button type="submit" name="save" class="btn btn-sm btn-success p-1 pt-0" data-target="#edit<?php echo $key;?>">Обновить</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!--Modal Edit-->
                        </td>
                    </tr>
                <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!--Modal-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавить запись</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  method="post">
                    <div class="input-group">
                        <form method="POST">
                        <input type="date" class="form-control" name="todo" required placeholder="Дата" min="2022-01-01" max="2030-12-31">
                        <input type="text" class="form-control" name="topic" required placeholder="Тема Встречи">
                        <input type="number" class="form-control" name="duration"  required placeholder="Длительность">
                        <input type="number" class="form-control" name="password"  required placeholder="Пароль">
                    </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary send" data-send="1">Создать</button>
            </div> </form>
        </div>
    </div>
</div>
<!--Modal-->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script>
</script>
</body>
</html>