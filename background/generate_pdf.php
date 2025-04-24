<?php
require 'vendor/autoload.php'; // Dompdf or use Composer autoload

use Dompdf\Dompdf;
use Dompdf\Options;

$imgPath = 'http://localhost/ticketing/background/';


$html = '<!DOCTYPE html>
<html lang="en">
<head>
   
    <meta charset="UTF-8">
  <meta name="viewport" content="width=1140">
  <title>Match Ticket</title>
  
  <style type="text/css">
* {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: lightgoldenrodyellow;
      color: #fff;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .ticket-container {
      background: #fff;
      width: 100%;
      max-width: 900px;
      /* padding: 20px; */
      border-radius: 10px;
    }

    .head-text {
      text-align: center;
    
      margin-bottom: 20px;
    }

    .head-text p {
      font-weight: bold;
      background-color: red;
      padding: 10px 0px 10px 0px;
      
    }

    .qr-code {
      display: block;
      margin: 10px auto;
      max-width: 150px;
    }

    .wrapper {
      max-width: 800px;
      margin: 0 auto;
    }

    .ticket-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .ticket-header img {
      height: 60px;
    }

    .ticket-header h2 {
      margin-top: 5px;
      font-weight: bold;
      display: flex;
      align-items: center;
    }

    .ticket-header span {
      transform: rotate(-89deg);
      font-size: 13px;
      margin: 0 10px;
      color: red;
    }

    .print-label {
      font-weight: bold;
      margin-bottom: 20px;
      color: black;
    }

    .details-box {
      background: #cc0000;
      padding: 20px;
      /* border-radius: 8px; */
    }

    .details-heading {
      text-align: center;
      background-color: #c00;
      padding: 10px;
      border-radius: 6px;
    }

    .details-heading h4,
    .details-heading p {
      color: white;
      font-weight: bold;
    }
    .match-info-main {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 10px;
  background-color: #fff;
  padding: 18px;
  margin-top: 20px;
  color: black;
}

.match-info-main > div:nth-child(1) {
  grid-column: span 2; /* Supporter ID */
  margin-left: 35px;
  justify-self: start; /* Align content to the left */
}
.match-info-main > div:nth-child(2) {
  grid-column: span 1; /* Name */
}
.match-info-main > div:nth-child(3) {
  grid-column: span 4; /* Price Class on its own row */
  justify-self: start; /* Align content to the left */
  margin-left: 36px;
}
.match-info-main > div:nth-child(4),
.match-info-main > div:nth-child(5),
.match-info-main > div:nth-child(6),
.match-info-main > div:nth-child(7) {
  grid-column: span 1; /* Entrance, Block, Row, Seat */
}


    .match-info-main div {
      font-size: 15px;
    }

    .map-section img {
      width: 100%;
      max-height: 300px;
      object-fit: cover;
      margin-top: 20px;
      /* border-radius: 8px; */
    }

    .footer-note {
      /* margin-top: 20px; */
      font-size: 14px;
      line-height: 1.4;
    }
  </style>
</head>
<body class="bg-light">
<div class="ticket-container">
    <div class="head-text">
      <p>Once printed, please scan this QR code at the turnstile reader to gain entry</p>
      <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" class="qr-code" alt="QR Code">
    </div>

    <div class="wrapper">
      <div class="ticket-header">
        <div class="match-ticket-contents" style="display: flex;">
          <img src="https://upload.wikimedia.org/wikipedia/en/7/7a/Manchester_United_FC_crest.svg" alt="MUFC Logo">
          <h2 style="color: black; margin-left:10px; font-size:30px">
            MATCH TICKET
            <span>24/25</span>
          </h2>
        </div>
        <div class="premierlogo">
          <img src="images/Premier-League-Logo.jpg" alt="Premier League Logo" width="80">
        </div>
      </div>

      <h5 class="print-label">Print at Home</h5>

      <div class="details-box">
        <div class="details-heading">
          <h4>Manchester United vs Ipswich Town</h4>
          <p>Wednesday 26th February 2025 at 19:30</p>
      

        <div class="match-info-main" >
          <div><span>Supporter ID:</span> <strong>7591006</strong></div>
          <div><span>Name:</span></div>
          <div><span>Price Class:</span> <strong>Adult</strong></div>
          <div><span>Entrance:</span> <strong>N42</strong></div>
          <div><span>Block:</span> <strong>N3404</strong></div>
          <div><span>Row:</span> <strong>23</strong></div>
          <div><span>Seat:</span> <strong>147</strong></div>
        </div>
        </div>
      </div>

      <div class="map-section">
        <img src="images/map.PNG" alt="Map">
      </div>

      <div class="footer-note" style="color: black !important; margin:10px; padding:8px;">
        <p>Each ticket has a single scan QR code admitting one entry to Old Trafford for the game <br> purchased and unauthorised duplication of any ticket will prevent entry into the stadium tickets.ma</p>
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
