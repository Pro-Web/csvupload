<?php

use yii\db\Migration;

/**
 * Handles the creation of table `csv_upload`.
 */
class m180121_153843_create_csv_upload_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('csv_upload', [
            'id' => $this->primaryKey(),
            'column' => $this->string(255),
            'title' => $this->string(255),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('csv_upload');
    }
}
