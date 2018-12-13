<?php



class IndexController extends ControllerBase
{


    public function indexAction()
    {

       // $this->assets->addJs('/vendor/chart.js/Chart.js');
        $reservations = Reservations::find("events_id = ".$this->evento->id);
        $stati = Stati::find();
        $this->view->stati = $stati;
        $this->view->richieste = count($reservations);
        $labels = array();
        foreach($stati as $stato){
            $labels[]=$stato->descrizionestato;
        }
        $this->view->labels = implode("','",$labels);

        
        $distribution = array();
        foreach($stati as $stato){
            $contatorearea = 0;
            foreach($reservations as $reservation){
                if ($reservation->getStati()->descrizionestato == $stato->descrizionestato){
                    $contatorearea++;
                }
            }
            $distribution[]=$contatorearea;
            $tbldistribstati[] = $contatorearea;
        }

        $this->view->distribution = implode(",",$distribution);
        $this->view->tbldistribstati = $tbldistribstati;

        // preparo il grafico aree tematiche
        $areas = Areas::find();
        $this->view->areas = $areas;
        $labels = array();
        foreach($areas as $area){
            $labelareas[] = $area->nome;
            $coloriareas[] = $area->colore;
        }
        $this->view->labelareas = implode("','",$labelareas);

        $distributionareas = array();
        foreach($areas as $area){
            $contatoreareas = 0;
            foreach($reservations as $reservation){
                if ($reservation->getAreas()->nome == $area->nome){
                    $contatoreareas++;
                }
            }
            $distributionareas[]=$contatoreareas;
            $tbldistribarea[$area->nome] = $contatoreareas;
        }

        $this->view->distributionareas = implode(",",$distributionareas);
        $this->view->coloriareas = implode("','",$coloriareas);
        $this->view->tbldistribarea = $tbldistribarea;
    }

    public function csvespositoriAction()
    {
        
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        $this->response->resetHeaders();
        $this->response->setHeader('Content-Type', 'application/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename=espositori.csv');
     
        // elenco servizi esistenti per intestazione colonne
        $elencoservizi = Services::find();
        $reservations = Reservations::find("events_id = ".$this->evento->id);

        $nomiservizi = array();
        foreach($elencoservizi as $nomeservizio){
            $nomiservizi[] = $nomeservizio->descrizione;
        }

        $nomicolonne = array(
            'ragione sociale', 
            'area tematica',
            'intervento programma culturale',
            'richiesta stand personalizzato',
            'stato della richiesta',
            'indirizzo',
            'cap',
            'citta',
            'provincia',
            'telefono',
            'email aziendale',
            'piva',
            'codfisc',
            'nome del referente',
            'telefono del referente',
            'email del referente',
            'prodotti esposti',
            'fascia di prezzo',
            'quantita coespositori',
            'nomi coespositori',
            'codicestand',
            'altri servizi richiesti',
        );

        $nomicolonne = array_merge($nomicolonne,$nomiservizi);

        $output = fopen('php://output', 'w');
        
        fputcsv($output, $nomicolonne, ";");

        foreach($reservations as $domandaespositore){
            $sa = array();
            $serviziacquistati = array();
            $reservationservices = ReservationServices::find("reservations_id = ".$domandaespositore->id);
            foreach($reservationservices as $singoloservizio){
                        $serviziacquistati[$singoloservizio->services_id] = $singoloservizio->quantita;
            }
         //   \PhalconDebug::info('serv.ac.',$serviziacquistati);
            foreach($elencoservizi as $servizio){
                if(!empty($serviziacquistati[(int)$servizio->id])){
                    $sa[] = (int)$serviziacquistati[$servizio->id];       
                }
                else{
                    $sa[] = 0;
                }
            }

            $righe = array(
                $domandaespositore->getExhibitors()->ragionesociale, 
                $domandaespositore->getAreas()->nome,
                $domandaespositore->interventoprogrammaculturale ? "si" : "no",
                $domandaespositore->standpersonalizzato,
                $domandaespositore->getStati()->descrizionebreve,
                $domandaespositore->getExhibitors()->indirizzo,
                $domandaespositore->getExhibitors()->cap,
                $domandaespositore->getExhibitors()->citta,
                $domandaespositore->getExhibitors()->provincia,
                $domandaespositore->getExhibitors()->telefono,
                $domandaespositore->getExhibitors()->emailaziendale,
                $domandaespositore->getExhibitors()->piva,
                $domandaespositore->getExhibitors()->codfisc,
                $domandaespositore->getExhibitors()->referentenome,
                $domandaespositore->getExhibitors()->referentetelefono,
                $domandaespositore->getExhibitors()->referenteemail,
                $domandaespositore->getExhibitors()->prodottiesposti,
                $domandaespositore->getExhibitors()->fasciadiprezzo,
                $domandaespositore->getExhibitors()->numerocoespositore,
                $domandaespositore->getExhibitors()->nomecoespositore,
                $domandaespositore->codicestand,
                $domandaespositore->altriservizi,
            );
            \PhalconDebug::info('riga prima',$righe,$sa);
            $righe = array_merge($righe,$sa);
            fputcsv($output, $righe, ";");
            \PhalconDebug::info('riga dopo',$righe);
        }
        
        fclose($output);

    }



    public function csvcatalogoAction()
    {

        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        $reservations = Reservations::find("events_id = ".$this->evento->id);
        $this->response->resetHeaders();
        $this->response->setHeader('Content-Type', 'application/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename=catalogoespositori.csv');
        $output = fopen('php://output', 'w');

        fputcsv($output, array(
            'Dati per il Catalogo '.$this->evento->descrizione,
        ));

        fputcsv($output, array(
            'nome',
            'indirizzo',
            'cap',
            'citta',
            'provincia',
            'telefono',
            'email',
            'sito web',
            'pagina facebook',
            'profilo instagram',
            'profilo twitter',
            'descrizione',
        ));

        foreach($reservations as $domandaespositore){

            $serviziacquistati = array();
            $reservationservices = ReservationServices::find("reservations_id = ".$domandaespositore->id);
            foreach($reservationservices as $singoloservizio){
                    $serviziacquistati[] = $singoloservizio->quantita." ".$singoloservizio->getServices()->descrizione;
            }

            fputcsv($output, array(
                $domandaespositore->getExhibitors()->catalogonome,
                $domandaespositore->getExhibitors()->catalogoindirizzo,
                $domandaespositore->getExhibitors()->catalogocap,
                $domandaespositore->getExhibitors()->catalogocitta,
                $domandaespositore->getExhibitors()->catalogoprovincia,
                $domandaespositore->getExhibitors()->catalogotelefono,
                $domandaespositore->getExhibitors()->catalogoemail,
                $domandaespositore->getExhibitors()->catalogositoweb,
                $domandaespositore->getExhibitors()->catalogofacebook,
                $domandaespositore->getExhibitors()->catalogoinstagram,
                $domandaespositore->getExhibitors()->catalogotwitter,
                $domandaespositore->getExhibitors()->catalogodescrizione,
            ));
        }
        fclose($output);

    }


}

