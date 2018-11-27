<?php

class LogStatiReservations extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $reservations_id;

    /**
     *
     * @var integer
     */
    public $stati_id;

    /**
     *
     * @var string
     */
    public $dataora;

    /**
     *
     * @var integer
     */
    public $users_id;

    /**
     *
     * @var string
     */
    public $messaggio;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("c5_espositori");
        $this->setSource("log_stati_reservations");
        $this->belongsTo('reservations_id', '\Reservations', 'id', ['alias' => 'Reservations']);
        $this->belongsTo('stati_id', '\Stati', 'id', ['alias' => 'Stati']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'log_stati_reservations';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return LogStatiReservations[]|LogStatiReservations|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return LogStatiReservations|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'reservations_id' => 'reservations_id',
            'stati_id' => 'stati_id',
            'dataora' => 'dataora',
            'users_id' => 'users_id',
            'messaggio' => 'messaggio'
        ];
    }

}
