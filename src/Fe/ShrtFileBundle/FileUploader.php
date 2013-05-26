<?php

namespace Fe\ShrtFileBundle;

use Gaufrette\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /** @var Filesystem */
    private $filesystem;

    /** @var string */
    private $baseUrl;

    public function __construct(Filesystem $filesystem, $baseUrl)
    {
        $this->filesystem = $filesystem;
        $this->baseUrl = $baseUrl;
    }

    public function upload(UploadedFile $file)
    {
        $filename = sprintf('%d/%d/%s-%s', date('Y'), date('m'), uniqid(), $file->getClientOriginalName());

        $adapter = $this->filesystem->getAdapter();
        $adapter->setMetadata($filename, array('contentType' => $file->getClientMimeType()));
        $adapter->write($filename, file_get_contents($file->getPathname()));

        // Return the URL to the uploaded file on S3
        return sprintf('%s/%s', $this->baseUrl, $filename);
    }
}