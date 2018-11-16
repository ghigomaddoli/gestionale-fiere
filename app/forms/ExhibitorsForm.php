<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Radio;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\Digit as DigitValidator;
use Phalcon\Validation\Validator\StringLength as StringLength;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\InclusionIn;

use Phalcon\Forms\Element;

/*
EXHIBITORS COLUMN MAP
            'id' => 'id',
            'ragionesociale' => 'ragionesociale',
            'indirizzo' => 'indirizzo',
            'cap' => 'cap',
            'citta' => 'citta',
            'provincia' => 'provincia',
            'telefono' => 'telefono',
            'emailaziendale' => 'emailaziendale',
            'pivacodfisc' => 'pivacodfisc',
            'referentenome' => 'referentenome',
            'referentetelefono' => 'referentetelefono',
            'referenteemail' => 'referenteemail',
            'prodottiesposti' => 'prodottiesposti',
            'fasciadiprezzo' => 'fasciadiprezzo',
            'numerocoespositore' => 'numerocoespositore',
            'nomecoespositore' => 'nomecoespositore',
            'catalogonome' => 'catalogonome',
            'catalogoindirizzo' => 'catalogoindirizzo',
            'catalogocap' => 'catalogocap',
            'catalogocitta' => 'catalogocitta',
            'catalogoprovincia' => 'catalogoprovincia',
            'catalogotelefono' => 'catalogotelefono',
            'catalogoemail' => 'catalogoemail',
            'catalogositoweb' => 'catalogositoweb',
            'catalogofacebook' => 'catalogofacebook',
            'catalogoinstagram' => 'catalogoinstagram',
            'catalogotwitter' => 'catalogotwitter',
            'catalogodescrizione' => 'catalogodescrizione',
*/
class ExhibitorsForm extends Form
{
    /**
     * Initialize the products form
     */
    public function initialize($entity = null, $options = array())
    {
        
        if (isset($options['edit'])) {
            $this->add(new Hidden("id"));
        }

        $ragionesociale = new Text("ragionesociale");
        $ragionesociale->setLabel("Ragione Sociale");
        $ragionesociale->setFilters(['striptags', 'string']);
        $ragionesociale->addValidators([
            new PresenceOf([
                'message' => 'Ragione Sociale è obbligatoria'
            ])
        ]);
        $this->add($ragionesociale);

        $indirizzo = new Text("indirizzo");
        $indirizzo->setLabel("Indirizzo");
        $indirizzo->setFilters(['striptags', 'string']);
        $indirizzo->addValidators([
            new PresenceOf([
                'message' => 'L\'indirizzo è obbligatorio'
            ])
        ]);
        $this->add($indirizzo);

        $cap = new Text("cap");
        $cap->setLabel("CAP");
        $cap->setFilters(['striptags', 'string']);
        $cap->addValidators([
            new DigitValidator(
                [
                    "message" => "Il CAP deve contenere solo numeri",
                ]
                ),
                new StringLength(
                    [
                        "max"            => 5,
                        "min"            => 5,
                        "messageMaximum" => "CAP troppo lungo",
                        "messageMinimum" => "CAP obbligatorio o troppo corto",
                    ]
                )
        ]);
        $this->add($cap);

        $citta = new Text("citta");
        $citta->setLabel("Città");
        $citta->setFilters(['striptags', 'string']);
        $citta->addValidators([
            new PresenceOf([
                'message' => 'la città di appartenenza è obbligatoria'
            ])
        ]);
        $this->add($citta);

        $provincia = new Select("provincia",
        Province::find(),
        [
            'using'      => [
                'sigla_province',
                'nome_province',
            ],
            'useEmpty'   => true,
            'emptyText'  => 'Seleziona la Provincia...',
            'emptyValue' => '',
        ]
        );
        $provincia->setLabel("Provincia");
        $provincia->setFilters(['striptags', 'string']);
        $provincia->addValidators([
            new PresenceOf([
                'message' => 'la Provincia è obbligatoria'
            ]),
            new StringLength([
                        "max"            => 2,
                        "messageMaximum" => "La Provincia deve essere composta di due lettere",
            ]),
        ]);
        $this->add($provincia);


        $telefono = new Text("telefono");
        $telefono->setLabel("telefono");
        $telefono->setFilters(['striptags', 'string']);
        $telefono->addValidators([
            new PresenceOf([
                'message' => 'Il telefono è obbligatorio'
            ]),
            new DigitValidator(
                [
                    "message" => "Il telefono deve contenere solo numeri",
                ]
            )
        ]);
        $this->add($telefono);

        $emailaziendale = new Text("emailaziendale");
        $emailaziendale->setLabel("Email Azienda");
        $emailaziendale->setFilters(['striptags', 'string']);
        $emailaziendale->addValidators([
            new Email(
                [
                    'message' => 'L\'indirizzo email di riferimento per l\'azienda non è valido',
                ]
            )
        ]);
        $this->add($emailaziendale);

        $pivacodfisc = new Text("pivacodfisc");
        $pivacodfisc->setLabel("Partita Iva o Codice Fiscale");
        $pivacodfisc->setFilters(['striptags', 'string']);
        $pivacodfisc->addValidators([
            new PresenceOf([
                'message' => 'Partita Iva o Codice Fiscale obbligatori'
            ]),
            new Uniqueness(
                [
                    'message' => 'La Partita Iva o Codice Fiscale per l\'azienda esiste già nel database.',
                ]
            )
        ]);
        $this->add($pivacodfisc);

        $prodottiesposti = new TextArea("prodottiesposti");
        $prodottiesposti->setLabel("Descrizione dei prodotti o servizi esposti");
        $prodottiesposti->setFilters(['striptags', 'string']);
        $prodottiesposti->addValidators([
            new StringLength(
                [
                    "min"            => 15,
                    "messageMinimum" => "È necessario descrivere brevemente i prodotti o servizi esposti (min. 15 caratteri)",
                ]
            )
        ]);
        $this->add($prodottiesposti);


        $referentenome = new Text("referentenome");
        $referentenome->setLabel("Nome dell'espositore da poter contattare durante l'evento");
        $referentenome->setFilters(['striptags', 'string']);
        $referentenome->addValidators([
            new PresenceOf([
                'message' => 'Il nome dell\'espositore è obbligatorio'
            ])
        ]);
        $this->add($referentenome);


        $referentetelefono = new Text("referentetelefono");
        $referentetelefono->setLabel("Telefono dell'espositore per contatti durante l'evento");
        $referentetelefono->setFilters(['striptags', 'string']);
        $referentetelefono->addValidators([
            new DigitValidator(
                [
                    "message" => "Il telefono dell'espositore è obbligatorio e deve contenere solo numeri",
                ]
                )
        ]);
        $this->add($referentetelefono);

        $referenteemail = new Text("referenteemail");
        $referenteemail->setLabel("Email dell'espositore");
        $referenteemail->setFilters(['striptags', 'string']);
        $referenteemail->addValidators([
            new Email(
                [
                    'message' => 'L\'indirizzo email dell\'espositore non è valido ed è obbligatorio',
                ]
            )
        ]);
        $this->add($referenteemail);


        $fasciadiprezzo = new Select("fasciadiprezzo",['A'=>'Fascia A','B' => 'Fascia B']);
        $fasciadiprezzo->setLabel("Fascia di prezzo");
        $fasciadiprezzo->setFilters(['striptags', 'string']);
        $fasciadiprezzo->addValidators([
            new PresenceOf([
                'message' => 'È obbligatorio selezionare una Fascia di prezzo'
            ]),
        ]);
        $this->add($fasciadiprezzo);

        /* Scelta dello spazio espositivo */
        $options = Services::find(
            [
                "events_id = {$this->evento->id}",
                'order' => 'tipologia',
            ]
        );

        foreach($options as $key => $servizio)
        {
            if ($servizio->tipologia == 1 || $servizio->tipologia == 2){
                $optionsservices = array(
                    'name'  => "services[{$servizio->id}]",
                    'class' => 'form-control',
                    'value' => 1,
                );
                if ($servizio->tipologia == 1) $optionsservices["checked"]='';
                $services = new Check("service-".$servizio->id, $optionsservices);
                $services->setLabel($servizio->descrizione." - prezzo &euro; ".number_format($servizio->prezzofasciaa,2,",","."));
                $this->add($services);
            }

            if ($servizio->tipologia == 3 ){
                $quantitaservices = new Numeric("services[".$servizio->id."]",['value'=> 0, 'min' => 0, 'max'=> 20,'id'=> 'service-'.$servizio->id]);
                $quantitaservices->setLabel($servizio->descrizione);
                $quantitaservices->setFilters(['striptags', 'string']);
                $this->add($quantitaservices);
            }
        }

        $altriservizi = new TextArea("altriservizi");
        $altriservizi->setLabel("altri servizi");
        $altriservizi->setFilters(['striptags', 'string']);
        $this->add($altriservizi);

        
        $numerocoespositore = new Numeric("numerocoespositore");
        $numerocoespositore->setLabel("Numero dei coespositori");
        $numerocoespositore->setFilters(['striptags', 'string']);
        $numerocoespositore->setDefault('0');
        $numerocoespositore->addValidators([
            new DigitValidator(
                [
                    "message" => "specificare un numero valido di coespositori",
                ]
            ),
        ]);
        $this->add($numerocoespositore);

        $nomecoespositore = new Text("nomecoespositore");
        $nomecoespositore->setLabel("Nomi dei coespositori");
        $nomecoespositore->setFilters(['striptags', 'string']);
        $this->add($nomecoespositore);

        $interventoprogrammaculturale = new Check("interventoprogrammaculturale",["name" => "interventoprogrammaculturale","value" =>"1"]);
        $interventoprogrammaculturale->setLabel("Desidero partecipare al programma culturale");
        $interventoprogrammaculturale->setFilters(['striptags', 'string']);
        $this->add($interventoprogrammaculturale);

        /* qui vanno i campi per l'inserimento delle prenotazioni */
        
        $areas_id = new Select("areas_id",
                Areas::find(),
                [
                    'using'      => [
                        'id',
                        'nome',
                    ],
                    'useEmpty'   => true,
                    'emptyText'  => 'Seleziona un\'area tematica...',
                    'emptyValue' => '',
                ]
        );
        $areas_id->setLabel("Scelta dell'area tematica");
        $areas_id->setFilters(['striptags', 'string']);
        $this->add($areas_id);

        $codicestand = new Text("codicestand",["id"=>"primadelcatalogo"]);
        $codicestand->setLabel("Codice stand");
        $codicestand->setFilters(['striptags', 'string']);
        $this->add($codicestand);

        $catalogonome = new Text("catalogonome");
        $catalogonome->setLabel("Nome");
        $catalogonome->setFilters(['striptags', 'string']);
        $this->add($catalogonome);

        $catalogoindirizzo = new Text("catalogoindirizzo");
        $catalogoindirizzo->setLabel("Indirizzo");
        $catalogoindirizzo->setFilters(['striptags', 'string']);
        $this->add($catalogoindirizzo);

        $catalogocap = new Text("catalogocap");
        $catalogocap->setLabel("Cap");
        $catalogocap->setFilters(['striptags', 'string']);
       /*
        $catalogocap->addValidators([
            new DigitValidator(
                [
                    "message" => "Il CAP per il catalogo deve contenere solo numeri",
                ]
                ),
                new StringLength(
                    [
                        "max"            => 5,
                        "messageMaximum" => "CAP per il catalogotroppo lungo",
                    ]
                )
        ]);
        */
        $this->add($catalogocap);

        $catalogocitta = new Text("catalogocitta");
        $catalogocitta->setLabel("Città");
        $catalogocitta->setFilters(['striptags', 'string']);
        $this->add($catalogocitta);

        $catalogoprovincia = new Select("catalogoprovincia",
        Province::find(),
        [
            'using'      => [
                'sigla_province',
                'nome_province',
            ],
            'useEmpty'   => true,
            'emptyText'  => 'Seleziona la Provincia...',
            'emptyValue' => '',
        ]
        );
        $catalogoprovincia->setLabel("Provincia");
        $catalogoprovincia->setFilters(['striptags', 'string']);
        $this->add($catalogoprovincia);

        $catalogotelefono = new Text("catalogotelefono");
        $catalogotelefono->setLabel("Telefono");
        $catalogotelefono->setFilters(['striptags', 'string']);
        $this->add($catalogotelefono);

        $catalogoemail = new Text("catalogoemail");
        $catalogoemail->setLabel("Email");
        $catalogoemail->setFilters(['striptags', 'string']);
        $this->add($catalogoemail);

        $catalogositoweb = new Text("catalogositoweb");
        $catalogositoweb->setLabel("Sito web");
        $catalogositoweb->setFilters(['striptags', 'string']);
        $this->add($catalogositoweb);

        $catalogofacebook = new Text("catalogofacebook");
        $catalogofacebook->setLabel("Pagina Facebook");
        $catalogofacebook->setFilters(['striptags', 'string']);
        $this->add($catalogofacebook);

        $catalogoinstagram = new Text("catalogoinstagram");
        $catalogoinstagram->setLabel("Pagina Instagram");
        $catalogoinstagram->setFilters(['striptags', 'string']);
        $this->add($catalogoinstagram);

        $catalogotwitter = new Text("catalogotwitter");
        $catalogotwitter->setLabel("Pagina Twitter");
        $catalogotwitter->setFilters(['striptags', 'string']);
        $this->add($catalogotwitter);

        $catalogodescrizione = new Text("catalogodescrizione");
        $catalogodescrizione->setLabel("Descrizione azienda per il catalogo");
        $catalogodescrizione->setFilters(['striptags', 'string']);
        $this->add($catalogodescrizione);


    }
}