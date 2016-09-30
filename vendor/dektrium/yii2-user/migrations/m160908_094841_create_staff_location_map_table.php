<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation for table `staff_location_map`.
 */
class m160908_094841_create_staff_location_map_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%staff_location_map}}', [
            'id'         => Schema::TYPE_PK,
            'user_id'    => Schema::TYPE_INTEGER,
            'staff_id' => Schema::TYPE_INTEGER,
            'location_id' => Schema::TYPE_INTEGER,
        ]);

        $this->addForeignKey('fk_user_staff_location_map', '{{%staff_location_map}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_staffid_staff_location_map', '{{%staff_location_map}}', 'staff_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_locationid_staff_location_map', '{{%staff_location_map}}', 'location_id', '{{%business_location}}', 'id', 'CASCADE', 'RESTRICT');
        
    }

    public function down()
    {
        $this->dropTable('{{%staff_location_map}}');
    }
}
