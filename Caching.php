<?php
class Cache
{
    private $expireTimeInMinutes;
    private $cacheDirectory = 'Caching';

    function __construct(int $expireTimeInMinutes)
    {
        // Check if the cache directory exsists, if not created it.
        if (!file_exists($this->cacheDirectory)) {
            mkdir($this->cacheDirectory);
        }

        $this->expireTimeInMinutes = $expireTimeInMinutes;
    }

    public function AddToCache(string $filename, $data): void
    {
        $tempFileName = time() . '.temp';
        file_put_contents($this->cacheDirectory . '/' . $tempFileName, json_encode($data), LOCK_EX);

        rename($this->cacheDirectory . '/' . $tempFileName, $this->cacheDirectory . '/' . $filename);
    }

    public function GetFromCache(string $filename)
    {
        return json_decode(file_get_contents($this->cacheDirectory . '/' . $filename));
    }

    public function Exists(string $filename): bool
    {
        return file_exists($this->cacheDirectory . '/' . $filename);
    }

    public function IsExpired(string $filename): bool
    {
        return filemtime($this->cacheDirectory . '/' . $filename) < (time() - 60 * $this->expireTimeInMinutes);
    }
}
