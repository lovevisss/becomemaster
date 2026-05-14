<?php

return [
    'base_url' => env('CAS_BASE_URL', 'https://cas.paas.zufedfc.edu.cn/cas'),
    'service_path' => env('CAS_SERVICE_PATH', '/login'),
    'logout_return_path' => env('CAS_LOGOUT_RETURN_PATH', '/login'),
];

