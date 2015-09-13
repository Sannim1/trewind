<?php

namespace App\Contracts\Repository;

use Laravel\Socialite\AbstractUser as SocialUser;

/**
 * interface to which implementations of the user repository should adhere to
 *
 * @package App\Contracts
 * @author
 **/
interface UserRepositoryInterface
{

    /**
     * finds a user by provider ID or create
     *
     * @param  string   $provider
     * @param  int      $providerId
     * @param  array    $userData
     * @return App\Models\User
     */
    public function findUserByProviderOrCreate($provider, SocialUser $userData);

    /**
     * finds a user by their email address
     * @param  string $email
     * @return App\Models\User
     */
    public function findUserByEmail($email);
} // END interface UserRepositoryInterface
