<?php

use yii\db\Migration;
use yii\db\Schema;

class m160908_092011_add_new_fields_to_profile extends Migration
{
    public function up()
    {
        $this->addColumn('{{%profile}}', 'business_name', Schema::TYPE_STRING);
        $this->addColumn('{{%profile}}', 'address', Schema::TYPE_TEXT);
        $this->addColumn('{{%profile}}', 'city', Schema::TYPE_STRING);
        $this->addColumn('{{%profile}}', 'state', Schema::TYPE_STRING);
        $this->addColumn('{{%profile}}', 'zip', Schema::TYPE_STRING);
        $this->addColumn('{{%profile}}', 'phone', Schema::TYPE_STRING);
        $this->addColumn('{{%profile}}', 'profile_url', Schema::TYPE_STRING);
        $this->addColumn('{{%profile}}', 'google_page', Schema::TYPE_STRING);
        $this->addColumn('{{%profile}}', 'facebook_page', Schema::TYPE_STRING);
        $this->addColumn('{{%profile}}', 'trip_advisor', Schema::TYPE_STRING);
        $this->addColumn('{{%profile}}', 'yelp', Schema::TYPE_STRING);
        $this->addColumn('{{%profile}}', 'setup', Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->dropColumn('{{%profile}}', 'business_name');
        $this->dropColumn('{{%profile}}', 'address');
        $this->dropColumn('{{%profile}}', 'city');
        $this->dropColumn('{{%profile}}', 'state');
        $this->dropColumn('{{%profile}}', 'zip');
        $this->dropColumn('{{%profile}}', 'phone');
        $this->dropColumn('{{%profile}}', 'profile_url');
        $this->dropColumn('{{%profile}}', 'google_page');
        $this->dropColumn('{{%profile}}', 'facebook_page');
        $this->dropColumn('{{%profile}}', 'trip_advisor');
        $this->dropColumn('{{%profile}}', 'yelp');
        $this->dropColumn('{{%profile}}', 'setup');
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
