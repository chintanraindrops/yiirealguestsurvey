<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation for table `email_template_map`.
 */
class m160913_083853_create_email_template_map_table extends Migration
{
    // /**
    //  * @inheritdoc
    //  */
    // public function up()
    // {
    //     $this->createTable('email_template_map', [
    //         'id' => $this->primaryKey(),
    //     ]);
    // }

    // /**
    //  * @inheritdoc
    //  */
    // public function down()
    // {
    //     $this->dropTable('email_template_map');
    // }
    public function up()
    {
        $this->createTable('{{%email_template_map}}', [
            'id'         => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'title' => Schema::TYPE_STRING . '(255) NOT NULL',
            'template' => Schema::TYPE_TEXT,
            'template_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'active' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);

        $this->addForeignKey('fk_user_email_template_map', '{{%email_template_map}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_email_template_email_template_map', '{{%email_template_map}}', 'template_id', '{{%email_template}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%email_template_map}}');
    }
}
