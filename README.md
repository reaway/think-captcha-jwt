# think-captcha

## 安装
```bash
composer require reaway/think-captcha-jwt
```

## 用法
安装依赖
```bash
composer require firebase/php-jwt
```
ThinkPHP控制器中使用

```php
namespace app\controller;

use Exception;
use app\BaseController;
use think\Response;
use Think\Component\CaptchaJwt\Facade\Captcha as CaptchaJwt;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Captcha extends BaseController
{
    /**
     * 生成验证码
     * @return Response
     * @throws Exception
     */
    public function index(): Response
    {
        $tokenId = base64_encode(random_bytes(32)); // 生成随机ID
        $issuedAt = time(); // JWT的创建时间
        $notBefore = $issuedAt + 10; // 在创建10秒后才能使用
        $expire = $issuedAt + 60; // JWT的过期时间

        $captcha = CaptchaJwt::create();
        $data = [
            'iat' => $issuedAt,         // JWT的创建时间
            'jti' => $tokenId,          // JWT的随机ID
            'nbf' => $notBefore,        // 在创建10秒后才能使用
            'exp' => $expire,           // JWT的过期时间
            'data' => [                 // JWT的数据部分
                'code' => $captcha['key'], // 验证码
            ]
        ];

        $jwt = JWT::encode($data, config('jwt.secret'), 'HS256'); // 生成JWT
        
        return json([
                'code' => 0,
                'msg' => 'success',
                'data' => [
                    'token' => $jwt,
                    'content_type' => $captcha['content_type'],
                    'content_base64' => $captcha['content_base64']
                ]
            ]
        );
    }

    /**
     * 异步验证验证码
     * @return Response
     * @throws Exception
     */
    public function check(): Response
    {
        $param = [
            'captcha' => $this->request->param('captcha', '', 'trim'),
            'token' => $this->request->param('token', '', 'trim')
        ];
        
        //todo 验证提交参数
        
        try {
            $decoded = JWT::decode($param['token'], new Key(config('jwt.secret'), 'HS256'));
            $result = CaptchaJwt::check($decoded->data->code, $param['captcha']);
            $data = [
                'code' => 0,
                'msg' => 'success',
                'data' => $result
            ];
        } catch (Exception $e) {
            $data = [
                'code' => 1,
                'msg' => $e->getMessage(),
                'data' => false
            ];
        }
        
        return json($data);
    }
}
```
## 文档
