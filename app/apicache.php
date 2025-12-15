<?php
class ApiCache {
    private $file;
    private $ttl;

    public function __construct($file = "cache/api_cache.json", $ttl = 300)
    {
        $this->file = $file;
        $this->ttl = $ttl;
    }

    public function get()
    {
        if (!file_exists($this->file)) return null;

        $content = json_decode(file_get_contents($this->file), true);

        if (time() - $content['timestamp'] > $this->ttl) {
            return null; // cache expired
        }

        return $content['data'];
    }

    public function save($data)
    {
        $content = [
            "timestamp" => time(),
            "data" => $data
        ];

        file_put_contents($this->file, json_encode($content, JSON_PRETTY_PRINT));
    }
}
