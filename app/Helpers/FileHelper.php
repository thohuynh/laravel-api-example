<?php

namespace App\Helpers;

use Exception;
use Illuminate\Http\UploadedFile;
use Storage;

/**
 * Class FileHelper.
 *
 * @package App\Helpers
 */
class FileHelper
{
    /** @var $store Storage */
    protected $store;

    const FILE_TYPE_AVATAR = 'avatar';

    const FILE_TYPE_ASSESSMENT = 'assessments';

    /**
     * @var string $rootUploads.
     */
    public $rootUploads = 'uploads';

    /**
     * List directories to save in local.
     *
     * @var array
     */
    public $directories = [
        'avatar' => self::FILE_TYPE_AVATAR,
        'assessments' => self::FILE_TYPE_ASSESSMENT
    ];

    /**
     * FileHelper constructor.
     */
    public function __construct()
    {
        $this->initStorage();
        $this->store = Storage::disk('local');
    }

    /**
     * Init storage with config.
     */
    private function initStorage()
    {
        $rootDir = storage_path("app");

        if (!is_dir($rootDir)) {
            mkdir($rootDir);
            chmod($rootDir, 0777);
        }

        foreach ($this->directories as $directory) {
            $path = "$rootDir/$directory";
            if (!is_dir($path)) {
                mkdir($path);
                chmod($path, 0777);
            }
        }
    }

    /**
     * @param $file UploadedFile
     * @param $type string
     * @return null|string
     * @throws Exception
     */
    public function upload($file, $type)
    {
        try {
            $filename = strtr(':type-:time.:ext', [
                ':type' => $type, ':time' => time(),
                ':ext' => $file->getClientOriginalExtension()
            ]);

            $result = $this->store->putFileAs($this->directories[$type], $file, $filename);

            return !empty($result) ? $filename : null;
        } catch (Exception $e) {
            throw new Exception('UPLOAD_ERROR');
        }
    }

    /**
     * Save file when upload into local.
     *
     * @param $fileContent
     * @param $fileName
     * @param $type
     * @return null
     * @throws Exception
     */
    public function saveFile($fileContent, $fileName, $type)
    {
        try {
            $result = $this->store->putFileAs($this->directories[$type], $fileContent, $fileName);

            return !empty($result) ? $fileName : null;
        } catch (Exception $e) {
            throw new Exception("[SAVE_FILE_LOCAL_ERROR]: [FILE_NAME: $fileName] [TYPE: $type]");
        }
    }

    /**
     * Remove file uploaded local.
     *
     * @param $fileName
     * @param $type
     */
    public function removeFile($fileName, $type)
    {
        $path = $this->directories[$type] . "/$fileName";
        $this->store->delete($path);
    }

    /**
     * Get path directories upload local by type.
     * @param $type
     * @return string
     */
    public function getPathDirByType($type)
    {
        return $this->store->path($type);
    }
}
