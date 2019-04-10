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

class CoespositoreForm extends Form
{
    /**
     * Initialize the form
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
                'message' => 'La Ragione Sociale è obbligatoria'
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
                    "message" => "Il telefono è obbligatorio e deve contenere solo numeri",
                    "allowempty" => true,
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

        $piva = new Text("piva");
        $piva->setLabel("Partita Iva");
        $piva->setFilters(['striptags', 'string']);
        $piva->addValidators([
            new DigitValidator(
                [
                    "message" => "La Partita Iva deve contenere solo numeri",
                    "allowEmpty" => true,
                ]
                ),
            new Uniqueness(
                [
                    'message' => 'Questa Partita Iva esiste già nel database.',
                    "allowEmpty" => true,
                ]
                ),
            new StringLength(
                [
                    "min"           => 11,
                    "messageMinimum" => "La Partita Iva deve essere lunga 11 caratteri",
                    "allowEmpty" => true,
                ]
            )              
        ]);
        $this->add($piva);

        $codfisc = new Text("codfisc");
        $codfisc->setLabel("Codice Fiscale");
        $codfisc->setFilters(['striptags', 'string']);
        $codfisc->addValidators([
            new StringLength(
                [
                    "min"   =>16,
                    "messageMinimum" => "Il Codice Fiscale deve essere composto da 16 caratteri",
                    "allowEmpty" => true,
                ]
            )
        ]);
        $this->add($codfisc);

        $pec = new Text("pec");
        $pec->setLabel("PEC - Posta elettronica certificata");
        $pec->setFilters(['striptags', 'string', 'trim']);
        $pec->addValidators([
            new Email(
                [
                    'message' => 'L\'indirizzo email PEC non ha un formato valido',
                    "allowEmpty" => true,
                ]
            )
        ]);
        $this->add($pec);

        $codicesdi = new Text("codicesdi");
        $codicesdi->setLabel("Codice del Sistema di Interscambio");
        $codicesdi->setFilters(['striptags', 'string', 'trim']);
        $this->add($codicesdi);

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

        $prodottiesposti = new TextArea("prodottiesposti");
        $prodottiesposti->setLabel("Descrizione dei prodotti o servizi esposti");
        $prodottiesposti->setFilters(['striptags', 'string','trim']);
        $prodottiesposti->addValidators([
            new StringLength(
                [
                    "min"            => 10,
                    "messageMinimum" => "È necessario descrivere brevemente i prodotti o servizi esposti",
                ]
            )
        ]);
        $this->add($prodottiesposti);        
/*
        $padiglione = new Text("padiglione");
        $padiglione->setLabel("Padiglione");
        $padiglione->setFilters(['striptags', 'string', 'trim']);
        $padiglione->addValidators([
            new StringLength(
                [
                    "max"            => 20,
                    "messageMaximum" => "Il campo Padiglione è troppo lungo (max 20 caratteri)",
                    "allowEmpty" => true,
                ]
            )
        ]);
        $this->add($padiglione);
*/
        $interventoprogrammaculturale = new Check("interventoprogrammaculturale",["name" => "interventoprogrammaculturale","value" =>"1"]);
        $interventoprogrammaculturale->setLabel("Desidero partecipare al programma culturale");
        $interventoprogrammaculturale->setFilters(['striptags', 'string']);
        $this->add($interventoprogrammaculturale);

        $codicestand = new Text("codicestand",["id"=>"primadelcatalogo"]);
        $codicestand->setLabel("Codice stand");
        $codicestand->setFilters(['striptags', 'string', 'trim']);
        $codicestand->addValidators([
            new StringLength(
                [
                    "max"            => 20,
                    "messageMaximum" => "Il Codice Stand è troppo lungo",
                    "allowEmpty" => true,
                ]
            )
        ]);
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
        $catalogocap->addValidators([
            new DigitValidator(
                [
                    "message" => "Il CAP per il catalogo deve contenere solo numeri",
                    "allowEmpty" => true,
                ]
                ),
                new StringLength(
                    [
                        "max"            => 5,
                        "messageMaximum" => "CAP per il catalogotroppo lungo",
                        "allowEmpty" => true,
                    ]
                )
        ]); 
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