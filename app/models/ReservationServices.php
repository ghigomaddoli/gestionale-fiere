<?php


class ReservationServices extends \Phalcon\Mvc\Model
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
    public $services_id;

    /**
     *
     * @var integer
     */
    public $quantita;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("c5_espositori");
        $this->setSource("reservation_services");
        $this->belongsTo('reservations_id', 'Reservations', 'id', ['alias' => 'Reservations']);
        $this->belongsTo('services_id', 'Services', 'id', ['alias' => 'Services']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'reservation_services';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ReservationServices[]|ReservationServices|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ReservationServices|\Phalcon\Mvc\Model\ResultInterface
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
            'services_id' => 'services_id',
            'quantita' => 'quantita'
        ];
    }

}
