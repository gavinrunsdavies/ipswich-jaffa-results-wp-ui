<?php
/**
 * Ipswich JAFFA webpages
 * @author Gavin Davies <gavindavies@mypostoffice.co.uk>
 * @copyright Copyright &copy; 2009, Gavin Davies
 */
require_once('fpdf.php');


if (!class_exists("StandardsCertificatePdf")) {
  class StandardsCertificatePdf extends FPDF {
    
    // Class properties
    //var $standardSecretary = 'Gavin Davies';
    var $chairman = 'Alison Beech';
    
    var $title='Ipswich JAFFA Running Club Standards Certificate';
    var $documentAuthor = "Gavin Davies";
    
    // Class constructor
    function StandardsCertificatePdf() {
    
      // Call the base constructor
      parent::FPDF('L','mm','A4');

      // Set documebt properties
      $this->SetMargins(0, 0, 0);
      $this->SetTitle($this->title);
      $this->SetAuthor($this->documentAuthor);
            
      $this->SetDisplayMode('fullpage');
    } // end constructor


    function printCertificate($name, $standard, $event, $date, $time, $filepath) {   
        $this->AddPage('L','A4');
        
        $this->addBackgroundImage($filepath, substr($standard, 0 , 1));        
        $this->addCertificateBody($name, $standard, $event, $date, $time);           
        $this->addChairmanSignature($standard);
        //$this->addStandardsSecretarySignature($standard);
        
        $this->Output();
    }
    
    function addChairmanSignature() {    

      // Arial 12
      $this->SetFont('Arial','',11);
      $text = "Chair: " . $this->chairman . ", " . date('jS F Y') . " ";
      
      //Colors of frame, background and text
      $this->SetDrawColor(247,147,30);
      $this->SetFillColor(255,255,255);
      $this->SetTextColor(0,0,0);
      
      //Thickness of frame
      $this->SetLineWidth(0.5);
      
      // Add cell - width, height, text, border (y/n)...     
      // Value in mm
      $this->SetXY(73, 140);
      $this->Cell(155, 12, $text, 1, 1, 'R', true);
      
    } // end function addChairmanSignature
    
    function addStandardsSecretarySignature() {    

      // Arial 12
      $this->SetFont('Arial','',11);
      $text = "Standards secretary: " . $this->standardSecretary . ", " . date('jS F Y'). " ";
      
      //Colors of frame, background and text
      $this->SetDrawColor(247,147,30);
      $this->SetFillColor(255,255,255);
      $this->SetTextColor(0,0,0);
      
      //Thickness of frame
      $this->SetLineWidth(0.5);
      
      // Add cell - width, height, text, border (y/n)...     
      // Value in mm
      $this->SetXY(73, 154.2);
      $this->Cell(155, 12, $text, 1, 1, 'R', true);
      
    } // end function addStandardsSecretarySignature

    
    function addBackgroundImage($filepath, $number) {
        $this->SetXY(0, 0);
        $imagePath = $filepath . $number .'StarCertificate.jpg';

		if ($number > 0 && $number < 6) {
			$this->Image($imagePath, 0, 0, 297, 210);
		} else {
			// try 
			$imagePath = $filepath . 'GStarCertificate.jpg';
			$this->Image($imagePath, 0, 0, 297, 210);
		}
    } // end function addBackgroundImage
    
    function addCertificateBody($name, $standard, $event, $date, $time) {   
            
        $this->SetY(92);
       
        $this->SetFont('Arial','', 12);
        
        // Output centered text  
        // Name        
        $this->SetFont('','B', 16);
        $this->Cell(0,15, $name, 0, 0, 'C');        
        
        // Standard
        $this->SetFont('','', 12);
        $this->Ln(8);
        $text = 'has achieved a ' . $standard . ' Star Standard at the';
        $this->Cell(0,15, $text, 0, 0, 'C');   
        $this->Ln(10);     
        
        // event
        $this->SetFont('','B', 16);
        $this->Cell(0,15, $event, 0, 0, 'C'); 
               
        // date and time
        $this->SetFont('','',12);
        $this->Ln(8);
        $text = 'on ' . $date . ' in ' . $time;
        $this->Cell(0,15, $text, 0, 1, 'C');        
        
    } // end function addCertificateBody

  } // end class StandardsCertificatePdf
}
?>