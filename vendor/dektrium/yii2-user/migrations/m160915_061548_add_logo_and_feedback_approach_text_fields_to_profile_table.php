<?php

use yii\db\Migration;
use yii\db\Schema;

class m160915_061548_add_logo_and_feedback_approach_text_fields_to_profile_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%profile}}', 'logo', Schema::TYPE_STRING);
        $this->addColumn('{{%profile}}', 'feedback_approach_text', Schema::TYPE_TEXT);
    }

    public function down()
    {
       $this->dropColumn('{{%profile}}', 'logo');
       $this->dropColumn('{{%profile}}', 'feedback_approach_text');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
