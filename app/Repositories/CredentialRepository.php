<?php

namespace App\Repositories;

use App\Models\Credential;
use App\Contracts\Repository\RepositoryInterface;
use App\Contracts\Repository\CredentialRepositoryInterface;

/**
 * credentials repository
 *
 * @package App
 * @author Abdulmusawwir Sanni<sanniabdulmusawwir@gmail.com>
 **/
class CredentialRepository
    implements
        RepositoryInterface,
        CredentialRepositoryInterface
{
    protected $credentials;

    public function __construct(Credential $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * get all models
     * @param  array  $columns
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getAll($columns = ['*'])
    {
        return $this->credentials->all();
    }

    /**
     * get a particular model by id
     * @param  int $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getById($id)
    {
        return $this->credentials->find($id);
    }

    /**
     * gets a user's credentials by the provider_id
     * @param  string   $provider
     * @param  int      $provider_id
     * @return App\Models\Credential
     */
    public function getUserCredentialByProvider($provider, $providerId)
    {
        return $this->credentials->where('provider', '=', $provider)
            ->where('provider_id', '=', $providerId)
            ->first();
    }
} // END class UserRepository
