<?php

namespace App\Customs;

use Log;

class Tickets {

    protected $apiKey;
    protected $apiURL;

    public function __construct($url, $key) {
        if($url != null && $key != null) { $this->apiURL = $url; $this->apiKey = $key; } else { die(json_encode(array('message' => 'exception in initial class Tickets()'))); }
    }

    protected function request($method, $route, $data = null) {
        Log::debug(print_r($data, true));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiURL.$route);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: token='.$this->apiKey,
        ));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if($method == 'POST') {
            Log::debug($this->apiURL.$route.http_build_query($data));
            Log::debug(http_build_query($data));
            // https://github.com/NikitchenkoSergey/test-api/blob/master/public/index.php
            // 33 incorrect and other if ($_POST) cant be array ?? [] = allTime here 
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        } else { curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, "User-Agent: System/1.0 (compatible; Tellarion)");
        return curl_exec($ch);
    }

    /* list shows */

    public function shows() {
        return json_decode($this->request('GET', '/shows'));
    }

    /* list show by Id and get list events */

    public function showEvents($idShow = null) {
        return json_decode($this->request('GET', "/shows/{$idShow}/events"));
    }

    /* list show by Id and get places */

    public function showPlaces($idEvent = null) {
        return json_decode($this->request('GET', "/events/{$idEvent}/places"));
    }

    /* send post data for booking */

    public function bookingSeat($idEvent, $name, $idSeats) {
        $data = array('name' => (string) $name, 'places' => (array) $idSeats);
        return json_decode($this->request('POST', "/events/{$idEvent}/reserve", $data));
    }


}

?>