<?php

// use yii\db\Migration;
use dektrium\user\migrations\Migration;
use yii\db\Schema;

/**
 * Handles the creation for table `user_map`.
 */
class m160907_045744_create_user_map_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        // $this->createTable('user_map', [
        //     'id' => $this->primaryKey(),
        // ]);

        $this->createTable('{{%user_map}}', [
            'id'         => Schema::TYPE_PK,
            'user_id'    => Schema::TYPE_INTEGER,
            'parent_id'   => Schema::TYPE_INTEGER
        ]);

        $this->createIndex('user_map_unique', '{{%user_map}}', ['user_id', 'parent_id'], true);
        $this->addForeignKey('fk_user_user', '{{%user_map}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_user_parent', '{{%user_map}}', 'parent_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%user_map}}');
    }
}
