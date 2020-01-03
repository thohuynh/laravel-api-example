<?php

namespace App\Services\Aws;

use App\Traits\S3UtilityTrait;
use Aws\Exception\AwsException;
use Aws\Result;
use Aws\ResultInterface;
use Aws\S3\ObjectUploader;
use Aws\S3\S3Client;
use Exception;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Log;

/**
 * Class S3Service.
 *
 * @package App\Services\Aws
 */
class S3Service extends AwsBase
{
    use S3UtilityTrait;

    /** @var $s3 Filesystem */
    protected $s3;

    /** @var $client S3Client */
    protected $client;

    /** @var $bucket string */
    protected $bucket;

    /**
     * S3Service constructor.
     * @param string $bucket
     * @param string $region
     */
    public function __construct($bucket = null, $region = null)
    {
        parent::__construct();
        $this->bucket = !empty($bucket) ? $bucket : config('filesystems.disks.s3.bucket');

        if (!empty($region)) {
            $this->region = $region;
        }

        $this->initClient();
    }

    /**
     * Init S3
     */
    private function initClient()
    {
        if ($this->isLocal) {
            $config = $this->getLocalConfig();
        } else {
            $config = $this->getIamRoleConfig();
        }

        $this->client = new S3Client($config);

        $adapter = new AwsS3Adapter($this->client, $this->bucket);

        $this->s3 = new Filesystem($adapter);
    }

    /**
     * Get File URI by type
     *
     * @param $filename
     * @param string $type
     * @param null $id
     * @return null|string
     */
    public function getFileUri($filename, $type = 'temporary', $id = null)
    {
        try {
            $key = $this->getKeyByType($filename, $type, $id);

            return $this->getFileUriByKey($key);
        } catch (Exception $e) {
            Log::error($e);

            return null;
        }
    }

    /**
     * Get File URI by key
     *
     * @param $key
     * @param string $expires
     * @return null|string
     */
    public function getFileUriByKey($key, $expires = '+168 hours')
    {
        try {
            $cmd = $this->client->getCommand('GetObject', [
                'Bucket' => $this->bucket,
                'Key'    => $key
            ]);

            $request = $this->client->createPresignedRequest($cmd, $expires); //default 7 days

            return (string)$request->getUri();
        } catch (Exception $e) {
            Log::error($e);

            return null;
        }
    }

    /**
     * Upload Single File
     *
     * @param $file
     * @param $filename
     * @param string $type
     * @param null $id
     * @return null|string
     */
    public function upload($file, $filename, $type = 'temporary', $id = null)
    {
        $key      = $this->getKeyByType($filename, $type, $id);
        $uploader = new ObjectUploader($this->client, $this->bucket, $key, file_get_contents($file));
        $result   = $uploader->upload();

        if ($result["@metadata"]["statusCode"] == '200') {
            return $key;
        }

        return null;
    }

    /**
     * Upload file to S3 service with content.
     *
     * @param $fileContent
     * @param $filename
     * @param string $type
     * @param null $id
     * @return string|null
     */
    public function uploadContent($fileContent, $filename, $type = 'temporary', $id = null)
    {
        $key      = $this->getKeyByType($filename, $type, $id);
        $uploader = new ObjectUploader($this->client, $this->bucket, $key, $fileContent);
        $result   = $uploader->upload();

        if ($result["@metadata"]["statusCode"] == '200') {
            return $key;
        }

        return null;
    }

    /**
     * @param $file
     * @param $filename
     * @param string $type
     * @return mixed
     */
    public function uploadAsync($file, $filename, $type = 'temporary')
    {
        $key = $this->getKeyByType($filename, $type);

        $uploader = new ObjectUploader($this->client, $this->bucket, $key, file_get_contents($file));

        $promise = $uploader->promise();

        $data = $promise->then(function ($res) {
            return [true, $res];
        }, function ($err) {
            return [false, $err];
        });

        return $data;
    }


    /**
     * Move file s3.
     *
     * @param $sourceKey
     * @param $newKey
     * @return bool
     */
    public function move($sourceKey, $newKey)
    {
        try {
            $result = $this->client->copyObject([
                'Bucket'     => $this->bucket,
                'Key'        => $newKey,
                'CopySource' => "{$this->bucket}/{$sourceKey}",
            ]);

            if ($result instanceof ResultInterface) {
                $this->delete($sourceKey);
                return true;
            }

            if ($result instanceof AwsException) {
                return false;
            }

            return false;
        } catch (Exception $e) {
            Log::error($e);
            return false;
        }
    }

    /**
     * Delete an object from the bucket.
     *
     * @param $key
     * @return Result
     */
    public function delete($key)
    {
        return $this->client->deleteObject([
            'Bucket' => $this->bucket,
            'Key'    => $key
        ]);
    }
}
