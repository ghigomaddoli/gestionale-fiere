<?php

class Reservations extends \Phalcon\Mvc\Model
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
    public $exhibitors_id;

    /**
     *
     * @var integer
     */
    public $events_id;

    /**
     *
     * @var integer
     */
    public $areas_id;

    /**
     *
     * @var string
     */
    public $codicestand;

    /**
     *
     * @var integer
     */
    public $padre_id;

    /**
     *
     * @var double
     */
    public $prezzofinale;

    /**
     *
     * @var string
     */
    public $notepagamento;

    /**
     *
     * @var string
     */
    public $altriservizi;

    /**
     *
     * @var integer
     */
    public $interventoprogrammaculturale;

    /**
     *
     * @var double
     */
    public $prezzostandpersonalizzato;

    /**
     *
     * @var string
     */
    public $standpersonalizzato;

    /**
     *
     * @var integer
     */
    public $stato;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("c5_espositori");
        $this->setSource("reservations");
        $this->hasMany('id', 'ReservationServices', 'reservations_id', ['alias' => 'ReservationServices']);
        $this->belongsTo('exhibitors_id', 'Exhibitors', 'id', ['alias' => 'Exhibitors']);
        $this->belongsTo('events_id', 'Events', 'id', ['alias' => 'Events']);
        $this->belongsTo('areas_id', 'Areas', 'id', ['alias' => 'Areas']);
        $this->belongsTo('padre_id', 'Exhibitors', 'id', ['alias' => 'Padri']);
        $this->belongsTo('stato', 'Stati', 'id', ['alias' => 'Stati']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Reservations[]|Reservations|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Reservations|\Phalcon\Mvc\Model\ResultInterface
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
            'exhibitors_id' => 'exhibitors_id',
            'events_id' => 'events_id',
            'areas_id' => 'areas_id',
            'codicestand' => 'codicestand',
            'padre_id' => 'padre_id',
            'prezzofinale' => 'prezzofinale',
            'notepagamento' => 'notepagamento',
            'altriservizi' => 'altriservizi',
            'interventoprogrammaculturale' => 'interventoprogrammaculturale',
            'prezzostandpersonalizzato' => 'prezzostandpersonalizzato',
            'standpersonalizzato' => 'standpersonalizzato',
            'stato' => 'stato'
        ];
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'reservations';
    }

}
