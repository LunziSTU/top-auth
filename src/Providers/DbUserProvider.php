<?php

namespace Lunzi\TopAuth\Providers;

use Lunzi\TopAuth\Contracts\Authenticatable as UserContract ;
use Lunzi\TopAuth\Contracts\UserProvider;
use Lunzi\TopAuth\GenericUser;
use think\facade\Db;

class DbUserProvider implements UserProvider
{
    /**
     * 当前用户表名
     *
     * @var string
     */
    protected $table;

    /**
     * 主键
     * @var string
     */
    protected $pk;

    public function __construct($table)
    {
        $this->table = $table;
        $this->pk = 'id';
    }

    public function retrieveById($identifier)
    {
        $user = Db::table($this->table)->where($this->pk, $identifier)->find();
        return $this->getGenericUser($user);
    }

    /**
     * @param mixed $identifier
     * @param string $token
     * @return UserContract|GenericUser|void|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function retrieveByToken($identifier, $token)
    {
        $user = $this->getGenericUser(
            Db::table($this->table)->where($this->pk,$identifier)->find()
        );

        return $user && $user->getRememberToken() && hash_equals($user->getRememberToken(), $token)
            ? $user : null;
    }

    /**
     * @param UserContract $user
     * @param string $token
     * @throws \think\db\exception\DbException
     */
    public function updateRememberToken(UserContract $user, $token)
    {
        Db::table($this->table)
            ->where($user->getAuthIdentifierName(), $user->getAuthIdentifier())
            ->update([$user->getRememberTokenName() => $token]);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     */
    public function retrieveByCredentials(array $credentials)
    {

    }

    /**
     * @param $user
     * @return GenericUser|void
     */
    protected function getGenericUser($user)
    {
        if (! is_null($user)) {
            return new GenericUser((array) $user);
        }
    }

    /**
     * @param UserContract $user
     * @param array $credentials
     * @return bool|void
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {

    }
}