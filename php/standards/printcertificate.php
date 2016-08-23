<?php
/**
 * Ipswich JAFFA webpages
 * @author Gavin Davies <gavindavies@mypostoffice.co.uk>
 * @copyright Copyright &copy; 2009, Gavin Davies
 */
$name = $_GET['name'];
$standard = $_GET['standard'];
$event = $_GET['event'];
$date = $_GET['date'];
$time = $_GET['time'];
$filepath = $_GET['filepath'];

if (isset($name) && isset($standard ) && isset($event) && isset($date) && isset($time) && isset($filepath)) {

  // Print PDF certificate
  require_once('standardcertificate.class.php');
  $pdf = new StandardsCertificatePdf();
  $response = $pdf->printCertificate($name, $standard, $event, $date, $time, $filepath);
  
  // Post back the response as json
  //header('Content-type: application/pdf');
  //header('Content-length: ' . strlen($response));
         
}
?>