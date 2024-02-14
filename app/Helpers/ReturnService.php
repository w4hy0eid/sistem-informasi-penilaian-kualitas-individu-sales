<?php

namespace App\Helpers;

class ReturnService {
    public function Return($data, bool $valid, string $message) {
        return [
            "data" => $data,
            "valid" => $valid,
            "message" => $message
        ];
    }
}
