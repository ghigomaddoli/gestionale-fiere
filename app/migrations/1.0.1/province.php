<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ProvinceMigration_101
 */
class ProvinceMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('province', [
                'columns' => [
                    new Column(
                        'id_province',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 16,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'nome_province',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 128,
                            'after' => 'id_province'
                        ]
                    ),
                    new Column(
                        'sigla_province',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 5,
                            'after' => 'nome_province'
                        ]
                    ),
                    new Column(
                        'regione_province',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 128,
                            'after' => 'sigla_province'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id_province'], 'PRIMARY')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '111',
                    'ENGINE' => 'MyISAM',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ],
            ]
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

}
