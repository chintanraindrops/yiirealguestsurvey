<?php

use yii\db\Migration;
use yii\db\Schema;


/**
 * Handles the creation for table `email_template`.
 */
class m160913_083830_create_email_template_table extends Migration
{
    // /**
    //  * @inheritdoc
    //  */
    // public function up()
    // {
    //     $this->createTable('email_template', [
    //         'id' => $this->primaryKey(),
    //     ]);
    // }

    // /**
    //  * @inheritdoc
    //  */
    // public function down()
    // {
    //     $this->dropTable('email_template');
    // }

    public function up()
    {
        $this->createTable('{{%email_template}}', [
            'id'         => Schema::TYPE_PK,
            'title'    => Schema::TYPE_STRING . '(255) NOT NULL',
            'template'   => Schema::TYPE_TEXT
            'icon'    => Schema::TYPE_STRING . '(255) NOT NULL',
        ]);

        $this->createIndex('email_title_unique', '{{%email_template}}', ['title'], true);
    }

    public function down()
    {
        $this->dropTable('{{%email_template}}');
    }
}
