<?php
declare(strict_types=1);

/*
 * 本文件由 秋枫雁飞 编写，所有解释权归Aiweline所有。
 * 作者：Admin
 * 邮箱：aiweline@qq.com
 * 网址：aiweline.com
 * 论坛：https://bbs.aiweline.com
 * 日期：2023/7/2 13:09:23
 */

namespace Weline\I18n;

use Weline\Framework\Database\Api\Db\Ddl\TableInterface;
use Weline\Framework\Database\Model;
use Weline\Framework\Http\Cookie;
use Weline\Framework\Setup\Data\Context;
use Weline\Framework\Setup\Db\ModelSetup;

trait TraitLocalModel
{
    function __init()
    {
        parent::__init();
        if (!CLI) {
            $this->where(self::fields_local_code, Cookie::getLang(), '=', 'or')->where(self::fields_local_code.' is null');
        }
    }

    /**
     * @inheritDoc
     */
    public function setup(ModelSetup $setup, Context $context): void
    {
        $this->install($setup, $context);
    }

    /**
     * @inheritDoc
     */
    public function upgrade(ModelSetup $setup, Context $context): void
    {
        // TODO: Implement upgrade() method.
    }

    /**
     * @inheritDoc
     */
    public function install(ModelSetup $setup, Context $context): void
    {
//        $setup->dropTable();
        if (!$setup->tableExist()) {
            $setup->createTable()
                  ->addColumn(
                      self::fields_ID,
                      TableInterface::column_type_INTEGER,
                      0,
                      'not null',
                      '属性ID'
                  )
                  ->addColumn(
                      self::fields_local_code,
                      TableInterface::column_type_VARCHAR,
                      6,
                      'not null',
                      '当地码'
                  )
                  ->addColumn(
                      self::fields_name,
                      TableInterface::column_type_VARCHAR,
                      255,
                      'not null',
                      '当地名称'
                  )
                  ->addConstraints('primary key (' . self::fields_ID . ',' . self::fields_local_code . ')')
                  ->create();
        }
    }
}