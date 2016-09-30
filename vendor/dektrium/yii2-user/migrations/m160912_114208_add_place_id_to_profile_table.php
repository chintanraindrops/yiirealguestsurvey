<?php

use yii\db\Migration;
use yii\db\Schema;

class m160912_114208_add_place_id_to_profile_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%profile}}', 'place_id', Schema::TYPE_STRING);
    }

    public function down()
    {
        /*echo "m160912_114208_add_place_id_to_profile_table cannot be reverted.\n";

        return false;*/
        $this->dropColumn('{{%profile}}', 'place_id');
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
