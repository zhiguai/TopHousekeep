<?php

namespace app\api\validate;

use think\Validate;

class Cards extends Validate
{
    //定义验证规则
    protected $rule =   [
        'uid' => 'require|number',    
        'price' => 'require|float',    

        'introduction' => 'require',
        'content' => 'require',

        'good' => 'number',
        'comments' => 'number',

        'status' => 'boolean',
        'top' => 'boolean',
        'ban' => 'boolean',
    ];

    //定义错误信息
    protected $message  =   [
        'uid.require' => 'uid不得为空',
        'uid.number' => 'uid格式错误',

        'price.require' => 'price不得为空',
        'price.float' => 'price格式错误',

        'content.require' => 'content不得为空',
        'introduction.require' => 'introduction不得为空',

        'good.number' => 'good格式非法',
        'comments.number' => 'comments格式非法',
        //'tag.array' => 'tag格式非法',
        'status.boolean' => 'status格式非法',

        //'time.date' => 'time格式非法',
        //'ip.ip' => 'ip格式非法',
        'top.boolean' => 'top格式非法',
        'ban.boolean' => 'ban格式非法',
    ];

}
