<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class ReservationsMigration_103
 */
class ReservationsMigration_103 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('reservations', [
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
                        'exhibitors_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'events_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'exhibitors_id'
                        ]
                    ),
                    new Column(
                        'areas_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'events_id'
                        ]
                    ),
                    new Column(
                        'codicestand',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'default' => "",
                            'size' => 20,
                            'after' => 'areas_id'
                        ]
                    ),
                    new Column(
                        'padiglione',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 20,
                            'after' => 'codicestand'
                        ]
                    ),
                    new Column(
                        'padre_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'size' => 10,
                            'after' => 'padiglione'
                        ]
                    ),
                    new Column(
                        'prezzofinale',
                        [
                            'type' => Column::TYPE_DECIMAL,
                            'size' => 12,
                            'scale' => 4,
                            'after' => 'padre_id'
                        ]
                    ),
                    new Column(
                        'notepagamento',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'prezzofinale'
                        ]
                    ),
                    new Column(
                        'altriservizi',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'notepagamento'
                        ]
                    ),
                    new Column(
                        'prezzoaltriservizi',
                        [
                            'type' => Column::TYPE_DECIMAL,
                            'size' => 12,
                            'scale' => 4,
                            'after' => 'altriservizi'
                        ]
                    ),
                    new Column(
                        'interventoprogrammaculturale',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 4,
                            'after' => 'prezzoaltriservizi'
                        ]
                    ),
                    new Column(
                        'prezzostandpersonalizzato',
                        [
                            'type' => Column::TYPE_DECIMAL,
                            'size' => 12,
                            'scale' => 4,
                            'after' => 'interventoprogrammaculturale'
                        ]
                    ),
                    new Column(
                        'standpersonalizzato',
                        [
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'prezzostandpersonalizzato'
                        ]
                    ),
                    new Column(
                        'stato',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "1",
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'standpersonalizzato'
                        ]
                    ),
                    new Column(
                        'numerofattura',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 100,
                            'after' => 'stato'
                        ]
                    ),
                    new Column(
                        'anticiporichiesto',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'numerofattura'
                        ]
                    ),
                    new Column(
                        'anticipopagato',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'anticiporichiesto'
                        ]
                    ),
                    new Column(
                        'pagamentocompleto',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'anticipopagato'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('exhibitors_id', ['exhibitors_id'], null),
                    new Index('events_id', ['events_id'], null),
                    new Index('areas_id', ['areas_id'], null),
                    new Index('padre_id', ['padre_id'], null),
                    new Index('stato', ['stato'], null)
                ],
                'references' => [
                    new Reference(
                        'reservations_ibfk_1',
                        [
                            'referencedTable' => 'exhibitors',
                            'referencedSchema' => 'c5_espositori',
                            'columns' => ['exhibitors_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'CASCADE',
                            'onDelete' => 'CASCADE'
                        ]
                    ),
                    new Reference(
                        'reservations_ibfk_2',
                        [
                            'referencedTable' => 'events',
                            'referencedSchema' => 'c5_espositori',
                            'columns' => ['events_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'CASCADE',
                            'onDelete' => 'NO ACTION'
                        ]
                    ),
                    new Reference(
                        'reservations_ibfk_3',
                        [
                            'referencedTable' => 'areas',
                            'referencedSchema' => 'c5_espositori',
                            'columns' => ['areas_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'CASCADE',
                            'onDelete' => 'NO ACTION'
                        ]
                    ),
                    new Reference(
                        'reservations_ibfk_4',
                        [
                            'referencedTable' => 'exhibitors',
                            'referencedSchema' => 'c5_espositori',
                            'columns' => ['padre_id'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'CASCADE',
                            'onDelete' => 'SET NULL'
                        ]
                    ),
                    new Reference(
                        'reservations_ibfk_5',
                        [
                            'referencedTable' => 'stati',
                            'referencedSchema' => 'c5_espositori',
                            'columns' => ['stato'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'CASCADE',
                            'onDelete' => 'NO ACTION'
                        ]
                    )
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '30',
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
