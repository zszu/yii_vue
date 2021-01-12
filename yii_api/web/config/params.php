<?php
const STATUS_ACTIVE = 1;
const STATUS_INACTIVE = 0;
const STATUS_DELETED = -1;

const STATUS_YES = 1;
const STATUS_NO = 0;
return [
    // 图片保存路径
    'uploadPath' => '/data/attachment/',
    'adminEmail' => 'admin@example.com',
    'list' => [
        'read' => [1 => '已读', 0 => '未读'],
        'qy_ty' => [1 => '启用', 0 => '停用'],
        's_f' => [1 => '是', 0 => '否'],
    
    ],
    //   api
    'user.accessTokenExpire' => 1*24*3600,
];
