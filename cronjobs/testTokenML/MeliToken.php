<?php

namespace tokenML2;

class MeliToken
{
    private $token_file;

    public function __construct()
    {
        $this->token_file = __DIR__ . '/tokens.json';
    }

    public function getTokenMeli()
    {
        if (!file_exists($this->token_file)) {
            throw new \Exception("Token file not found: " . $this->token_file);
        }

        $json = file_get_contents($this->token_file);
        $data = json_decode($json, true);

        if (!isset($data['access_token'])) {
            throw new \Exception("Token not found in JSON file.");
        }

        return $data['access_token'];
    }
}

