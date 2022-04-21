<?php
require_once 'config.php';
use GuzzleHttp\Client;
  
function create_meeting() {
    $client = new Client(['base_uri' => 'https://api.zoom.us']);
  
    $db = new DB();
    $arr_token = $db->get_access_token();
    $accessToken = $arr_token->access_token;
  
    try {
        $response = $client->request('POST', '/v2/users/me/meetings', [
            "headers" => [
                "Authorization" => "Bearer $accessToken"
            ],
            'json' => [
                "topic" => htmlspecialchars($_POST['topic']),
                "type" => 2,
                "start_time" => htmlspecialchars($_POST['start_time']),
                "duration" => htmlspecialchars($_POST['duration']),
                "password" => htmlspecialchars($_POST['password'])
            ],
        ]);
  
        $data = json_decode($response->getBody());
        echo "Join URL: ". $data->join_url;
        echo "<br>";
        echo "Meeting Password: ". $data->password;
  
    } catch(Exception $e) {
        if( 401 == $e->getCode()) {
            $refresh_token = $db->get_refersh_token();
  
            $client = new Client(['base_uri' => 'https://zoom.us']);
            $response = $client->request('POST', '/oauth/token', [
                "headers" => [
                    "Authorization" => "Basic ". base64_encode(CLIENT_ID.':'.CLIENT_SECRET)
                ],
                'form_params' => [
                    "grant_type" => "refresh_token",
                    "refresh_token" => $refresh_token
                ],
            ]);
            $db->update_access_token($response->getBody());
  
            create_meeting();
        } else {
            echo $e->getMessage();
        }
    }
}
  
create_meeting();
