<?php



class IndexController extends ControllerBase
{


    public function indexAction()
    {

       // $this->assets->addJs('/vendor/chart.js/Chart.js');
        //$this->assets->addJs('js/demo/chart-pie-demo.js');
        $reservations = Reservations::find("events_id = ".$this->evento->id);
        $stati = Stati::find();
        $this->view->stati = $stati;
        $this->view->richieste = count($reservations);
        $labels = array();
        foreach($stati as $stato){
            $labels[]=$stato->descrizionebreve;
        }
        $this->view->labels = implode("','",$labels);
        $distribution = array();
        foreach($stati as $stato){
            $contatorearea = 0;
            foreach($reservations as $reservation){
                if ($reservation->getStati()->descrizionebreve == $stato->descrizionebreve){
                    $contatorearea++;
                }
            }
            $distribution[]=$contatorearea;
        }

        $this->view->distribution = implode(",",$distribution);
    }

    public function csvespositoriAction()
    {

        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        $reservations = Reservations::find("events_id = ".$this->evento->id);
        $this->response->resetHeaders();
        $this->response->setHeader('Content-Type', 'application/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename=espositori.csv');
        $output = fopen('php://output', 'w');

        fputcsv($output, array(
            'ragione sociale', 
            'area tematica',
            'codicestand',
            'servizi',
            'altri servizi richiesti',
            'intervento programma culturale',
            'richiesta stand personalizzato',
            'stato della richiesta',
            'indirizzo',
            'cap',
            'citta',
            'provincia',
            'telefono',
            'email aziendale',
            'piva codfisc',
            'nome del referente',
            'telefono del referente',
            'email del referente',
            'prodotti esposti',
            'fascia di prezzo',
            'quantita coespositori',
            'nomi coespositori',
        ));

        foreach($reservations as $domandaespositore){

            $serviziacquistati = array();
            $reservationservices = ReservationServices::find("reservations_id = ".$domandaespositore->id);
            foreach($reservationservices as $singoloservizio){
                    $serviziacquistati[] = $singoloservizio->quantita." ".$singoloservizio->getServices()->descrizione;
            }

            fputcsv($output, array(
                $domandaespositore->getExhibitors()->ragionesociale, 
                $domandaespositore->getAreas()->nome,
                $domandaespositore->codicestand,
                implode(", ",$serviziacquistati),
                $domandaespositore->altriservizi,
                $domandaespositore->interventoprogrammaculturale ? "si" : "no",
                $domandaespositore->standpersonalizzato,
                $domandaespositore->getStati()->descrizionebreve,
                $domandaespositore->getExhibitors()->indirizzo,
                $domandaespositore->getExhibitors()->cap,
                $domandaespositore->getExhibitors()->citta,
                $domandaespositore->getExhibitors()->provincia,
                $domandaespositore->getExhibitors()->telefono,
                $domandaespositore->getExhibitors()->emailaziendale,
                $domandaespositore->getExhibitors()->pivacodfisc,
                $domandaespositore->getExhibitors()->referentenome,
                $domandaespositore->getExhibitors()->referentetelefono,
                $domandaespositore->getExhibitors()->referenteemail,
                $domandaespositore->getExhibitors()->prodottiesposti,
                $domandaespositore->getExhibitors()->fasciadiprezzo,
                $domandaespositore->getExhibitors()->numerocoespositore,
                $domandaespositore->getExhibitors()->nomecoespositore
            ));
        }
        fclose($output);

    }



    public function csvcatalogoAction()
    {

        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        $reservations = Reservations::find("events_id = ".$this->evento->id);
        $this->response->resetHeaders();
        $this->response->setHeader('Content-Type', 'application/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename=espositori.csv');
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

