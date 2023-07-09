<?php

namespace app\api\validate;

use think\Validate;

class CardsComments extends Validate
{
    //定义验证规则
    protected $rule =   [
        'content'   => 'require|length:1,1024',
    ];

    //定义错误信息
    protected $message  =   [
        'content.require' => '内容不得为空',
        'content.length'     => '内容超出范围(1-1024)',
    ];
}
