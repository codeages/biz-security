<?php

use Phpmig\Migration\Migration;

class BizRole extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $biz = $this->getContainer();
        $connection = $biz['db'];


        $connection->exec("
            CREATE TABLE `biz_security_role` (
            `id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
            `code` VARCHAR(64) NOT NULL COMMENT '角色代码',
            `name` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '角色名称',
            `data` TEXT COMMENT '权限code集',
            `enable` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '是否启用',
            `created_user_id` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT '创建者',
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
        ");
    }
}
