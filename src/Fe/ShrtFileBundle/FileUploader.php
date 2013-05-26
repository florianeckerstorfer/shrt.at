<?php
/**
 * This file is part of shrt.at
 * (c) 2013 Florian Eckerstorfer
 */

namespace Fe\ShrtFileBundle;

use Gaufrette\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * FileUploader
 *
 * @package    FeShrtFileBundle
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
 */
class FileUploader
{
    /** @var Filesystem */
    private $filesystem;

    /** @var string */
    private $baseUrl;

    /**
     * Constructor.
     *
     * @param Filesystem $filesystem The filesystem
     * @param string     $baseUrl    The base URL of the Amazon S3 bucket
     */
    public function __construct(Filesystem $filesystem, $baseUrl)
    {
        $this->filesystem = $filesystem;
        $this->baseUrl = $baseUrl;
    }

    /**
     * Uploads the given file to S3 and returns the URL.
     *
     * @param UploadedFile $file The file to upload
     *
     * @return string The URL to the file on S3
     */
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
