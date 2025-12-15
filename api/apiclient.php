<?php
require_once __DIR__ . "/loadenv.php";

class ApiClient {
    private $BASE_AQI;
    private $BASE_WEATHER;
    private $API_KEY;

    public function __construct() {
        $this->BASE_AQI = $_ENV["OPENWEATHER_API"] ?? null;
        $this->BASE_WEATHER = $_ENV["OPENWEATHER_WEATHER"] ?? null;
        $this->API_KEY = $_ENV["OPENWEATHER_KEY"] ?? null;
    }

    public function getAqi($lat, $lon) {
        if (!$this->BASE_AQI || !$this->API_KEY) return null;

        $url = "{$this->BASE_AQI}?lat={$lat}&lon={$lon}&appid={$this->API_KEY}";
        return json_decode(file_get_contents($url), true);
    }

    public function getWeather($lat, $lon) {
        if (!$this->BASE_WEATHER || !$this->API_KEY) return null;

        $url = "{$this->BASE_WEATHER}?lat={$lat}&lon={$lon}&appid={$this->API_KEY}&units=metric";
        return json_decode(file_get_contents($url), true);
    }

    public function getUdara($lat, $lon) {
        return [
            "aqi" => $this->getAqi($lat, $lon),
            "weather" => $this->getWeather($lat, $lon)
        ];
    }
}
