<?php

use yii\db\Migration;
use yii\db\Schema;

class m160908_092031_business_location extends Migration
{
    public function up()
    {
        $this->createTable('{{%business_location}}', [
            'id'         => Schema::TYPE_PK,
            'user_id'    => Schema::TYPE_INTEGER,
            'business_name'  => Schema::TYPE_STRING,
            'location_name'  => Schema::TYPE_STRING,
            'address'  => Schema::TYPE_TEXT,
            'city'  => Schema::TYPE_STRING,
            'state'  => Schema::TYPE_STRING,
            'zip'  => Schema::TYPE_STRING,
            'phone'  => Schema::TYPE_STRING,
            'google_page'  => Schema::TYPE_STRING,
            'facebook_page'  => Schema::TYPE_STRING,
            'trip_advisor'  => Schema::TYPE_STRING,
            'yelp'  => Schema::TYPE_STRING,
            'google_place_id'  => Schema::TYPE_STRING,
            'feedback_approach_text'  => Schema::TYPE_TEXT,
            'logo'  => Schema::TYPE_STRING,
        ]);

        $this->addForeignKey('fk_user_business_location', '{{%business_location}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%business_location}}');
    }
}
