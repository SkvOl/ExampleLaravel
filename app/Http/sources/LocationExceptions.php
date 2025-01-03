<?php

namespace App\Http\sources;

use App\Http\sources\Mixin;

class LocationExceptions{
    use Mixin;

    private ?string $ip;
    private ?string $url;

    private array $urlExceptions;
    private array $ipExceptions;

    public function __construct(?string $ip = null, ?string $url = null)
    {
        $this->ip = $ip;
        $this->url = $url;
    }

    public function setUrlExceptions(array $urlExceptions): void
    {
        $this->urlExceptions = $urlExceptions;
    }

    public function setIpExceptions(array $ipExceptions): void
    {
        $this->ipExceptions = $ipExceptions;
    }

    public function isLocal(): bool
    {
        return str_contains($this->ip, env('LOCAL_IP'));
    }

    public function isUrlExceptions(): bool
    {
        return self::array_in_like($this->url, $this->urlExceptions) OR self::in_array_like($this->url, $this->urlExceptions);
    }

    public function isIpExceptions(): bool
    {
        return self::array_in_like($this->ip, $this->ipExceptions) OR self::in_array_like($this->ip, $this->ipExceptions);
    }
}