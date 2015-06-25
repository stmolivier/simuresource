<?php

namespace CPASimUSante\SimuResourceBundle\Migrations\pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/06/24 12:10:08
 */
class Version20150624121005 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE cpasimusante_simuresource (
                id INT AUTO_INCREMENT NOT NULL, 
                field_example VARCHAR(255) NOT NULL, 
                otherfield INT NOT NULL, 
                otherfield2 INT NOT NULL, 
                resourceNode_id INT DEFAULT NULL, 
                UNIQUE INDEX UNIQ_634B4C97B87FAB32 (resourceNode_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            ALTER TABLE cpasimusante_simuresource 
            ADD CONSTRAINT FK_634B4C97B87FAB32 FOREIGN KEY (resourceNode_id) 
            REFERENCES claro_resource_node (id) 
            ON DELETE CASCADE
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DROP TABLE cpasimusante_simuresource
        ");
    }
}