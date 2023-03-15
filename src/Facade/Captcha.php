<?php

namespace Think\Component\CaptchaJwt\Facade;

use think\Facade;

/**
 * Class Captcha
 * @package Think\Component\CaptchaJwt\Facade
 * @mixin \Think\Component\CaptchaJwt\Captcha
 */
class Captcha extends Facade
{
    protected static function getFacadeClass()
    {
        return 'Think\Component\CaptchaJwt\Captcha';
    }
}
