<?php

use Phpmig\Migration\Migration;

class BizOrg extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $biz = $this->getContainer();
        $connection = $biz['db'];


        $connection->exec("
          CREATE TABLE IF NOT EXISTS `biz_security_org` (
            `id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
            `parent_id` INT(10) unsigned NOT NULL DEFAULT 0,
            `name` VARCHAR(64) NOT NULL COMMENT '部门名称',
            `code` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '部门编码',
            `internal_code` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '内部编码，程序内使用',
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
