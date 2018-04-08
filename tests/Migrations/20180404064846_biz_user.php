<?php

use Phpmig\Migration\Migration;

class BizUser extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $biz = $this->getContainer();
        $connection = $biz['db'];


        $connection->exec("
          CREATE TABLE `biz_security_user` (
            `id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
            `username` VARCHAR(32) NOT NULL COMMENT '用户名',
            `email` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '邮箱',
            `mobile` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '手机号码',
            `nickname` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '昵称',
            `password` VARCHAR(32) NOT NULL COMMENT '加密后的密码',
            `salt` VARCHAR(32) NOT NULL COMMENT '密码盐',
            `email_verified` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '是否已经验证邮箱',
            `mobile_verified` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '是否已经验证手机',
            `locked` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '锁定',
            `created_source` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '注册来源，是一个可自定义字段，例如：web, app, wechat, Xxx第三方系统等',
            `created_ip` VARCHAR(32) NOT NULL COMMENT '创建时的ip',
            `created_user_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT '创建者',
            `created_time` INT(10) unsigned NOT NULL DEFAULT '0',
            `updated_time` INT(10) unsigned NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $connection->exec("
          CREATE TABLE `biz_security_user_bind` (
            `id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` INT(10) unsigned NOT NULL COMMENT '用户id',
            `type` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '绑定的第三方类型',
            `type_alias` VARCHAR(32) NOT NULL COMMENT '第三方类型别名',
            `bind_id` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '绑定的第三方id',
            `bind_ext` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '绑定的第三方扩展信息',
            `created_time` INT(10) unsigned NOT NULL DEFAULT '0',
            `updated_time` INT(10) unsigned NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

    }

    protected function isFieldExist($table, $filedName)
    {
        $biz = $this->getContainer();
        $db = $biz['db'];

        $sql = "DESCRIBE `{$table}` `{$filedName}`;";
        $result = $db->fetchAssoc($sql);

        return empty($result) ? false : true;
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $biz = $this->getContainer();
        $connection = $biz['db'];
        $connection->exec("
            DROP TABLE `biz_security_user`;
            DROP TABLE `biz_security_user_bind`;
        ");
    }
}
