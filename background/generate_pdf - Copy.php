<?php
require 'vendor/autoload.php'; // Dompdf or use Composer autoload

use Dompdf\Dompdf;
use Dompdf\Options;

$imgPath = 'http://localhost/a/a/a/harry/background/';


$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"> -->
    <meta charset="UTF-8">
  <meta name="viewport" content="width=1140">
  <title>Match Ticket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style type="text/css">
  *{  padding: 0px;
    margin: 0px;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
    /* background: #fff; */
  }
  .head-text p{
    background-color: #c00;
    padding:10px 0px 10px 0px;
  }
  
  .ticket-container {
    background: white;
    padding: 0px 20px 0px 20px ;
  }
  
  .ticket-container {
    border: 1px solid #ddd;
  }
  .qr-code {
    max-width: 150px;
    margin: 0 auto;
  }
  .details-box ,.details-heading {
    background: #c00 !important;
    border: none !important;
    padding: 15px;
    padding: 20px;
  }
  .match-info-main{
    background-color: white;
  }
  .details-box span {
    font-weight: bold;
  }
  .map-section img {
   width: 900px !important;
  }
  .footer-note {
    font-size: 14px;
  }
  </style>
</head>
<body class="bg-light">
    <div class="d-flex justify-content-center align-items-center min-vh-100 ">
    <div class="container ticket-container mx-auto px-0 " style="max-width: 800px;">
      <div class="text-center mb-3 head-text" >
        <p class=" fw-bold text-white">Once printed, please scan this QR code at the turnstile reader to gain entry</p>
        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" class="qr-code d-block" alt="QR Code">
      </div>
      <!-- main content area -->
    <div class="wrapper mx-auto" style="max-width: 750px;">
          <div class=" mb-2 d-flex justify-content-between">
            <div class="match-ticket-contents ms-4">
                <img src="https://upload.wikimedia.org/wikipedia/en/7/7a/Manchester_United_FC_crest.svg" alt="MUFC Logo" style="height: 60px;">
                <h2 class="fw-bold d-inline-flex mt-1">
                    MATCH TICKET 
                    <span class="text-danger fw-bold" style="display: inline-block; transform: rotate(-89deg); font-size: 13px; margin-right: 10px;  margin-left: 7px;">
                      24/25
                    </span>
                  </h2>   
            </div>
            <div class="premierlogo">
                <img src="'. $imgPath .'images/Premier-League-Logo.jpg" alt="" class="img-fluid" width="80">
            </div>
          </div>
          <h5 class=" fw-bold mb-3">Print at Home</h5>
          <!-- details box  -->
          <div class="details-box">
            <div class="details-heading text-center">
              <h4 class="fw-bold text-white" style="background-color: #c00;">Manchester United vs Ipswich Town</h4>
              <p class="mb-0 fw-bold text-white" style="background-color: #c00;">Wednesday 26th February 2025 at 19:30</p>
            </div>
          <div class="match-info-main p-4">
            <div class="row mb-2">
              <div class="col-md-4" ><span>Supporter ID:</span> <strong>7591006</strong> </div>
              <div class="col-md-8"><span>Name:</span> </div>
            </div>
            <div class="row mb-2">
              <div class="col-md-6"><span>Price Class:</span> <strong>Adult</strong> </div>
            </div>
            <div class="row mb-2">
              <div class="col-md-3"><span>Entrance:</span> <strong>N42</strong> </div>
              <div class="col-md-3"><span>Block:</span>  <strong>N3404</strong></div>
              <div class="col-md-3"><span>Row:</span>  <strong>23</strong></div>
              <div class="col-md-3"><span>Seat:</span> <strong>147</strong> </div>
            </div>
        </div>
      </div>
      <!-- map section -->
          <div class="map-section ">
            <img src="'. $imgPath .'images/map.PNG" alt="Map" class="img-fluid" style="max-height: 300px;">
          </div>
           <!-- footer -->
          <div class="mt-3 footer-note ms-3">
            <p>Each ticket has a single scan QR code admitting one entry to Old Trafford for the game <br> purchased and unauthorised duplication of any ticket will prevent entry into the stadium
              tickets.ma</p>
          </div>
    </div>
  </div>
</div>
</body>
</html>';

/*$html = file_get_contents('create_pdf.php');
echo $html; exit;*/

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Save to a folder (e.g., "pdfs/output.pdf")
$output = $dompdf->output();
$filename = 'pdfs/output.pdf';

// Make sure the directory exists
if (!file_exists('pdfs')) {
    mkdir('pdfs', 0777, true);
}

file_put_contents($filename, $output);

// Optional: return JSON response
echo json_encode(['status' => 'success', 'message' => 'PDF saved successfully.']);
