<?php

namespace Lunzi\TopAuth;

use Lunzi\TopAuth\Contracts\UserProvider;
use Lunzi\TopAuth\Contracts\Authenticatable as AuthenticatableContract;

trait GuardHelpers
{
    /**
     * The currently authenticated user.
     *
     * @var AuthenticatableContract
     */
    protected $user;

    /**
     * The user provider implementation.
     *
     * @var UserProvider
     */
    protected $provider;

    /**
     * Determine if the current user is authenticated. If not, throw an exception.
     *
     * @return AuthenticatableContract
     *
     * @throws AuthenticationException
     */
    public function authenticate()
    {
        if (! is_null($user = $this->user())) {
            return $user;
        }

        throw new AuthenticationException;
    }

    /**
     * Determine if the guard has a user instance.
     *
     * @return bool
     */
    public function hasUser()
    {
        return ! is_null($this->user);
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        return ! is_null($this->user());
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        return ! $this->check();
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|string|null
     */
    public function id()
    {
        if ($this->user()) {
            return $this->user()->getAuthIdentifier();
        }
    }

    /**
     * Set the current user.
     *
     * @param AuthenticatableContract $user
     * @return $this
     */
    public function setUser(AuthenticatableContract $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the user provider used by the guard.
     *
     * @return UserProvider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set the user provider used by the guard.
     *
     * @param  UserProvider  $provider
     * @return void
     */
    public function setProvider(UserProvider $provider)
    {
        $this->provider = $provider;
    }
}