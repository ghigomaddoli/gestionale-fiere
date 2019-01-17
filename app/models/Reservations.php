<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\StringLength as StringLength;

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
     * @var double
     */
    public $prezzoaltriservizi;    

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
     *
     * @var string
     */
    public $numerofattura; 
    
    /**
     *
     * @var integer
     */
    public $anticiporichiesto;
    
    /**
     *
     * @var integer
     */
    public $anticipopagato;
    
    /**
     *
     * @var integer
     */
    public $pagamentocompleto;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("c5_espositori");
        $this->setSource("reservations");
        $this->hasMany('id', 'ReservationServices', 'reservations_id', ['alias' => 'ReservationServices']);
        $this->belongsTo('exhibitors_id', 'Exhibitors', 'id', ['alias' => 'Exhibitors', 'reusable' => true]);
        $this->belongsTo('events_id', 'Events', 'id', ['alias' => 'Events','reusable' => true]);
        $this->belongsTo('areas_id', 'Areas', 'id', ['alias' => 'Areas','reusable' => true]);
        $this->belongsTo('padre_id', 'Exhibitors', 'id', ['alias' => 'Padri']);
        $this->belongsTo('stato', 'Stati', 'id', ['alias' => 'Stati','reusable' => true]);
    }

    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'codicestand',
            new StringLength(
                [
                    "max"            => 20,
                    "messageMaximum" => "Il Codice Stand è troppo lungo",
                    "allowEmpty" => true,
                ]
            )
        );

        return $this->validate($validator);
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
            'prezzoaltriservizi' => 'prezzoaltriservizi',
            'interventoprogrammaculturale' => 'interventoprogrammaculturale',
            'prezzostandpersonalizzato' => 'prezzostandpersonalizzato',
            'standpersonalizzato' => 'standpersonalizzato',
            'stato' => 'stato',
            'numerofattura' => 'numerofattura',
            'anticiporichiesto' => 'anticiporichiesto',
            'anticipopagato' => 'anticipopagato',
            'pagamentocompleto' => 'pagamentocompleto'
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