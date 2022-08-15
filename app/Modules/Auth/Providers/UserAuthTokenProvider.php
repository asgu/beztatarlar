<?php

namespace Modules\Auth\Providers;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Container\BindingResolutionException;
use InvalidArgumentException;
use LogicException;
use Modules\Auth\Contracts\UserAuthTokenStorageInterface;

class UserAuthTokenProvider implements UserProvider
{
    protected UserAuthTokenStorageInterface $storage;

    /**
     * UserAuthTokenProvider constructor.
     *
     * @param $config
     *
     * @throws BindingResolutionException
     */
    public function __construct($config)
    {
        $storageClass = $config['storageClass'] ?? null;

        if (!$storageClass) {
            throw new InvalidArgumentException('No class specified for storage');
        }

        $storage = app()->make($storageClass);

        if (!$storage instanceof UserAuthTokenStorageInterface) {
            throw new InvalidArgumentException('Storage must implement the interface "UserAuthTokenStorageInterface"');
        }

        $this->storage = $storage;
    }

    /**
     * @inheritDoc
     */
    public function retrieveById($identifier): ?UserContract
    {
        throw new LogicException('This ' . __METHOD__ . ' method is not implemented.');
    }

    /**
     * @inheritDoc
     */
    public function retrieveByToken($identifier, $token): ?UserContract
    {
        throw new LogicException('This ' . __METHOD__ . ' method is not implemented.');
    }

    /**
     * @inheritDoc
     */
    public function updateRememberToken(UserContract $user, $token): void
    {
        throw new LogicException('This ' . __METHOD__ . ' method is not implemented.');
    }

    /**
     * @inheritDoc
     */
    public function retrieveByCredentials(array $credentials): ?UserContract
    {
        return $this->storage->getAuthenticatableByToken($credentials['api_token'] ?? null);
    }

    public function validateCredentials(UserContract $user, array $credentials): bool
    {
        throw new LogicException('This ' . __METHOD__ . ' method is not implemented.');
    }
}
