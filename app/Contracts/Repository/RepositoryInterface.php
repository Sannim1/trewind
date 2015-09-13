<?php

namespace App\Contracts\Repository;

use Laravel\Socialite\Two\User as SocialUser;

/**
 * interface to which all repository implementations should adhere to
 *
 * @package App\Contracts
 * @author
 **/
interface RepositoryInterface
{
    /**
     * get all models
     * @param  array  $columns
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getAll($columns = ['*']);

    /**
     * get a particular model by id
     * @param  int $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getById($id);
} // END interface UserRepositoryInterface
