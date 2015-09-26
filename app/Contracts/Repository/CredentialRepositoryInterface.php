<?php

namespace App\Contracts\Repository;

/**
 * interface to which implementations of the credentials
 * repository should adhere to.
 *
 * @package App\Contracts
 * @author Abdulmusawwir Sanni<sanniabdulmusawwir@gmail.com>
 **/
interface CredentialRepositoryInterface
{
    /**
     * gets a user's credentials by the provider_id
     * @param  string   $provider
     * @param  int      $provider_id
     * @return App\Models\Credential
     */
    public function getUserCredentialByProvider($provider, $providerId);
} // END interface UserRepositoryInterface
