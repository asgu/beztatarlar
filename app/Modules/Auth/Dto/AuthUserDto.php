<?php


namespace Modules\Auth\Dto;


use Illuminate\Http\Request;
use Netibackend\Laravel\DTO\AbstractDto;

class AuthUserDto extends AbstractDto
{
    /** @var mixed */
    public $deviceId;

    /** @var mixed */
    public $deviceType;

    /** @var mixed */
    public $userHost;

    /** @var mixed */
    public $userAgent;

    /** @var mixed */
    public $userIp;

    public static function populateByRequest(Request $request): AuthUserDto
    {
        return static::populateByArray([
            'userHost'   => $request->fullUrl(),
            'userAgent'  => $request->userAgent(),
            'userIp'     => $request->ip(),
            'deviceId'   => $request->post('deviceId'),
            'deviceType' => $request->post('deviceType'),
        ]);
    }
}
