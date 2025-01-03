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

    /**
     * Задаём исключения для url. 
     * @param array $urlExceptions [url1, url2, ...]
     * @return void
     */
    public function setUrlExceptions(array $urlExceptions): void
    {
        $this->urlExceptions = $urlExceptions;
    }

    /**
     * Задаём исключения для ip. 
     * @param array $ipExceptions [ip1, ip1, ...]
     * @return void
     */
    public function setIpExceptions(array $ipExceptions): void
    {
        $this->ipExceptions = $ipExceptions;
    }

    /**
     * Проверяем, находится ли ip клиента в нашей локальной сети для персонализации response. 
     * @return bool
     */
    public function isLocal(): bool
    {
        return str_contains($this->ip, env('LOCAL_IP'));
    }

    /**
     * Проверяем, находится ли url клиента в списке исключений для персонализации response. 
     * @return bool
     */
    public function isUrlExceptions(): bool
    {
        return self::array_in_like($this->url, $this->urlExceptions) OR self::in_array_like($this->url, $this->urlExceptions);
    }

    /**
     * Проверяем, находится ли ip клиента в списке исключений для персонализации response. 
     * @return bool
     */
    public function isIpExceptions(): bool
    {
        return self::array_in_like($this->ip, $this->ipExceptions) OR self::in_array_like($this->ip, $this->ipExceptions);
    }
}
