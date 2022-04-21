<?php
require_once 'config.php';
$url = "https://zoom.us/oauth/authorize?response_type=code&client_id=".CLIENT_ID."&redirect_uri=".REDIRECT_URI;
?>
<center>
    <a href="<?php echo $url; ?>">Login with Zoom</a>                      
    <input type="date"  name="start_time" required placeholder="Дата" min="2022-01-01" max="2030-12-31">
    <input type="text"  name="topic" required placeholder="Тема Встречи">
    <input type="number"  name="duration"  required placeholder="Длительность">
    <input type="number"  name="password"  required placeholder="Пароль">
</center>