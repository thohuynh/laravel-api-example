<?php

namespace App\Repositories;

use App\Entities\User;
use App\Repositories\Core\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Specify Query Builder
     *
     * @return Builder
     */
    public function query()
    {
        return User::query();
    }
}
