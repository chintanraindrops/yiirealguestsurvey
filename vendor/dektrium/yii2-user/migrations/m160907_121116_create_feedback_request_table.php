<?php

// use yii\db\Migration;
use dektrium\user\migrations\Migration;
use yii\db\Schema;

/**
 * Handles the creation for table `feedback_request`.
 */
class m160907_121116_create_feedback_request_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        /*$this->createTable('feedback_request', [
            'id' => $this->primaryKey(),
        ]);*/

        $this->createTable('{{%feedback_request}}', [
            'id'         => Schema::TYPE_PK,
            'user_id'       => Schema::TYPE_INTEGER . '(11)',
            'location_id'   => Schema::TYPE_INTEGER . '(11)',
            'firstname'     => Schema::TYPE_STRING . '(255)',
            'lastname'      => Schema::TYPE_STRING . '(255)',
            'email'         => Schema::TYPE_STRING . '(255)',
            'mobile'        => Schema::TYPE_STRING . '(255)',
            'created_at'    => Schema::TYPE_INTEGER . ' NOT NULL',
            'status'        => Schema::TYPE_STRING . '(50)',
            'token' => Schema::TYPE_STRING . '(32) NOT NULL',
        ], $this->tableOptions);

        $this->addForeignKey('fk_user_feedback_request', '{{%feedback_request}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_location_feedback_request', '{{%feedback_request}}', 'location_id', '{{%business_location}}', 'id', 'CASCADE', 'RESTRICT');
        $this->createIndex('token_unique', '{{%feedback_request}}', 'token', true);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%feedback_request}}');
    }
}
