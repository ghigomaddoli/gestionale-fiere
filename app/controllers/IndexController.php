<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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
                if ($reservation->stati->descrizionestato == $stato->descrizionestato){
                    $contatorearea++;
                }
            }
            $distribution[]=$contatorearea;
            $tbldistribstati[] = $contatorearea;
            $coloristato[] = $stato->esadecimale;
        }

        $this->view->distribution = implode(",",$distribution);
        $this->view->tbldistribstati = $tbldistribstati;
        $this->view->coloristato = implode("','",$coloristato);

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
                if ($reservation->areas->nome == $area->nome){
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
        $elencoservizi = Services::find("events_id = ".$this->evento->id);
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
            'padiglione',
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
                htmlspecialchars_decode($domandaespositore->exhibitors->ragionesociale,ENT_QUOTES),
                htmlspecialchars_decode($domandaespositore->areas->nome,ENT_QUOTES),
                $domandaespositore->interventoprogrammaculturale ? "si" : "no",
                htmlspecialchars_decode($domandaespositore->standpersonalizzato,ENT_QUOTES),
                $domandaespositore->stati->descrizionebreve,
                htmlspecialchars_decode($domandaespositore->exhibitors->indirizzo,ENT_QUOTES),
                $domandaespositore->exhibitors->cap,
                $domandaespositore->exhibitors->citta,
                $domandaespositore->exhibitors->provincia,
                $domandaespositore->exhibitors->telefono,
                $domandaespositore->exhibitors->emailaziendale,
                $domandaespositore->exhibitors->piva,
                $domandaespositore->exhibitors->codfisc,
                htmlspecialchars_decode($domandaespositore->exhibitors->referentenome,ENT_QUOTES),
                $domandaespositore->exhibitors->referentetelefono,
                $domandaespositore->exhibitors->referenteemail,
                htmlspecialchars_decode($domandaespositore->exhibitors->prodottiesposti,ENT_QUOTES),
                $domandaespositore->exhibitors->fasciadiprezzo,
                $domandaespositore->exhibitors->numerocoespositore,
                htmlspecialchars_decode($domandaespositore->exhibitors->nomecoespositore,ENT_QUOTES),
                $domandaespositore->codicestand,
                $domandaespositore->padiglione,
                htmlspecialchars_decode($domandaespositore->altriservizi,ENT_QUOTES),
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
        $reservations = Reservations::find("events_id = ".$this->evento->id." AND stato = 3 ORDER BY id desc");
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
        ),";");

        foreach($reservations as $domandaespositore){

            $serviziacquistati = array();
            $reservationservices = ReservationServices::find("reservations_id = ".$domandaespositore->id);
            foreach($reservationservices as $singoloservizio){
                    $serviziacquistati[] = $singoloservizio->quantita." ".$singoloservizio->services->descrizione;
            }

            fputcsv($output, array(
                htmlspecialchars_decode($domandaespositore->exhibitors->catalogonome,ENT_QUOTES),
                htmlspecialchars_decode($domandaespositore->exhibitors->catalogoindirizzo,ENT_QUOTES),
                $domandaespositore->exhibitors->catalogocap,
                htmlspecialchars_decode($domandaespositore->exhibitors->catalogocitta,ENT_QUOTES),
                $domandaespositore->exhibitors->catalogoprovincia,
                $domandaespositore->exhibitors->catalogotelefono,
                $domandaespositore->exhibitors->catalogoemail,
                $domandaespositore->exhibitors->catalogositoweb,
                $domandaespositore->exhibitors->catalogofacebook,
                $domandaespositore->exhibitors->catalogoinstagram,
                $domandaespositore->exhibitors->catalogotwitter,
                htmlspecialchars_decode($domandaespositore->exhibitors->catalogodescrizione,ENT_QUOTES),
            ),";");
        }
        fclose($output);

    }


    public function xlsespositoriAction()
    {
        
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        $this->response->resetHeaders();
        $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response->setHeader('Content-Disposition', 'attachment; filename=espositori.xlsx');
        
        // elenco servizi esistenti per intestazione colonne
        $elencoservizi = Services::find("events_id = ".$this->evento->id);
       
        $areetematiche = Areas::find("events_id = ".$this->evento->id);

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
            'padiglione',
            'altri servizi richiesti',
        );

        $nomicolonne = array_merge($nomicolonne,$nomiservizi);

        $spreadsheet = new Spreadsheet();
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

        foreach ($areetematiche as $area){          

            $reservations = null;
            $reservations = Reservations::find("events_id = ".$this->evento->id." AND areas_id = ".$area->id);
            if(count($reservations) > 0){
                
                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $area->nome); 
                $spreadsheet->addSheet($myWorkSheet, 0);
                $sheet = $spreadsheet->setActiveSheetIndex(0);
                $sheet->fromArray($nomicolonne,Null,'A1');             
                $contatorerighe=2;
                foreach($reservations as $domandaespositore){
                    $sa = array();
                    $serviziacquistati = array();
                    $reservationservices = ReservationServices::find("reservations_id = ".$domandaespositore->id);
                    foreach($reservationservices as $singoloservizio){
                                $serviziacquistati[$singoloservizio->services_id] = $singoloservizio->quantita;
                    }
                    foreach($elencoservizi as $servizio){
                        if(!empty($serviziacquistati[(int)$servizio->id])){
                            $sa[] = (int)$serviziacquistati[$servizio->id];       
                        }
                        else{
                            $sa[] = 0;
                        }
                    }
        
                    $righe = array(
                        htmlspecialchars_decode($domandaespositore->exhibitors->ragionesociale,ENT_QUOTES), 
                        $domandaespositore->areas->nome,
                        $domandaespositore->interventoprogrammaculturale ? "si" : "no",
                        htmlspecialchars_decode($domandaespositore->standpersonalizzato,ENT_QUOTES),
                        $domandaespositore->stati->descrizionebreve,
                        htmlspecialchars_decode($domandaespositore->exhibitors->indirizzo,ENT_QUOTES),
                        $domandaespositore->exhibitors->cap,
                        htmlspecialchars_decode($domandaespositore->exhibitors->citta,ENT_QUOTES),
                        $domandaespositore->exhibitors->provincia,
                        $domandaespositore->exhibitors->telefono,
                        $domandaespositore->exhibitors->emailaziendale,
                        $domandaespositore->exhibitors->piva,
                        $domandaespositore->exhibitors->codfisc,
                        htmlspecialchars_decode($domandaespositore->exhibitors->referentenome,ENT_QUOTES),
                        $domandaespositore->exhibitors->referentetelefono,
                        $domandaespositore->exhibitors->referenteemail,
                        htmlspecialchars_decode($domandaespositore->exhibitors->prodottiesposti,ENT_QUOTES),
                        $domandaespositore->exhibitors->fasciadiprezzo,
                        $domandaespositore->exhibitors->numerocoespositore,
                        htmlspecialchars_decode($domandaespositore->exhibitors->nomecoespositore,ENT_QUOTES),
                        $domandaespositore->codicestand,
                        $domandaespositore->padiglione,
                        htmlspecialchars_decode($domandaespositore->altriservizi,ENT_QUOTES),
                    );
                    $righe = array_merge($righe,$sa);
                    $sheet->fromArray( $righe, NULL, 'A'.$contatorerighe );  
                    $contatorerighe++;
                }
            }

        }   
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Worksheet')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);
         $writer->save('php://output');

    }

    public function xlscatalogoAction()
    {

        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        $this->response->resetHeaders();
        $this->response->setHeader('Content-Type', 'application/csv');
        $this->response->setHeader('Content-Disposition', 'attachment; filename=catalogoespositori.xlsx');

        $areetematiche = Areas::find("events_id = ".$this->evento->id);

        $nomicolonne = array(
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
        );

        $spreadsheet = new Spreadsheet();
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $reservations = null;

        foreach ($areetematiche as $area){

            $reservations = Reservations::find("events_id = ".$this->evento->id." AND areas_id = ".$area->id." AND stato = 3 ORDER BY id desc");
            if(count($reservations) > 0){
                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $area->nome); 
                $spreadsheet->addSheet($myWorkSheet, 0);
                $sheet = $spreadsheet->setActiveSheetIndex(0);
                $sheet->fromArray($nomicolonne,Null,'A1');
                
                $contatorerighe=2;
                foreach($reservations as $domandaespositore){

                    $lariga =  array(
                        htmlspecialchars_decode($domandaespositore->exhibitors->catalogonome,ENT_QUOTES),
                        htmlspecialchars_decode($domandaespositore->exhibitors->catalogoindirizzo,ENT_QUOTES),
                        $domandaespositore->exhibitors->catalogocap,
                        htmlspecialchars_decode($domandaespositore->exhibitors->catalogocitta,ENT_QUOTES),
                        $domandaespositore->exhibitors->catalogoprovincia,
                        $domandaespositore->exhibitors->catalogotelefono,
                        $domandaespositore->exhibitors->catalogoemail,
                        $domandaespositore->exhibitors->catalogositoweb,
                        $domandaespositore->exhibitors->catalogofacebook,
                        $domandaespositore->exhibitors->catalogoinstagram,
                        $domandaespositore->exhibitors->catalogotwitter,
                        htmlspecialchars_decode($domandaespositore->exhibitors->catalogodescrizione,ENT_QUOTES),
                    );
                    $sheet->fromArray( $lariga, NULL, 'A'.$contatorerighe );  
                    $contatorerighe++;

                }
            }
        }
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Worksheet')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);
        $writer->save('php://output');

    }    


}

