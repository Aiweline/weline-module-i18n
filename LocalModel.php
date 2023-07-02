<?php
declare(strict_types=1);

/*
 * 本文件由 秋枫雁飞 编写，所有解释权归Aiweline所有。
 * 作者：Admin
 * 邮箱：aiweline@qq.com
 * 网址：aiweline.com
 * 论坛：https://bbs.aiweline.com
 * 日期：2023/7/2 13:13:46
 */

namespace Weline\I18n;

use Weline\Framework\Database\Model;
use Weline\Framework\Http\Cookie;

class LocalModel extends Model implements LocalModelInterface
{
    public array $_unit_primary_keys = [self::fields_ID, self::fields_local_code];
    public array $_index_sort_keys = [self::fields_ID, self::fields_local_code];
    use TraitLocalModel;
}