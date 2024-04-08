<?php

declare(strict_types=1);

namespace App\Handler\File;

class FileHandler
{
    protected string $file_path;

    public function __construct(string $filePath)
    {
        $this->file_path = $filePath;
    }

    protected function read(): string
    {
        return file_get_contents($this->file_path);
    }

    protected function write(string $content): void
    {
        file_put_contents($this->file_path, $content);
    }

}
