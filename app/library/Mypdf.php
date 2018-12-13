<?php
use Fpdf\Fpdf;

class Mypdf extends FPDF 
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('img/logoflcgu.png',10,6,30);
        // Arial bold 15
        $this->SetFont('Arial','B',12);
        // Move to the right
        $this->Cell(80);
        // Title
        //$this->Cell(30,10,'Lettera di ammissione',0,0,'C');
        $this->SetLineWidth(1.2);
        $this->SetDrawColor(199, 72, 54);
        $this->Line(45, 27, 200, 27);
        // Line break
        $this->Ln(20);
    }
    
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-20);
        $this->SetFont('Arial','B',9);
        // sito web
        $this->SetTextColor(199, 72, 54);
        $this->Cell(50,6,'www.falacosagiustaumbria.it',0,0,'R');
        $this->Ln();
        // linea rossa 
        $this->SetLineWidth(1.2);
        $this->SetDrawColor(199, 72, 54);
        $this->Line(5, 283, 59, 283);    
        
        // riferimenti in blu
        $this->SetY(-20);
        $this->SetX(63);
        $this->SetTextColor(21, 40, 96); // blu scuro
        $this->SetFont('Arial','B',8);
        $this->Cell(90,6,"Fa' la cosa giusta! Umbria | Organizzazione Fair Lab Srls",0,2,'L');
        $this->SetFont('Arial','',8);
        $this->Cell(90,5,"Via XIV Settembre, 73 - 06121 Perugia - Fax 075.37.21.786",0,2,'L');
        $this->Cell(110,5,"organizzazione@falacosagiustaumbria.it - amministrazione@falacosagiustaumbria.it",0,2,'L');
        $this->Ln();        

        // Page number
        //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

    function BasicTable($data)
    {
        foreach($data as $primacol => $secondacol)
        {
            $this->Cell(40,6,$primacol,1);
            $this->Cell(150,6,$secondacol,1);
            $this->Ln();
        }
    }

}