<?php

namespace App\Repositories;

use App\Models\Credential;
use App\Contracts\Repository\CredentialRepositoryInterface;

/**
 * credentials repository
 *
 * @package App
 * @author Abdulmusawwir Sanni<sanniabdulmusawwir@gmail.com>
 **/
class CredentialRepository implements CredentialRepositoryInterface
{
    protected $credentials;

    public function __construct(Credential $credentials)
    {
        $this->credentials = $credentials;
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
