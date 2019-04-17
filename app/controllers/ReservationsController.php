<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Mvc\Model\Query;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
            $ragionesociale = $this->request->getPost('cercaragionesociale', 'string');
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
        $this->assets->addCss('https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css');
        $this->assets->addCss('css/style.css');
        $this->assets->addJs('https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js');
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
        if($reservation->padre_id){
            $mainreservation = Reservations::findFirstById($reservation->padre_id);
            $this->view->mainreservation = $mainreservation;
            $this->view->mainreservationservices = ReservationServices::find("reservations_id = ".$reservation->padre_id);
        }

        $this->view->reservation = $reservation;
        $this->view->areas = Areas::find("events_id = ".$reservation->events_id);
        $this->tag->setDefault('areas_id', $reservation->areas_id);
        $this->view->stands = Services::find("events_id = ".$reservation->events_id." AND tipologia IN (1,2)");
        $this->view->services = Services::find("events_id = ".$reservation->events_id." AND tipologia = 3");
        $this->view->users = Users::find(" attivo = 1 ");
        $this->tag->setDefault('users_id', $reservation->users_id);
        $reservationservices = ReservationServices::find("reservations_id = ".$reservation->id);
        $this->view->reservationservices = $reservationservices;

        $prezzocalcolato = 0;
        $campoprezzo = $reservation->exhibitors->fasciadiprezzo == 'a' ? 'prezzofasciaa' : 'prezzofasciab';

        //calcolo del prezzo totale teorico:
        foreach($reservationservices as $servizio){
            $prezzo = $servizio->services->$campoprezzo * $servizio->quantita;
            $prezzocalcolato = $prezzocalcolato + $prezzo;
        }
        $this->view->prezzocalcolato = $prezzocalcolato;

        $stati = Stati::find();
        $this->view->stati = $stati;
        $this->view->statimax = count($stati);

        $this->view->logstatireservations = LogStatiReservations::find("reservations_id = ".$reservation->id." ORDER BY dataora DESC");

        $this->assets->addCss('css/style.css');
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

        // tutti i campi flag che non arrivano sono = 0
        $interventoprogrammaculturale = $this->request->getPost('interventoprogrammaculturale', 'int');
        if(empty($interventoprogrammaculturale)){
            $reservation->interventoprogrammaculturale = 0;
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
        $this->flash->success("I dati della prenotazione dell'espositore {$reservation->exhibitors->ragionesociale} sono stati aggiornati");

        return $this->dispatcher->forward(
            [
                "controller" => "reservations",
                "action"     => "edit",
                "params"     => [$id]
            ]
        );
    }

    /**
     * spara in stream il file pdf della "Lettera di ammissione"
     *
     * @param string $id
     */
    public function anteprimaletteraAction($id){
        $ilpdf = $this->generapdf($id, true);
        $ilpdf->Output();
    }

     /**
     * allega alla email il file pdf della "Lettera di ammissione"
     *
     * @param string $id
     */
    public function invialetteraAction(){


        if (!$this->request->isAjax()) {
            $ris["incima"] = "Richiesta non valida";
            $ris["status"] = "KO";
            return $this->response->setJsonContent($ris);
        }
        
        $id = $this->request->getPost("reservationid","int");
        $reservation = Reservations::findFirstById($id);
        $exhibitors = $reservation->exhibitors;
        $ilpdf = $this->generapdf($id, false);

        $string = strtolower($reservation->exhibitors->ragionesociale);
        $string = preg_replace("/[^0-9A-Za-z ]/", "", $string);
        $string = str_replace(" ", "-", $string);
        while (strstr($string, "--")) {
            $string = preg_replace("/--/", "-", $string);
        }
        $permalink = (utf8_decode($string));

        $ilpdf->Output('temp/lettera-ammissione-'.$permalink.'.pdf','F');
        $allegato = array("filepath" => 'temp/lettera-ammissione-'.$permalink.'.pdf', "mimetype" => 'application/pdf');
        $auth = $this->session->get('auth');
        
        $parametri = array(
            'exhibitors' => $exhibitors,
            'evento' => $this->evento->descrizione, 
            'destinatari' => array($exhibitors->emailaziendale => $exhibitors->ragionesociale, $exhibitors->referenteemail => $exhibitors->referentenome, "amministrazione@falacosagiustaumbria.it" => "Amministrazione FLCGU"),
            'allegato' => $allegato,
            'replyto' => array($auth["email"] => $auth["nome"])
        );        
        $result = MyEmailSender::inviaEmail($this, 'letteraammissione', $parametri,"Lettera di Ammissione per ".$this->evento->descrizione);

        if($result > 0){
            $ris["status"] = "OK";
            $ris['incima'] = "Invio effettuato con successo!";
            $auth = $this->session->get('auth');

            $logstatireservations = new LogStatiReservations();
            $logstatireservations->reservations_id = $id;
            $logstatireservations->stati_id = $reservation->stato;
            $logstatireservations->users_id = $auth['id'];
            $logstatireservations->dataora = date("Y-m-d H:i:s");
            $logstatireservations->messaggio = $auth["username"]." ha inviato la lettera di ammissione al cliente.";
            if ($logstatireservations->save() === false) {

                $i=0;
                foreach ($logstatireservations->getMessages() as $message) {
                    \PhalconDebug::info("errore: ".$message);
                    $ris['incima'] .= ' '.$message;
                    $i++;
                }
                $ris["status"] = "KO";
                return $this->response->setJsonContent($ris);
            }
            else{
                $ris['incima'] .= "<br>L'evento è stato inserito nel diario";
            }
            return $this->response->setJsonContent($ris);
        }
        else{
            $ris["status"] = "KO";
            $ris['incima'] = "errore di invio email.";
            return $this->response->setJsonContent($ris);
        }
        // @unlink('temp/lettera-ammissione-'.$permalink.'.pdf');

    }

    /**
     * genera il file pdf della "Lettera di ammissione"
     *
     * @param string $id
     */
    public function generapdf($id,$headers = true){

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

        if($headers === true){
            $string = strtolower($reservation->exhibitors->ragionesociale);
            $string = preg_replace("/[^0-9A-Za-z ]/", "", $string);
            $string = str_replace(" ", "-", $string);
            while (strstr($string, "--")) {
                $string = preg_replace("/--/", "-", $string);
            }
            $permalink = (utf8_decode($string));
            
            $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
            $this->response->resetHeaders();   
            $this->response->setHeader('Content-Type', 'application/pdf');
            $this->response->setHeader('Content-Disposition', "attachment; filename=lettera-ammissione-{$permalink}.pdf");
        }

        // Instanciation of inherited class
        $pdf = new Mypdf();
        $pdf->SetAuthor(utf8_decode("Fairlab S.r.l."));
        $pdf->SetCreator(utf8_decode("Gestionale fiere Fairlab S.r.l."));
        $pdf->SetTitle(utf8_decode("Lettera di ammissione per ".utf8_decode($this->evento->descrizione)));
        $pdf->SetSubject(utf8_decode($this->evento->descrizione));
        $pdf->AliasNbPages();
        $pdf->AddPage();


        // titolo evento
        $pdf->Cell(0,8,"Lettera di ammissione",0,1,'C');
        $pdf->Cell(0,8,$this->evento->descrizione,0,1,'C');

        $auth = $this->session->get('auth');
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(0,6,utf8_decode("Da inviare firmata a ".$auth["email"]." o via fax allo 075 3721786."),0,'L',false);
        $pdf->SetFont('Times','',10);
        $pdf->MultiCell(0,6,utf8_decode("Si prega di versare il COSTO DI ISCRIZIONE tramite bonifico su conto corrente presso Banca Etica di Perugia. \nCodice iban: IT18W0501803000000012426979 Intestato a: Fair Lab srls. \nCausale: la ragione sociale della propria azienda."),1,'C',false);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(0,6,utf8_decode("Finché l'organizzazione non riceve il costo di iscrizione non considera opzionato lo spazio in mappa. Il saldo dovrà essere corrisposto entro e non oltre i termini indicati nella fattura."),0,'L',false);
        $pdf->Ln();

        $pdf->SetFont('Times','B',12);
        $pdf->Cell(0,6,"DATI ESPOSITORE PER LA FATTURAZIONE",0,1,'L');

        $pdf->SetFont('Times','',12);
        $datifatturazione["Ragione Sociale"] = htmlspecialchars_decode(utf8_decode($reservation->exhibitors->ragionesociale),ENT_QUOTES);
        $datifatturazione["Indirizzo"] = htmlspecialchars_decode(utf8_decode($reservation->exhibitors->indirizzo." - ".$reservation->exhibitors->cap." - ".ucfirst($reservation->exhibitors->citta)." (".$reservation->exhibitors->provincia.")"),ENT_QUOTES);
        $datifatturazione["Telefono e Email"] = utf8_decode($reservation->exhibitors->telefono." ".$reservation->exhibitors->emailaziendale);
        if(!empty($reservation->exhibitors->piva)) $datifatturazione["Partita Iva"] = $reservation->exhibitors->piva;
        if(!empty($reservation->exhibitors->codfisc)) $datifatturazione["Codice Fiscale"] = $reservation->exhibitors->codfisc;
        $pdf->BasicTable($datifatturazione);
        $pdf->Ln();

        $pdf->SetFont('Times','B',12);
        $pdf->Cell(0,6,"REFERENTE ESPOSITORE PER CONTATTI PRIMA E DURANTE LA FIERA",0,1,'L');

        $pdf->SetFont('Times','',12);
        $datireferente["Nome e cognome"] = htmlspecialchars_decode(utf8_decode($reservation->exhibitors->referentenome),ENT_QUOTES);
        $datireferente["Cellulare e Email"] = utf8_decode($reservation->exhibitors->referentetelefono." - ".$reservation->exhibitors->referenteemail);
        $pdf->BasicTable($datireferente);
        $pdf->Ln();

        $pdf->SetFont('Times','B',12);
        $pdf->Cell(0,6,"RIEPILOGO ORDINE",0,1,'L');

        $pdf->SetFont('Times','',12);
        $riepilogo["Sezione Tematica"] = utf8_decode($reservation->areas->nome);
        $riepilogo["Elenco prodotti"] = htmlspecialchars(utf8_decode(substr($reservation->exhibitors->prodottiesposti,0,87)."..."),ENT_QUOTES);
        $riepilogo["Codice Spazio"] = utf8_decode($reservation->codicestand);
        $pdf->BasicTable($riepilogo);

        // riga grigia:
        $pdf->SetFillColor(240,0,0);
        $pdf->Cell(190,6,'',1);
        $pdf->Ln();

        $reservationservices = ReservationServices::find("reservations_id = ".$reservation->id);
        $fieldprezzo = 'prezzofasciaa';
        switch($reservation->exhibitors->fasciadiprezzo){
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
        $ivatot = 0;
        foreach($reservationservices as $reservationservice){
            if($reservationservice->services->descrizione == "Costo fisso di iscrizione") $costifissi = $reservationservice->services->$fieldprezzo;
            $pdf->Cell(82,6,$reservationservice->services->descrizione,1);
            $pdf->Cell(27,6,$reservationservice->quantita,1,0,'R');
            $pdf->Cell(27,6,number_format($reservationservice->services->$fieldprezzo,2,",","."),1,0,'R');
            $pdf->Cell(27,6,number_format($reservationservice->quantita * $reservationservice->services->$fieldprezzo,2,",","."),1,0,'R'); //prezzo = quantita * prezzofascia
            $pr = $reservationservice->quantita * $reservationservice->services->$fieldprezzo;
            $iva = $reservationservice->quantita * $reservationservice->services->$fieldprezzo * 0.22;
            $ivatot += $iva;
            $totale += $pr;
            $pdf->Cell(27,6,number_format($iva,2,",","."),1,0,'R'); //prezzo inclusivo di iva
            $pdf->Ln();
        }
        // rigo stand personalizzato
        if ($reservation->prezzostandpersonalizzato > 0){
            $pdf->Cell(82,6,"Stand personalizzato",1);
            $pdf->Cell(27,6,1,1,0,'R');
            $pdf->Cell(27,6,number_format($reservation->prezzostandpersonalizzato,2,",","."),1,0,'R');
            $pdf->Cell(27,6,number_format($reservation->prezzostandpersonalizzato,2,",","."),1,0,'R'); //prezzo = quantita * prezzofascia
            $pr = $reservation->prezzostandpersonalizzato;
            $iva = $reservation->prezzostandpersonalizzato * 0.22;
            $ivatot += $iva;
            $totale += $pr;
            $pdf->Cell(27,6,number_format($iva,2,",","."),1,0,'R'); //prezzo inclusivo di iva
            $pdf->Ln();            
        }
        // rigo altri servizi
        if ($reservation->prezzoaltriservizi > 0){
            $pdf->Cell(82,6,utf8_decode("Altri servizi: ".$reservation->altriservizi),1);
            $pdf->Cell(27,6,1,1,0,'R');
            $pdf->Cell(27,6,number_format($reservation->prezzoaltriservizi,2,",","."),1,0,'R');
            $pdf->Cell(27,6,number_format($reservation->prezzoaltriservizi,2,",","."),1,0,'R'); //prezzo = quantita * prezzofascia
            $pr = $reservation->prezzoaltriservizi;
            $iva = $reservation->prezzoaltriservizi * 0.22;
            $ivatot += $iva;
            $totale += $pr;
            $pdf->Cell(27,6,number_format($iva,2,",","."),1,0,'R'); //prezzo inclusivo di iva
            $pdf->Ln();            
        }
        // rigo del totale
        $pdf->SetFillColor(220, 158, 5); // giallo
        
        $pdf->Cell(136,6,'TOTALI',1,0,'L');
        $pdf->Cell(27,6,number_format($totale,2,",","."),1,0,'R'); //prezzo TOTALE inclusivo di iva
        $pdf->Cell(27,6,number_format($ivatot,2,",","."),1,0,'R'); //prezzo TOTALE inclusivo di iva
        $pdf->Ln();

        $pdf->Cell(136,6,'COSTO TOTALE (Iva inclusa)',1,0,'L');
        $pdf->Cell(54,6,number_format($totale + $totale * 0.22,2,",","."),1,0,'C'); //prezzo TOTALE inclusivo di iva
        $pdf->Ln();

        if($reservation->prezzofinale != $totale && $reservation->prezzofinale > 0){
            // rigo del totale scontato
            $pdf->Cell(136,6,'COSTO TOTALE SCONTATO',1,0,'L');
            $pdf->Cell(54,6,number_format($reservation->prezzofinale + $reservation->prezzofinale * 0.22,2,",","."),1,0,'C'); //prezzo TOTALE scontato inclusivo di iva
            $pdf->Ln();

            if($reservation->stato >= 2){
                // rigo del totale meno il costo fisso
                $pdf->SetFillColor(219, 84, 6); // arancio chiaro        
                $pdf->Cell(136,6,'COSTO TOTALE DA CORRISPONDERE (meno anticipo iscrizione gia versato)',1,0,'L');
                $pdf->Cell(54,6,number_format(($reservation->prezzofinale + $reservation->prezzofinale * 0.22)-$costifissi,2,",","."),'LTR',0,'C'); //prezzo TOTALE meno anticipo
                $pdf->Ln();
            }
        }
        else{
            if($reservation->stato >= 2){
                // rigo del totale meno il costo fisso
                $pdf->SetFillColor(219, 84, 6); // arancio chiaro        
                $pdf->Cell(163,6,'COSTO TOTALE DA CORRISPONDERE (meno anticipo iscrizione gia versato)',1,0,'L');
                $pdf->Cell(27,6,"EURO ".number_format(($totale + $totale * 0.22)-$costifissi,2,",","."),'LTR',0,'R'); //prezzo TOTALE meno anticipo
                $pdf->Ln();
            }
        }
        
        // note spazio espositivo personalizzato
        $pdf->Cell(82,18,'DESCRIZIONE SPAZIO E NOTE',1,0,'L');
        $x = $pdf->GetX();
        $pdf->Cell(108,18,'',1,0,'L');
        $pdf->SetFont('Times','',8);
        $pdf->SetX($x);
        $pdf->MultiCell(108,6,htmlspecialchars_decode(utf8_decode($reservation->standpersonalizzato),ENT_QUOTES),0,'L',false);
        $pdf->Ln();

        // note condivise con l'espositore
        if($reservation->notecondivise !=''){
            $pdf->Ln();
            $pdf->SetFont('Times','',12);
            $pdf->MultiCell(0,6,htmlspecialchars_decode(utf8_decode("ALTRE NOTE: ".$reservation->notecondivise),ENT_QUOTES),0,'L',false);
            $pdf->Ln();
        }

        // data e firma
        $pdf->SetFont('Times','',12);
        $pdf->Cell(136,6,'',0,0,'L');
        $pdf->Cell(54,6,'',0,0,'L');
        $pdf->Ln();        
        $pdf->Cell(136,6,"Luogo e data",0,0,'L');
        $pdf->Cell(54,6,utf8_decode("Firma"),0,0,'L');
        $pdf->Ln();

        // pagina nuova per tutte le clausole e accettazioni:
        $pdf->SetTopMargin(25);
        $pdf->AddPage();
        $pdf->SetFont('Times','',8);
        $pdf->Write(4,utf8_decode("- L'espositore dichiara che i prodotti, i servizi e i progetti presentati in fiera sono conformi ai criteri generali e specifici della sezione tematica di appartenenza dettagliati nel documento \"Criteri di Ammissione\" e dalla \"Carta Etica\"."));
        $pdf->Ln();
        $pdf->Write(4,utf8_decode("- L'espositore dichiara di accettare e di impegnarsi a rispettare scrupolosamente quanto disposto e dal Regolamento per gli espositori e dal Regolamento Tecnico di Quartiere."));
        $pdf->Ln();
        $pdf->Write(4,utf8_decode("- L'espositore dichiara e garantisce che durante Fa' la cosa giusta! Umbria saranno osservate le vigenti disposizioni in materia di sicurezza sul lavoro ed in particolare quanto previsto dal Dlgs 81/2008 e successive modifiche e integrazioni."));
        $pdf->Ln();
        $pdf->Write(4,utf8_decode("- L'espositore dichiara di condividere, accettare e rispettare i principi enunciati nella carta etica della manifestazione."));
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Times','B',8);
        $pdf->Write(4,utf8_decode("L'espositore dichiara che (indicare con una X l'ipotesi che interessa):"));
        $pdf->SetFont('Times','',8);
        $pdf->Ln();

        $pdf->Write(4,utf8_decode("  Durante Fa' la cosa giusta! Umbria NON saranno utilizzati allestimenti propri in aggiunta a quelli forniti dall'Organizzazione"));
        $pdf->Ln();
        $pdf->Write(4,utf8_decode("  Durante Fa' la cosa giusta! Umbria SARANNO UTILIZZATI allestimenti propri in aggiunta a quelli forniti dall'Organizzazione e 	che tali rispettano le norme antinfortunistiche e di prevenzione antincendio. (Allegare certificazioni)"));
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Times','B',8);
        $pdf->Write(4,utf8_decode("L'espositore dichiara che (indicare con una X l'ipotesi che interessa):"));
        $pdf->SetFont('Times','',8);
        $pdf->Ln();
        $pdf->Write(4,utf8_decode("  Durante Fa' la cosa giusta! Umbria NON saranno presenti macchinari e apparecchiature in funzione;"));
        $pdf->Ln();
        $pdf->Write(4,utf8_decode("  Durante Fa' la cosa giusta! Umbria SARANNO PRESENTI macchinari e apparecchiature in funzione, (non rientrano in questa classificazione apparecchiature quali ad. Es. registratori di cassa, PC, proiettori, monitor) quali __________________________"));
        $pdf->Ln();
        $pdf->Write(4,utf8_decode("e che gli stessi non costituiscono  pericolo o molestia per gli espositori e per i visitatori, essendo dotati di tutti i dispositivi atti a 	prevenire infortuni, cattivi odori, emissione di gas e di liquidi; che sarà evitato il manifestarsi di rischi potenziali per i visitatori e/o non addetti ai lavori durante il funzionamento dei macchinari e delle apparecchiature."));
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Write(4,utf8_decode("Data e luogo __________________________________     Firma per accettazione __________________________________"));
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Times','B',8);
        $pdf->Write(4,utf8_decode("L'espositore dichiara:"));
        $pdf->SetFont('Times','',8);
        $pdf->Ln();
        $pdf->Write(4,utf8_decode("di aver letto attentamente e di aver compilato correttamente il presente modulo;\ndi sollevare l'Organizzatore da qualsiasi responsabilità derivante dall'inosservanza delle norme riportate e delle dichiarazioni qui rese."));
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Write(4,utf8_decode("Data e luogo __________________________________     Firma per accettazione __________________________________"));
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Times','B',8);
        $pdf->Write(4,utf8_decode("Tutela della privacy. "));
        $pdf->SetFont('Times','',8);
        $pdf->Write(4,utf8_decode("Dopo aver letto la \"nota informativa\" allegata al documento \"regolamento degli espositori\" e l'art. 20 dello stesso, accetto quanto in esso prescritto e contenuto"));
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Write(4,utf8_decode("Data e luogo __________________________________     Firma per accettazione __________________________________"));
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Write(4,utf8_decode("    Autorizzo                   Non autorizzo"));
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Write(4,utf8_decode("Fair Lab srls al trattamento dei miei dati esclusivamente per le finalità correlate alla prestazione del servizio e per l'invio di materiale informativo, secondo quanto previsto dall'art. 13 del D.Lgs n. 196 del 30.06.2003. N.B. La mancata accettazione del trattamento dei dati implica l'impossibilità di adesione."));
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Write(4,utf8_decode("Data e luogo __________________________________     Firma per accettazione __________________________________"));
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Times','B',8);
        $pdf->Write(4,utf8_decode("Cogliamo l'occasione per ricordare quanto già pattuito verbalmente, ovvero l'obbligo tassativo di rispetto, a pena di esclusione dalla fiera, di alcune norme previste per tutti gli espositori della fiera:"));
        $pdf->Ln();
        $pdf->SetFont('Times','',8);
        $pdf->Write(4,utf8_decode("- Autorizzazione alla somministrazione e vendita esclusivamente dei prodotti da Lei indicati nel modulo di ammissione ed approvati dall'Organizzazione;\n"));
        $pdf->Write(4,utf8_decode("- Divieto assoluto di vendita di acqua in bottiglia, di bibite o bevande di marchi o società multinazionali (ad esempio, a titolo esemplificativo, ma non esaustivo: Coca Cola - Estathè - Fanta - Sprite - Heineken - Ceres etc);\n"));
        $pdf->Write(4,utf8_decode("- Tutti gli espositori presenti in fiera sono tenuti ad utilizzare stoviglie, posate, bicchieri e shopper in materiale biodegradabile e compostabile, conforme agli standard europei EN 13432. In particolare Le ricordiamo che la vigente normativa nazionale vieta l'uso di buste in plastica monouso e, dal 21 agosto 2014, prevede l'applicazione di sanzioni per le eventuali violazioni (per ulteriori informazioni visiti il sito www.assobioplastiche.it).\n"));
        $pdf->Write(4,utf8_decode("- E' facoltà dell'organizzazione effettuare controlli per la verifica del rispetto degli impegni assunti dagli espositori o concordati espressamente con l'organizzazione, in relazione ai prodotti posti in vendita o somministrati. In caso vendita o somministrazione di prodotti non elencati nel modulo di ammissione è facoltà dell'Organizzazione disporre l'esclusione dell'espositore dalla manifestazione, senza diritto di questi alla restituzione della quota di ammissione.\n"));
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Write(4,utf8_decode("Data e luogo __________________________________     Firma per accettazione __________________________________"));

        // aggiungiamo gli assets grafici dei quadratini
        $pdf->Image('img/quadrato.png',9,82,2);
        $pdf->Image('img/quadrato.png',9,86,2);
        $pdf->Image('img/quadrato.png',9,102,2);
        $pdf->Image('img/quadrato.png',9,106,2);
        // autorizzo e non autorizzo
        $pdf->Image('img/quadrato.png',11,182,2);
        $pdf->Image('img/quadrato.png',35,182,2);

        return $pdf;


    }    

    public function excelgenAction()
    {
        $separasheets = $this->request->getPost('separasheets', 'int');
        if($separasheets == 1){
            $this->excelmultisheetgen();
        }
        else{
            $this->excelsinglesheetgen();
        }
    }

    public function excelmultisheetgen(){
        
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        $this->response->resetHeaders();
        $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response->setHeader('Content-Disposition', 'attachment; filename=catalogoespositori.xlsx');

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
            $this->persistent->searchParams = $par;
        }

        if ($this->persistent->searchParams && count($this->persistent->searchParams)) {
            $parameters = $this->persistent->searchParams;
        }

        // elenco servizi esistenti per intestazione colonne
        $elencoservizi = Services::find("events_id = ".$this->evento->id." AND id != 1 ORDER BY tipologia");

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
            'stato pagamento',
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
            'prezzo finale'
        );

        $nomicolonne = array_merge($nomicolonne,$nomiservizi);

        $spreadsheet = new Spreadsheet();
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

        foreach ($areetematiche as $area){ 
            if(!empty($areas_id) && $area->id != $areas_id){
                continue;
            }  

            $builder = $this->modelsManager->createBuilder()
            ->from('Reservations')
            ->join('Exhibitors')
            ->where("events_id = ".$this->evento->id)
            ->andWhere("areas_id = ".$area->id);
    
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
    
            $reservations = null;
            $reservations = $builder->getQuery()->execute();
            $this->view->laquery = $builder->getQuery()->getSql();

            if(count($reservations) > 0){

                $escaper = new \Phalcon\Escaper();
                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $area->nome); 
                $spreadsheet->addSheet($myWorkSheet, 0);
                $sheet = $spreadsheet->setActiveSheetIndex(0);
                $sheet->fromArray($nomicolonne,Null,'A1');             
                $contatorerighe=2;
                foreach($reservations as $domandaespositore){
                    $sa = array();
                    $serviziacquistati = array();
                    $reservationservices = ReservationServices::find("reservations_id = ".$domandaespositore->id." AND services_id != 1");
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
                        htmlspecialchars_decode($domandaespositore->areas->nome,ENT_QUOTES), 
                        $domandaespositore->interventoprogrammaculturale ? "si" : "no",
                        htmlspecialchars_decode($domandaespositore->standpersonalizzato,ENT_QUOTES), 
                        $domandaespositore->stati->descrizionebreve,
                        htmlspecialchars_decode($domandaespositore->exhibitors->indirizzo,ENT_QUOTES), 
                        $domandaespositore->exhibitors->cap,
                        htmlspecialchars_decode($domandaespositore->exhibitors->citta,ENT_QUOTES), 
                        htmlspecialchars_decode($domandaespositore->exhibitors->provincia,ENT_QUOTES), 
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
                        $domandaespositore->prezzofinale,
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


    /**
     * genera il file pdf della "Lettera di ammissione"
     *
     * @param string $id
     */
    public function facsimilefatturaAction($id){

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

        $string = strtolower($reservation->exhibitors->ragionesociale);
        $string = preg_replace("/[^0-9A-Za-z ]/", "", $string);
        $string = str_replace(" ", "-", $string);
        while (strstr($string, "--")) {
            $string = preg_replace("/--/", "-", $string);
        }
        $permalink = (utf8_decode($string));
        
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        $this->response->resetHeaders();   
        $this->response->setHeader('Content-Type', 'application/octet-stream');
        $this->response->setHeader('Content-Disposition', "attachment; filename=fac-simile-fattura-{$permalink}.txt");

        // titolo evento
        echo($this->evento->descrizione." - ");
        echo("Dati per la fattura\r\n");

        $datifatturazione["Ragione Sociale"] = utf8_decode($reservation->exhibitors->ragionesociale);
        $datifatturazione["Indirizzo"] = utf8_decode($reservation->exhibitors->indirizzo." - ".$reservation->exhibitors->cap." - ".ucfirst($reservation->exhibitors->citta)." (".$reservation->exhibitors->provincia.")");
        echo "RAGIONE SOCIALE: ".$datifatturazione["Ragione Sociale"]."\r\n";
        echo "INDIRIZZO: ".$datifatturazione["Indirizzo"]."\r\n";
        echo "TELEFONO: ".$reservation->exhibitors->telefono."\r\n";
        echo "EMAIL AZIENDA: ".$reservation->exhibitors->emailaziendale."\r\n";
        echo "PARTITA IVA: ".$reservation->exhibitors->piva."\r\n";
        echo "PEC: ".$reservation->exhibitors->pec."\r\n";
        echo "CODICE SDI: ".$reservation->exhibitors->codicesdi."\r\n";
        echo "NOME REFERENTE: ".$reservation->exhibitors->referentenome."\r\n";
        echo "TELEFONO REFERENTE: ".$reservation->exhibitors->referentetelefono."\r\n";
        echo "EMAIL REFERENTE: ".$reservation->exhibitors->referenteemail."\r\n";
        echo "FASCIA DI PREZZO: ".$reservation->exhibitors->fasciadiprezzo."\r\n";
        echo "NUMERO FATTURA: ".$reservation->numerofattura."\r\n";

        echo "--------------------------------------------------------------\r\n";
        echo("RIGHI FATTURA\r\n");
        echo "--------------------------------------------------------------\rn";

        $reservationservices = ReservationServices::find("reservations_id = ".$reservation->id);
        $fieldprezzo = 'prezzofasciaa';
        switch($reservation->exhibitors->fasciadiprezzo){
            case 'b':
                $fieldprezzo = 'prezzofasciab';
            break;
            default:
                $fieldprezzo = 'prezzofasciaa';
            break;
        }

        // servizi acquistati, quantità e prezzo
        echo("DESCRIZIONE\t");
        echo("QUANTITA\t");
        echo("COSTO UNITARIO\t");
        echo("COSTO\t"); //prezzo = quantita * prezzofascia
        echo(utf8_decode("COSTO+IVA\r\n")); //prezzo inclusivo di iva
        echo "--------------------------------------------------------------\r\n";
        
        $totale = 0;
        $costifissi = 200;
        foreach($reservationservices as $reservationservice){
            if($reservationservice->services->descrizione == "Costo fisso di iscrizione") $costifissi = $reservationservice->services->$fieldprezzo;
            echo($reservationservice->services->descrizione."\t");
            echo("X".$reservationservice->quantita."\t");
            echo(number_format($reservationservice->services->$fieldprezzo,2,",",".")."\t");
            echo(number_format($reservationservice->quantita * $reservationservice->services->$fieldprezzo,2,",",".")."\t"); //prezzo = quantita * prezzofascia
            $pr = $reservationservice->quantita * $reservationservice->services->$fieldprezzo;
            $iva = $reservationservice->quantita * $reservationservice->services->$fieldprezzo * 0.22;
            $totale += $pr;
            echo(number_format($pr + $iva,2,",",".")."\r\n"); //prezzo inclusivo di iva
            echo "--------------------------------------------------------------\r\n";
        }
        
        // rigo stand personalizzato
        if ($reservation->prezzostandpersonalizzato > 0){
            echo("Stand personalizzato\t1\t");
            echo(number_format($reservation->prezzostandpersonalizzato,2,",",".")."\t");
            echo(number_format($reservation->prezzostandpersonalizzato,2,",",".")."\t"); //prezzo = quantita * prezzofascia
            $pr = $reservation->prezzostandpersonalizzato;
            $iva = $reservation->prezzostandpersonalizzato * 0.22;
            $totale += $pr;
            echo(number_format($pr + $iva,2,",",".")."\r\n"); //prezzo inclusivo di iva
            echo "--------------------------------------------------------------\r\n";
        }
        // rigo altri servizi
        if ($reservation->prezzoaltriservizi > 0){
            echo(utf8_decode("Altri servizi: ".$reservation->altriservizi)."\t\t");
            echo(number_format($reservation->prezzoaltriservizi,2,",",".")."\t");
            echo(number_format($reservation->prezzoaltriservizi,2,",",".")."\t"); //prezzo = quantita * prezzofascia
            $pr = $reservation->prezzoaltriservizi;
            $iva = $reservation->prezzoaltriservizi * 0.22;
            $totale += $pr;
            echo(number_format($pr + $iva,2,",",".")."\r\n"); //prezzo inclusivo di iva
            echo "--------------------------------------------------------------\r\n";
        }

        // rigo del totale        
        echo('COSTO TOTALE DA LISTINO: ');
        echo("EURO ".number_format($totale + $totale * 0.22,2,",",".")."\r\n"); //prezzo TOTALE inclusivo di iva
        echo "--------------------------------------------------------------\r\n";

        if($reservation->prezzofinale > 0 && $reservation->prezzofinale < $totale){
            echo('COSTO TOTALE SCONTATO: ');
            echo("EURO ".number_format($reservation->prezzofinale + $reservation->prezzofinale * 0.22,2,",",".")."\r\n"); //prezzo TOTALE inclusivo di iva
            echo "--------------------------------------------------------------\r\n";
            
        // rigo del totale scontato meno il costo fisso
        echo('COSTO TOTALE SCONTATO DA CORRISPONDERE (MENO ANTICIPO): ');
        echo("EURO ".number_format(($reservation->prezzofinale + $reservation->prezzofinale * 0.22)-$costifissi,2,",",".")."\r\n"); //prezzo TOTALE meno anticipo
        echo "--------------------------------------------------------------\r\n";
        }
        else{
        // rigo del totale meno il costo fisso
        echo('COSTO TOTALE DA CORRISPONDERE (MENO ANTICIPO): ');
        echo("EURO ".number_format(($totale + $totale * 0.22)-$costifissi,2,",",".")."\r\n"); //prezzo TOTALE meno anticipo
        echo "--------------------------------------------------------------\r\n";
        }


    }    

    /**
     * genera dati catalogo
     *
     * @param string $id
     */
    public function daticatalogoAction($id){

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

        $string = strtolower($reservation->exhibitors->ragionesociale);
        $string = preg_replace("/[^0-9A-Za-z ]/", "", $string);
        $string = str_replace(" ", "-", $string);
        while (strstr($string, "--")) {
            $string = preg_replace("/--/", "-", $string);
        }
        $permalink = (utf8_decode($string));
        
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        $this->response->resetHeaders();   
        $this->response->setHeader('Content-Type', 'application/octet-stream');
        $this->response->setHeader('Content-Disposition', "attachment; filename=dati-catalogo-{$permalink}.txt");

        // titolo evento
        echo($this->evento->descrizione." - ");
        echo("Dati per il  catalogo\n\n");

        $datifatturazione["catalogonome"] = utf8_decode($reservation->exhibitors->catalogonome);
        $datifatturazione["Indirizzo"] = utf8_decode($reservation->exhibitors->catalogoindirizzo." - ".$reservation->exhibitors->catalogocap." - ".ucfirst($reservation->exhibitors->catalogocitta)." (".$reservation->exhibitors->catalogoprovincia.")");
        echo "NOME: ".$datifatturazione["catalogonome"]."\r\n";
        echo "INDIRIZZO: ".$datifatturazione["Indirizzo"]."\r\n";
        echo "TELEFONO: ".$reservation->exhibitors->catalogotelefono."\r\n";
        echo "EMAIL: ".$reservation->exhibitors->catalogoemail."\r\n";
        echo "SITO WEB: ".$reservation->exhibitors->catalogositoweb."\r\n";
        echo "PAGINA FACEBOOK: ".$reservation->exhibitors->catalogofacebook."\r\n";
        echo "PROFILO INSTAGRAM: ".$reservation->exhibitors->catalogoinstagram."\r\n";
        echo "PROFILO TWITTER: ".$reservation->exhibitors->catalogotwitter."\r\n";
        echo "DESCRIZIONE: ".$reservation->exhibitors->catalogodescrizione."\r\n";

    }
    
     /**
     * inserisce nota operatore sul diario/log
     *
     * 
     */
    public function scrivinotaAction(){

        if (!$this->request->isAjax()) {
            $ris["risposta"] = "Richiesta non valida";
            $ris["status"] = "KO";
            return $this->response->setJsonContent($ris);
        }
        
        $auth = $this->session->get('auth');

        $logstatireservations = new LogStatiReservations();
        $logstatireservations->reservations_id = $this->request->getPost("reservations_id","int");
        $logstatireservations->stati_id = $this->request->getPost("stati_id","int");
        $logstatireservations->users_id = $auth['id'];
        $logstatireservations->dataora = date("Y-m-d H:i:s");
        $logstatireservations->messaggio = $this->request->getPost("messaggio","string");
        if ($logstatireservations->save() === false) {

            $i=0;
            foreach ($logstatireservations->getMessages() as $message) {
                \PhalconDebug::info("errore: ".$message);
                $ris['risposta'] .= ' '.$message;
                $i++;
            }
            $ris["status"] = "KO";
            return $this->response->setJsonContent($ris);
        }
        else{
            $ris["status"] = "OK";
            $ris['risposta'] .= "La nota è stata inserito nel diario";
        }
        return $this->response->setJsonContent($ris);

    }        

    public function excelsinglesheetgen()
    {
        
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        $this->response->resetHeaders();
        $this->response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->response->setHeader('Content-Disposition', 'attachment; filename=catalogoespositori.xlsx');

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
            $this->persistent->searchParams = $par;
        }

        if ($this->persistent->searchParams && count($this->persistent->searchParams)) {
            $parameters = $this->persistent->searchParams;
        }

        // elenco servizi esistenti per intestazione colonne
        $elencoservizi = Services::find("events_id = ".$this->evento->id." AND id != 1 ORDER BY tipologia");

        $nomiservizi = array();
        foreach($elencoservizi as $nomeservizio){
            $nomiservizi[] = $nomeservizio->descrizione;
        }

        $nomicolonne = array(
            'ragione sociale', 
            'area tematica',
            'intervento programma culturale',
            'richiesta stand personalizzato',
            'stato pagamento',
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
            'prezzo finale'
        );

        $nomicolonne = array_merge($nomicolonne,$nomiservizi);

        $spreadsheet = new Spreadsheet();
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx"); 

            $builder = $this->modelsManager->createBuilder()
            ->from('Reservations')
            ->join('Exhibitors')
            ->where("events_id = ".$this->evento->id);
    
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
    
            $reservations = null;
            $reservations = $builder->getQuery()->execute();
            $this->view->laquery = $builder->getQuery()->getSql();

            if(count($reservations) > 0){
                
                $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'espositori'); 
                $spreadsheet->addSheet($myWorkSheet, 0);
                $sheet = $spreadsheet->setActiveSheetIndex(0);
                $sheet->fromArray($nomicolonne,Null,'A1');             
                $contatorerighe=2;
                foreach($reservations as $domandaespositore){
                    $sa = array();
                    $serviziacquistati = array();
                    $reservationservices = ReservationServices::find("reservations_id = ".$domandaespositore->id." AND services_id != 1");
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
                        htmlspecialchars_decode($domandaespositore->areas->nome,ENT_QUOTES), 
                        $domandaespositore->interventoprogrammaculturale ? "si" : "no",
                        htmlspecialchars_decode($domandaespositore->standpersonalizzato,ENT_QUOTES), 
                        $domandaespositore->stati->descrizionebreve,
                        htmlspecialchars_decode($domandaespositore->exhibitors->indirizzo,ENT_QUOTES), 
                        $domandaespositore->exhibitors->cap,
                        htmlspecialchars_decode($domandaespositore->exhibitors->citta,ENT_QUOTES), 
                        htmlspecialchars_decode($domandaespositore->exhibitors->provincia,ENT_QUOTES), 
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
                        $domandaespositore->prezzofinale,
                    );
                    $righe = array_merge($righe,$sa);
                    $sheet->fromArray( $righe, NULL, 'A'.$contatorerighe );  
                    $contatorerighe++;
                }
            }

        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Worksheet')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);
        $writer->save('php://output');

    }
   
}

