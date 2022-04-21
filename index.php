<?php 
//Нужно скачать в папку с сайтом эти библиотеки через composer
//firebase/php-jwt
//guzzlehttp:/ / guzzle
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use GuzzleHttp\Client;

define('ZOOM_API_KEY', 'NhHMmg7xTE6Mr3wU-foKCg');
define('ZOOM_SECRET_KEY', '8TAwG1vr4EZV5FdsPYyZuyKJve5Z8fkENnNu');


//Генерация JWT с помощью firebase/php-jwt
	function getZoomAccessToken() {
		$key = ZOOM_SECRET_KEY;
		$payload = array(
			"iss" => ZOOM_API_KEY,
			'exp' => time() + 3600,
		);
		return JWT::encode($payload, $key, 'HS256');//'HS256' я так и не понял что это но без третьего аргумента ошибка
}
?>
<?$createMeetingArray = array();?>
<form method="POST">
    <input type="text" name="topic" size="40" maxlength="35" required placeholder="Название Встречи">
    <input type="number" name="duration" size="40" maxlength="35" required placeholder="Длительность">
    <input type="number" name="password" size="40" maxlength="35" required placeholder="Пароль">
<?php
//Создаём встречу
function createZoomMeeting() {
    $client = new Client([
       'base_uri' => 'https://us04web.zoom.us/meeting?_x_zm_rtaid=Oj_3Qxd4Sg2HMtHM6xAMZA.1650022242521.e92591ecf4fc5582b3dd8745788e0f3d&_x_zm_rhtaid=935#/upcoming',
    ]);
 
    $response = $client->request('POST', '/v2/users/me/meetings', [
        "headers" => [
            "Authorization" => "Bearer " . getZoomAccessToken()
        ],
        'json' => [
            "todo" => $_POST['topic'],
            "type" => 2,
            "start_time" => $_POST['start_time'],
            "duration" => $_POST['duration'], // Время
            "password" => $_POST['password']
        ],
    ]);
 
    $data = json_decode($response->getBody());

    echo "Join URL: ". $data->join_url;
    echo "<br>";
    echo "Meeting Password: ". $data->password;
}
?>
<div align=center>
    <?php
        if( isset( $_POST['createZoomMeeting()'] ) )
        {
            createZoomMeeting();
        }
    ?>
<form method="POST">
    <input type="submit" name="createZoomMeeting()" value="Создать встречу" />
</form>
</div>

<?php
////
//СПИСОК ВСТРЕЧЬ
////
//createZoomMeeting();

function createМeetingList() {
    $client = new GuzzleHttp\Client(['base_uri' => 'https://us04web.zoom.us/meeting?_x_zm_rtaid=Oj_3Qxd4Sg2HMtHM6xAMZA.1650022242521.e92591ecf4fc5582b3dd8745788e0f3d&_x_zm_rhtaid=935#/upcoming']); 
    
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
        echo "<h3>Topic: $topic</h3>";
        echo "Join URL: $join_url";
    }
}


}
////
//СПИСОК ВСТРЕЧЬ
////
//createМeetingList();

////
//УДАЛЕНИЕ ВСТРЕЧЬ
////
function deleteZoomMeeting($meeting_id) {
    $client = new Client([
        // Base URI is used with relative requests
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
        
        deleteZoomMeeting($data->join_url);
 
//deleteZoomMeeting('СЮДА АЙДИ НАДА ПЕРЕДАТЬ');

?>
<?php
    if( isset( $_POST['createМeetingList()'] ) )
    {
        createМeetingList();
    }
?>
<form method="POST">
    <input type="submit" name="createМeetingList()" value="Список встречь" />
</form>

<?php
    if( isset( $_POST['deleteZoomMeeting($meeting_id)'] ) )
    {
        deleteZoomMeeting('71222496328');
    }
?>
<form method="POST">
    <input type="submit" name="deleteZoomMeeting($meeting_id)" value="Удалить встречу" />
</form>