<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Mvc\Model\Query;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

class ReservationsController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->areas = Areas::find("events_id = ".$this->evento->id);
        $this->view->stati = $this->stati;
        $numberPage = 1;
        $par = array();
        $parameters = array();
        $orderby = '';

        if ($this->request->isPost()) {

            $areas_id = $this->request->getPost('areas_id', 'int');
            $stato = $this->request->getPost('stato', 'int');
            $ragionesociale = $this->request->getPost('ragionesociale', 'string');
            $interventoprogrammaculturale = $this->request->getPost('interventoprogrammaculturale', 'int');
            $orderby = $this->request->getPost('orderby', 'string');

            if(!empty($areas_id)){
                $par["areas_id"] = $areas_id;
            }
            if(!empty($stato)){
                $par["stato"] = $stato;
            }
            if(!empty($ragionesociale)){
                $par["ragionesociale"] = $ragionesociale;
            }
            if(!empty($interventoprogrammaculturale)){
                $par["interventoprogrammaculturale"] = $interventoprogrammaculturale;
            }            
            if(!empty($orderby)){
                $par["orderby"] = $orderby;
            }
          //  \PhalconDebug::info($par);
            $this->persistent->searchParams = $par;
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $builder = $this->modelsManager->createBuilder()
        ->from('Reservations')
        ->join('Exhibitors')
        ->where('1 = 1');

        if ($this->persistent->searchParams && count($this->persistent->searchParams)) {
            $parameters = $this->persistent->searchParams;
        }

       // \PhalconDebug::info("this->persistent->searchParams",$this->persistent->searchParams);

        foreach($parameters as $campo => $valore){
            switch($campo){
                case "ragionesociale":
                    $builder->andWhere("Exhibitors.{$campo} like '%".$valore."%'");
                break;
                case "orderby":
                    $builder->orderBy($valore);
                break;
                default:
                    $builder->andWhere("{$campo} = {$valore}");
                break;
            }
        }

        if(empty($parameters['orderby'])){
            $builder->orderBy('Reservations.id DESC');
        }

        

       $paginator = new PaginatorQueryBuilder(
            [
                'builder' => $builder,
                'limit'   => 10,
                'page'    => $numberPage,
            ]
        );

        $this->view->page = $paginator->getPaginate();

        if ($this->view->page->total_items == 0) {
            $this->flash->notice("Nessun espositore da mostrare con questi criteri di ricerca");
            $this->persistent->searchParams = null;
        }     

        $this->assets->addJs('js/reservations-index.js');

    }

    public function initialize()
    {
        $this->tag->setTitle('Prenotazioni');
        parent::initialize();
    }

    public function editAction($id)
    {
        $reservation = Reservations::findFirstById($id);
        if (!$reservation) {
            $this->flash->error("Dettaglio della richiesta stand non trovato");

            return $this->dispatcher->forward(
                [
                    "controller" => "exhibitors",
                    "action"     => "index",
                ]
            );
        }

        $this->view->reservation = $reservation;
        $this->view->areas = Areas::find("events_id = ".$reservation->events_id);
        $this->tag->setDefault('areas_id', $reservation->areas_id);
        $this->view->stands = Services::find("events_id = ".$reservation->events_id." AND tipologia IN (1,2)");
        $this->view->services = Services::find("events_id = ".$reservation->events_id." AND tipologia = 3");
        $reservationservices = ReservationServices::find("reservations_id = ".$reservation->id);
        $this->view->reservationservices = $reservationservices;

        $prezzocalcolato = 0;
        $campoprezzo = $reservation->getExhibitors()->fasciadiprezzo == 'a' ? 'prezzofasciaa' : 'prezzofasciab';

        //calcolo del prezzo totale teorico:
        foreach($reservationservices as $servizio){
            $prezzo = $servizio->getServices()->$campoprezzo * $servizio->quantita;
            \PhalconDebug::info($servizio->getServices()->descrizione.':'.$prezzo.', quantita:'.$servizio->quantita);
            $prezzocalcolato = $prezzocalcolato + $prezzo;
        }
        $this->view->prezzocalcolato = $prezzocalcolato;

        $stati = Stati::find();
        $this->view->stati = $stati;
        $this->view->statimax = count($stati);

        $this->assets->addJs('js/reservations-edit.js');
    }



    /**
     * salva la prenotazione corrente
     *
     * @param string $id
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->flash->error("Richiesta con metodo non valido");
            return $this->dispatcher->forward(
                [
                    "controller" => "index",
                    "action"     => "index",
                ]
            );
        }

        $id = $this->request->getPost("id", "int");


        // Inizio della transaction
        $this->db->begin();

        $reservation = Reservations::findFirstById($id);
        if (!$reservation) {
            $this->flash->error("la Prenotazione stand non esiste");

            return $this->dispatcher->forward(
                [
                    "controller" => "index",
                    "action"     => "index",
                ]
            );
        }

        $form = new ReservationsForm;
        $this->view->form = $form;

        $data = $this->request->getPost();

        if (!$form->isValid($data, $reservation)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "reservations",
                    "action"     => "edit",
                    "params"     => [$id]
                ]
            );
        }


        if ($reservation->save() == false) {
            foreach ($reservation->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->db->rollback();

            return $this->dispatcher->forward(
                [
                    "controller" => "reservations",
                    "action"     => "edit",
                    "params"     => [$id]
                ]
            );
        }

        // cancello i records su reservation_services e li riinserisco aggiornati    
        $rs = ReservationServices::find("reservations_id='{$id}'");

        if($rs->delete()== false){
            foreach ($rs->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->db->rollback();

            return $this->dispatcher->forward(
                [
                    "controller" => "reservations",
                    "action"     => "edit",
                    "params"     => [$id]
                ]
            );
        }
        $servizi = $this->request->getPost("services");
        \PhalconDebug::info("servizi",$servizi);
        if(is_array($servizi) && count($servizi)){
            foreach($servizi as $idservizio => $quantita){
                if($quantita == 0) continue;
                $rs = new ReservationServices;
                $rs->reservations_id = $id;
                $rs->services_id = $idservizio;
                $rs->quantita = $quantita;
                if ($rs->save() == false) {
                    foreach ($rs->getMessages() as $message) {
                        $this->flash->error($message);
                    }

                    $this->db->rollback();
        
                    return $this->dispatcher->forward(
                        [
                            "controller" => "reservations",
                            "action"     => "edit",
                            "params"     => [$id]
                        ]
                    );
                }
            }
        }
        
        // Commit the transaction
        $this->db->commit();

        $form->clear();    

        $this->flash->success("I dati della prenotazione dell'espositore {$reservation->getExhibitors()->ragionesociale} sono stati aggiornati");

        return $this->dispatcher->forward(
            [
                "controller" => "reservations",
                "action"     => "edit",
                "params"     => [$id]
            ]
        );
    }

    /**
     * genera il file pdf della "Lettera di ammissione"
     *
     * @param string $id
     */
    public function anteprimaletteraAction($id){

        if (!$this->request->isGet()) {
            $this->flash->error("Richiesta non valida");

            return $this->dispatcher->forward(
                [
                    "controller" => "reservations",
                    "action"     => "edit",
                    "params"     => [$id]
                ]
            );
        }

        $reservation = Reservations::findFirstById($id);

        if (!$reservation) {
            $this->flash->error("la Prenotazione non esiste");

            return $this->dispatcher->forward(
                [
                    "controller" => "index",
                    "action"     => "index",
                ]
            );
        }

        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        $this->response->resetHeaders();
        $this->response->setHeader('Content-Type', 'application/pdf');
        $this->response->setHeader('Content-Disposition', "attachment; filename=lettera-ammissione-{$reservation->id}.pdf");

        // Instanciation of inherited class
        $pdf = new Mypdf();
        $pdf->AliasNbPages();
        $pdf->AddPage();


        // titolo evento
        $pdf->Cell(0,8,"Lettera di ammissione",0,1,'C');
        $pdf->Cell(0,8,$this->evento->descrizione,0,1,'C');

        $pdf->SetFont('Times','',12);
        $pdf->Cell(0,6,"Da inviare firmata a giorgio@falacosagiustaumbria.it o via fax allo 075 3721786",0,1,'C');
        $pdf->Cell(0,6,utf8_decode("Il saldo dovrà essere corrisposto entro e non oltre i termini indicati nella fattura."),0,1,'C');
        $pdf->Ln();

        $pdf->SetFont('Times','B',12);
        $pdf->Cell(0,6,"DATI ESPOSITORE PER LA FATTURAZIONE",0,1,'L');

        $pdf->SetFont('Times','',12);
        $datifatturazione["Ragione Sociale"] = utf8_decode($reservation->getExhibitors()->ragionesociale);
        $datifatturazione["Indirizzo"] = utf8_decode($reservation->getExhibitors()->indirizzo." - ".$reservation->getExhibitors()->cap." - ".ucfirst($reservation->getExhibitors()->citta)." (".$reservation->getExhibitors()->provincia.")");
        $datifatturazione["Telefono e Email"] = utf8_decode($reservation->getExhibitors()->telefono." ".$reservation->getExhibitors()->emailaziendale);
        if(!empty($reservation->getExhibitors()->piva)) $datifatturazione["Partita Iva"] = $reservation->getExhibitors()->piva;
        if(!empty($reservation->getExhibitors()->codfisc)) $datifatturazione["Codice Fiscale"] = $reservation->getExhibitors()->codfisc;
        $pdf->BasicTable($datifatturazione);
        $pdf->Ln();

        $pdf->SetFont('Times','B',12);
        $pdf->Cell(0,6,"REFERENTE ESPOSITORE PER CONTATTI PRIMA E DURANTE LA FIERA",0,1,'L');

        $pdf->SetFont('Times','',12);
        $datireferente["Nome e cognome"] = utf8_decode($reservation->getExhibitors()->referentenome);
        $datireferente["Cellulare e Email"] = utf8_decode($reservation->getExhibitors()->referentetelefono." - ".$reservation->getExhibitors()->referenteemail);
        $pdf->BasicTable($datireferente);
        $pdf->Ln();

        $pdf->SetFont('Times','B',12);
        $pdf->Cell(0,6,"RIEPILOGO ORDINE",0,1,'L');

        $pdf->SetFont('Times','',12);
        $riepilogo["Sezione Tematica"] = utf8_decode($reservation->getAreas()->nome);
        $riepilogo["Elenco prodotti"] = utf8_decode(substr($reservation->getExhibitors()->prodottiesposti,0,87)."...");
        $riepilogo["Codice Spazio"] = utf8_decode($reservation->codicestand);
        $pdf->BasicTable($riepilogo);

        // riga grigia:
        $pdf->SetFillColor(240,0,0);
        $pdf->Cell(190,6,'',1);
        $pdf->Ln();

        $reservationservices = ReservationServices::find("reservations_id = ".$reservation->id);
        $fieldprezzo = 'prezzofasciaa';
        switch($reservation->getExhibitors()->fasciadiprezzo){
            case 'b':
                $fieldprezzo = 'prezzofasciab';
            break;
            default:
                $fieldprezzo = 'prezzofasciaa';
            break;
        }

        // servizi acquistati, quantità e prezzo
        $pdf->Cell(82,6,"Descrizione",1);
        $pdf->Cell(27,6,utf8_decode("Quantità"),1);
        $pdf->Cell(27,6,"Costo unitario",1);
        $pdf->Cell(27,6,"Prezzo",1); //prezzo = quantita * prezzofascia
        $pdf->Cell(27,6,utf8_decode("Iva al 22%"),1); //prezzo inclusivo di iva
        $pdf->Ln();
        
        $totale = 0;
        $costifissi = 200;
        foreach($reservationservices as $reservationservice){
            if($reservationservice->getServices()->descrizione == "Costo fisso di iscrizione") $costifissi = $reservationservice->getServices()->$fieldprezzo;
            $pdf->Cell(82,6,$reservationservice->getServices()->descrizione,1);
            $pdf->Cell(27,6,$reservationservice->quantita,1,0,'R');
            $pdf->Cell(27,6,number_format($reservationservice->getServices()->$fieldprezzo,2,",","."),1,0,'R');
            $pdf->Cell(27,6,number_format($reservationservice->quantita * $reservationservice->getServices()->$fieldprezzo,2,",","."),1,0,'R'); //prezzo = quantita * prezzofascia
            $pr = $reservationservice->quantita * $reservationservice->getServices()->$fieldprezzo;
            $iva = $reservationservice->quantita * $reservationservice->getServices()->$fieldprezzo * 0.22;
            $totale += $pr;
            $pdf->Cell(27,6,number_format($pr + $iva,2,",","."),1,0,'R'); //prezzo inclusivo di iva
            $pdf->Ln();
        }
        // rigo del totale
        $pdf->SetFillColor(220, 158, 5); // giallo
        
        $pdf->Cell(136,6,'COSTO TOTALE',1,0,'L');
        $pdf->Cell(54,6,"EURO ".number_format($totale + $totale * 0.22,2,",","."),1,0,'C'); //prezzo TOTALE inclusivo di iva
        $pdf->Ln();

        // rigo del totale meno il costo fisso
        $pdf->SetFillColor(219, 84, 6); // arancio chiaro        
        $pdf->Cell(136,6,'COSTO TOTALE DA CORRISPONDERE',1,0,'L');
        $pdf->Cell(54,6,"EURO ".number_format(($totale + $totale * 0.22)-$costifissi,2,",","."),'LTR',0,'C'); //prezzo TOTALE meno anticipo
        $pdf->Ln();

        $pdf->SetFillColor(0); // arancio chiaro    
        $pdf->Cell(136,6,'(Costo totale meno costo fisso di iscrizione gia versato)','LRB',0,'L');
        $pdf->Cell(54,6,'',1,0,'C');
        $pdf->Ln();

        // note spazio espositivo
        $pdf->Cell(82,18,'DESCRIZIONE SPAZIO E NOTE',1,0,'L');
        $pdf->Cell(108,18,utf8_decode($reservation->standpersonalizzato),1,0,'C');
        $pdf->Ln();

        // data e firma
        $pdf->SetFont('Times','',12);
        $pdf->Cell(136,6,'',0,0,'L');
        $pdf->Cell(54,6,'',0,0,'L');
        $pdf->Ln();        
        $pdf->Cell(136,6,"Luogo e data",0,0,'L');
        $pdf->Cell(54,6,utf8_decode("Firma"),0,0,'L');
        $pdf->Ln();


        $pdf->Output();


    }    

}

