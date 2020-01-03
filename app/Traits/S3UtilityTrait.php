<?php

namespace App\Traits;

/**
 * Trait S3UtilityTrait.
 *
 * @package App\Contracts
 */
trait S3UtilityTrait
{
    /**
     * List directory.
     *
     * @var array
     */
    public static $directories = [
        'temporary'   => 'temporary',
        'assessments' => 'assessments',
    ];

    /**
     * Get S3 Key by type.
     *
     * @param $filename
     * @param $type
     * @param null $id
     * @return string
     */
    public function getKeyByType($filename, $type, $id = null)
    {
        $key = !empty($id)
            ? strtr(':type/:id/:filename', [
                ':id'       => $id,
                ':type'     => $type,
                ':filename' => $filename
            ])
            : strtr(':type/:filename', [
                ':type'       => $type,
                ':filename' => $filename
            ]);

        return $key;
    }
}
