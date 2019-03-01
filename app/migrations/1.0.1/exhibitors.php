<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ExhibitorsMigration_101
 */
class ExhibitorsMigration_101 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('exhibitors', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 10,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'ragionesociale',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'indirizzo',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'ragionesociale'
                        ]
                    ),
                    new Column(
                        'cap',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'indirizzo'
                        ]
                    ),
                    new Column(
                        'citta',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'cap'
                        ]
                    ),
                    new Column(
                        'provincia',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 2,
                            'after' => 'citta'
                        ]
                    ),
                    new Column(
                        'telefono',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 15,
                            'after' => 'provincia'
                        ]
                    ),
                    new Column(
                        'emailaziendale',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'telefono'
                        ]
                    ),
                    new Column(
                        'piva',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 16,
                            'after' => 'emailaziendale'
                        ]
                    ),
                    new Column(
                        'codfisc',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 16,
                            'after' => 'piva'
                        ]
                    ),
                    new Column(
                        'pec',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'codfisc'
                        ]
                    ),
                    new Column(
                        'codicesdi',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'pec'
                        ]
                    ),
                    new Column(
                        'referentenome',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'notNull' => true,
                            'size' => 100,
                            'after' => 'codicesdi'
                        ]
                    ),
                    new Column(
                        'referentetelefono',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'notNull' => true,
                            'size' => 15,
                            'after' => 'referentenome'
                        ]
                    ),
                    new Column(
                        'referenteemail',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'referentetelefono'
                        ]
                    ),
                    new Column(
                        'prodottiesposti',
                        [
                            'type' => Column::TYPE_TEXT,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'referenteemail'
                        ]
                    ),
                    new Column(
                        'fasciadiprezzo',
                        [
                            'type' => Column::TYPE_CHAR,
                            'default' => "a",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'prodottiesposti'
                        ]
                    ),
                    new Column(
                        'numerocoespositore',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 20,
                            'after' => 'fasciadiprezzo'
                        ]
                    ),
                    new Column(
                        'nomecoespositore',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'numerocoespositore'
                        ]
                    ),
                    new Column(
                        'catalogonome',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'nomecoespositore'
                        ]
                    ),
                    new Column(
                        'catalogoindirizzo',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'catalogonome'
                        ]
                    ),
                    new Column(
                        'catalogocap',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 10,
                            'after' => 'catalogoindirizzo'
                        ]
                    ),
                    new Column(
                        'catalogocitta',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 40,
                            'after' => 'catalogocap'
                        ]
                    ),
                    new Column(
                        'catalogoprovincia',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 2,
                            'after' => 'catalogocitta'
                        ]
                    ),
                    new Column(
                        'catalogotelefono',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 15,
                            'after' => 'catalogoprovincia'
                        ]
                    ),
                    new Column(
                        'catalogoemail',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'catalogotelefono'
                        ]
                    ),
                    new Column(
                        'catalogositoweb',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'catalogoemail'
                        ]
                    ),
                    new Column(
                        'catalogofacebook',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'catalogositoweb'
                        ]
                    ),
                    new Column(
                        'catalogoinstagram',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'catalogofacebook'
                        ]
                    ),
                    new Column(
                        'catalogotwitter',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 255,
                            'after' => 'catalogoinstagram'
                        ]
                    ),
                    new Column(
                        'catalogodescrizione',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'catalogotwitter'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('fasciadiprezzo', ['fasciadiprezzo'], null)
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '5',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'latin1_swedish_ci'
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
