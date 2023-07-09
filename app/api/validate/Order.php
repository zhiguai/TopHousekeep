<?php

namespace app\api\validate;

use think\Validate;

class Order extends Validate
{
    //定义验证规则
    protected $rule =   [
        'price' => 'require|float',   
        'address' => 'require|length:1,256',
        'note' => 'require|length:1,256',
        'service_date' => 'require|date',
    ];

    //定义错误信息
    protected $message  =   [
        'price.require' => 'price不得为空',
        'price.float' => 'price格式错误',

        'note.require' => 'note不得为空',
        'note.length' => 'note不符合长度1-256字符',

        'address.require' => 'address不得为空',
        'address.length' => 'address不符合长度1-256字符',

        'service_date.require' => 'service_date不得为空',
        'service_date.date' => 'service_date格式非法',
    ];
}
