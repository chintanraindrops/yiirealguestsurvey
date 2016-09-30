<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation for table `feedback`.
 */
class m160908_110013_create_feedback_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%feedback}}', [
            'id'         => Schema::TYPE_PK,
            'user_id'    => Schema::TYPE_INTEGER,
            'location_id'   => Schema::TYPE_INTEGER . '(11)',
            'type' => Schema::TYPE_STRING. '(255)',
            'from_firstname' => Schema::TYPE_STRING. '(255)',
            'from_lastname' => Schema::TYPE_STRING. '(255)',
            'from_email' => Schema::TYPE_STRING. '(255)',
            'from_mobile' => Schema::TYPE_STRING. '(255)',
            'from_token' => Schema::TYPE_STRING . '(32) NOT NULL',
            'notes' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_INTEGER,
            'contact_me' => Schema::TYPE_INTEGER,
        ]);

        $this->addForeignKey('fk_user_feedback', '{{%feedback}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_location_feedback', '{{%feedback}}', 'location_id', '{{%business_location}}', 'id', 'CASCADE', 'RESTRICT');
        
    }

    public function down()
    {
        $this->dropTable('{{%feedback}}');
    }
}
