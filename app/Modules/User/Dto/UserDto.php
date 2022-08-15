<?php


namespace Modules\User\Dto;


use Modules\User\Modules\Profile\Dto\UserProfileDto;
use Netibackend\Laravel\DTO\AbstractDto;

class UserDto extends AbstractDto
{
    /** @var mixed|int */
    public $positionId;

    /** @var mixed|int */
    public $mukhtasibatId;

    /** @var mixed|int */
    public $parishId;

    /** @var UserProfileDto|null  */
    protected ?UserProfileDto $_profile = null;

    /**
     * @return UserProfileDto|null
     */
    public function getProfile(): ?UserProfileDto
    {
        return $this->_profile;
    }

    /**
     * @param $value
     */
    public function setProfile($value): void
    {
        if ($value) {
            $this->_profile = UserProfileDto::populateByArray($value);
        }
    }
}
