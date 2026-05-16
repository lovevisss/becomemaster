<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CasService
{
    public function serviceUrl(): string
    {
        return $this->serviceUrlForReturn('/');
    }

    public function serviceUrlForReturn(string $returnUrl): string
    {
        $fixedServiceUrl = trim((string) config('cas.service_url', ''));
        if ($fixedServiceUrl !== '') {
            return $fixedServiceUrl;
        }

        $path = ltrim((string) config('cas.service_path', '/login'), '/');
        $route = route('login', ['returnUrl' => $returnUrl], false);

        return url($route ?: $path);
    }

    public function buildLoginUrl(string $serviceUrl): string
    {
        return rtrim((string) config('cas.base_url'), '/') . '/login?service=' . urlencode($serviceUrl);
    }

    public function buildLogoutUrl(string $returnUrl): string
    {
        return rtrim((string) config('cas.base_url'), '/') . '/logout?service=' . urlencode($returnUrl);
    }

    public function validateTicket(string $serviceUrl, string $ticket): ?array
    {
        $url = rtrim((string) config('cas.base_url'), '/') . '/serviceValidate';
        $response = Http::timeout(8)->get($url, [
            'service' => $serviceUrl,
            'ticket' => $ticket,
        ]);

        if (! $response->ok()) {
            return null;
        }

        $xml = @simplexml_load_string($response->body());
        if (! $xml) {
            return null;
        }

        $namespaces = $xml->getNamespaces(true);
        $casNs = $namespaces['cas'] ?? 'http://www.yale.edu/tp/cas';
        $serviceResponse = $xml->children($casNs);

        if (! isset($serviceResponse->authenticationSuccess)) {
            return null;
        }

        $success = $serviceResponse->authenticationSuccess;
        $user = trim((string) $success->user);
        if ($user === '') {
            return null;
        }

        $attributes = [];
        if (isset($success->attributes)) {
            foreach ($success->attributes->children($casNs) as $key => $value) {
                $stringValue = trim((string) $value);
                if (array_key_exists($key, $attributes)) {
                    $attributes[$key] = is_array($attributes[$key])
                        ? [...$attributes[$key], $stringValue]
                        : [$attributes[$key], $stringValue];
                } else {
                    $attributes[$key] = $stringValue;
                }
            }
        }

        return [
            'user' => $user,
            'attributes' => $attributes,
        ];
    }
}
