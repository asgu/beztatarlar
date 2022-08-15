<?php


namespace Modules\Auth\Dto;


use Illuminate\Http\Request;

class UserLoginDto extends AuthUserDto
{
    /** @var mixed|string */
    public $email;

    /** @var mixed|string */
    public $password;

    public static function populateByRequest(Request $request): UserLoginDto
    {
        return static::populateByArray([
            'email'      => $request->post('email'),
            'password'   => $request->post('password'),
            'userHost'   => $request->fullUrl(),
            'userAgent'  => $request->userAgent(),
            'userIp'     => $request->ip(),
            'deviceId'   => $request->post('deviceId'),
            'deviceType' => $request->post('deviceType'),
        ]);
    }
}
