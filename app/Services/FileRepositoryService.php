<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileRepositoryService
{
    protected string $disk;

    public function __construct(string $disk = 'local') // 'public', 's3', etc.
    {
        $this->disk = $disk;
    }

    /**
     * Save binary content to a file.
     *
     * @param string $path Relative path or filename (e.g., 'files/image.jpg')
     * @param string $binaryContent Binary data to be stored
     * @return string Path to the stored file
     * @throws \Exception
     */
    public function saveBinary(string $path, string $binaryContent): string
    {
        $stored = Storage::disk($this->disk)->put($path, $binaryContent);

        if (!$stored) {
            throw new \Exception("Failed to save binary content to: $path");
        }

        return $path;
    }

    /**
     * Generate a unique file name and save binary content.
     *
     * @param string $directory Directory inside disk (e.g. 'uploads')
     * @param string $binaryContent
     * @param string|null $extension (e.g. 'jpg', 'pdf')
     * @return string
     */
    public function saveBinaryWithUniqueName(string $directory, string $binaryContent, ?string $extension = null): string
    {
        // $filename = Str::uuid()->toString();
        // if ($extension) {
        //     $filename .= '.' . ltrim($extension, '.');
        // }

        $path = $directory . '/' . $extension;

        return $this->saveBinary($path, $binaryContent);
    }
}
