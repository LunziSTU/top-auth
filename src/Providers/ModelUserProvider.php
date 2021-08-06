<?php

namespace Lunzi\TopAuth\Providers;

use Lunzi\TopAuth\Contracts\UserProvider;

class ModelUserProvider implements UserProvider
{
    /**
     * 当前用户模型
     *
     * @var string
     */
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function retrieveById($identifier)
    {
        $model = $this->createModel();
        return $model->where($model->getAuthIdentifierName(), $identifier)->find();
    }

    public function retrieveByToken($identifier, $token)
    {
        $model = $this->createModel();

        $retrievedModel = $model->where(
            $model->getAuthIdentifierName(), $identifier
        )->find();

        if (! $retrievedModel) {
            return;
        }

        $rememberToken = $retrievedModel->getRememberToken();

        return $rememberToken && hash_equals($rememberToken, $token)
            ? $retrievedModel : null;
    }

    public function updateRememberToken(\Lunzi\TopAuth\Contracts\Authenticatable $user, $token)
    {
        $user->setRememberToken($token);

        $user->save();
    }

    public function retrieveByCredentials(array $credentials)
    {
        // TODO: Implement retrieveByCredentials() method.
    }

    public function validateCredentials(\Lunzi\TopAuth\Contracts\Authenticatable $user, array $credentials)
    {
        // TODO: Implement validateCredentials() method.
    }

    /**
     * 创建模型实例
     *
     */
    public function createModel()
    {
        $class = '\\'.ltrim($this->model, '\\');

        return new $class;
    }
}