<?php

declare(strict_types=1);

class FileFormValidator
{
    private string $name;
    private string $dir_upload;
    private string $typeMime;
    private string $extfile;
    private string $filename;
    private array $allowedExt;

    public function __construct(string $name, string $dir, array $exts)
    {
        $this->name = $name;
        $this->dir_upload = $dir;
        $this->allowedExt = $exts;
        $this->filename = $_FILES[$this->name]["name"];
        $this->typeMime = $_FILES[$this->name]["type"];
        $this->extfile = pathinfo($this->filename, PATHINFO_EXTENSION);
    }

    public function isError(): bool
    {
        return isset($_FILES[$this->name]) && $_FILES[$this->name]["error"] !== 0 ? true : false;
    }

    public function isExtensionAllowed(): bool
    {
        return array_key_exists($this->extfile, $this->allowedExt) ? true : false;
    }

    public function isFileExist(): bool
    {
        return file_exists($this->dir_upload . $this->filename) ? true : false;
    }

    public function moveFileTo(): void
    {
        move_uploaded_file($_FILES[$this->name]["tmp_name"], $this->dir_upload . $this->filename);
    }

    /**
     * Get the value of filename
     */
    public function getFilename(): string
    {
        return $this->filename;
    }
}
