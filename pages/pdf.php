<?php
$conn = new mysqli("localhost", "root", "", "tickting");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$ticks_id = 48;
$imagePath = "";

// Query to get the image_path
$sql = "SELECT image_path FROM tickts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ticks_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $imagePath = $row["image_path"];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=1140">

    <title>Match Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>


</head>

<body>

    <div class="d-flex justify-content-center align-items-center min-vh-100 " id="ticketContent">
        <div class="container ticket-container mx-auto px-0 " style="max-width: 800px;">
            <div class="text-center mb-3 head-text">
                <p class=" fw-bold text-white mt-3">Once printed, please scan this QR code at the turnstile reader to gain entry</p>

                <div id="qrContainer" class="qr-code d-block"></div>
            </div>
            <!-- main content area -->
            <div class="wrapper mx-auto" style="max-width: 750px;">
                <div class=" mb-2 d-flex justify-content-between">
                    <div class="match-ticket-contents ms-4">
                        <!-- QR code will go here -->
                        <h2 class="fw-bold d-inline-flex mt-1">
                            MATCH TICKET
                            <span class="text-danger fw-bold"
                                style="display: inline-block; transform: rotate(-89deg); font-size: 13px; margin-right: 10px;  margin-left: 7px;">
                                24/25
                            </span>
                        </h2>
                    </div>

                    <div class="premierlogo">
                        <img src="./images/Premier-League-Logo.jpg" alt="" class="img-fluid" width="80">
                    </div>
                </div>
                <h5 class=" fw-bold mb-3">Print at Home</h5>
                <!-- details box  -->
                <div class="details-box">
                    <div class="details-heading text-center">
                        <h4 class="fw-bold text-white" style="background-color: #c00;" id="teams">Manchester United vs Ipswich Town</h4>
                        <p class="mb-0 fw-bold text-white" style="background-color: #c00;" id="date">Wednesday 26th February 2025 at 19:30</p>
                    </div>
                    <div class="match-info-main p-4">
                        <div class="row mb-2">
                            <div class="col-md-4"><span>Supporter ID:</span> <strong>7591006</strong> </div>
                            <div class="col-md-8"><span>Name:</span> </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6"><span>Price Class:</span> <strong>Adult</strong> </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3"><span>Entrance:</span> <strong id="entrance">N42</strong> </div>
                            <div class="col-md-3"><span>Block:</span> <strong id="block">N3404</strong></div>
                            <div class="col-md-3"><span>Row:</span> <strong id="row">23</strong></div>
                            <div class="col-md-3"><span>Seat:</span> <strong id="seat">147</strong> </div>
                        </div>
                    </div>
                </div>
                <!-- map section -->
                <div class="map-section ">
                    <img src="images/map.PNG" alt="Map" class="img-fluid" style="max-height: 300px;">
                </div>
                <!-- footer -->
                <div class="mt-3 footer-note ms-3">
                    <p>Each ticket has a single scan QR code admitting one entry to Old Trafford for the game <br> purchased and
                        unauthorised duplication of any ticket will prevent entry into the stadium
                        tickets.ma</p>
                </div>
            </div>
        </div>
    </div>


























    <input type="hidden" id="imagePath" value="<?php echo htmlspecialchars($imagePath); ?>">


    <canvas id="canvas" style="display:none;"></canvas>


    <pre style="display: none;" id="textOutput"></pre>


    <pre id="qrOutput" style="display: none;"></pre>

    <!-- Tesseract.js (OCR) -->
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@2.1.5/dist/tesseract.min.js"></script>

    <!-- jsQR (QR Reader) -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <!-- <script>
        const imagePath = document.getElementById('imagePath').value;
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const textOutput = document.getElementById('textOutput');
        const qrOutput = document.getElementById('qrOutput');
        const qrContainer = document.getElementById('qrContainer');

        // Extract text fields like Entrance, Block, etc.
        function extractFields(text) {
            const fields = {};

            // Clean up the text
            const lines = text.split('\n').map(line => line.trim().toLowerCase());

            // Teams and Date - same as before
            const teamMatch = text.match(/(Manchester\s+United\s+v\s+.+)/i);
            const dateMatch = text.match(/(\d{1,2}\/\d{1,2}\/\d{4}\s+\d{1,2}:\d{2})/);

            // Entrance
            const entranceLine = lines.find(line => line.includes('entrance'));
            const entranceIndex = lines.indexOf(entranceLine);
            const entrance = entranceIndex !== -1 ? lines[entranceIndex + 1] : '';

            // Try to extract row and seat from the line that has both numbers
            let row = '',
                seat = '';
            for (let line of lines) {
                if (/\brow\b.*\bseat\b/i.test(line)) {
                    const idx = lines.indexOf(line);
                    const nextLine = lines[idx + 1];
                    const numbers = nextLine?.match(/\d+/g);
                    if (numbers && numbers.length >= 2) {
                        row = numbers[0];
                        seat = numbers[1];
                        break;
                    }
                }
            }

            // Block from section line if available
            let block = '';
            const blockLine = lines.find(line => line.includes('section'));
            if (blockLine) {
                const blockIndex = lines.indexOf(blockLine);
                block = lines[blockIndex + 1] || '';
            }

            fields.teams = teamMatch ? teamMatch[1].trim() : '';
            fields.date = dateMatch ? dateMatch[1].trim() : '';
            fields.entrance = entrance.toUpperCase();
            fields.block = block.toUpperCase();
            fields.row = row;
            fields.seat = seat;

            return fields;
        }


        if (imagePath) {
            const img = new Image();
            img.crossOrigin = 'Anonymous';
            img.onload = () => {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);

                // OCR: Extract Text
                Tesseract.recognize(canvas, 'eng')
                    .then(({
                        data: {
                            text
                        }
                    }) => {
                        textOutput.textContent = text;

                        const fields = extractFields(text);

                        // Populate elements by ID
                        document.getElementById('teams').textContent = fields.teams;
                        document.getElementById('date').textContent = fields.date;
                        document.getElementById('entrance').textContent = fields.entrance;
                        document.getElementById('block').textContent = fields.block;
                        document.getElementById('row').textContent = fields.row;
                        document.getElementById('seat').textContent = fields.seat;
                    });

                // QR Code: Scan
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, canvas.width, canvas.height);
                if (code) {
                    qrOutput.textContent = code.data;
                    qrContainer.innerHTML = '';
                    new QRCode(qrContainer, {
                        text: code.data,
                        width: 150,
                        height: 150
                    });
                } else {
                    qrOutput.textContent = "No QR code detected.";
                }
            };

            img.src = imagePath;
        }
    </script> -->

    <script>
        const imagePath = document.getElementById('imagePath').value;
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const textOutput = document.getElementById('textOutput');
        const qrOutput = document.getElementById('qrOutput');
        const qrContainer = document.getElementById('qrContainer');

        // Function to check if all fields are populated
        function isDataFullyPopulated() {
            const teams = document.getElementById('teams').textContent.trim();
            const date = document.getElementById('date').textContent.trim();
            const entrance = document.getElementById('entrance').textContent.trim();
            const block = document.getElementById('block').textContent.trim();
            const row = document.getElementById('row').textContent.trim();
            const seat = document.getElementById('seat').textContent.trim();

            return teams && date && entrance && block && row && seat; // All must be non-empty
        }

        // Extract text fields like Entrance, Block, etc.
        function extractFields(text) {
            const fields = {};

            // Clean up the text
            const lines = text.split('\n').map(line => line.trim().toLowerCase());

            // Teams and Date - same as before
            const teamMatch = text.match(/(Manchester\s+United\s+v\s+.+)/i);
            const dateMatch = text.match(/(\d{1,2}\/\d{1,2}\/\d{4}\s+\d{1,2}:\d{2})/);

            // Entrance
            const entranceLine = lines.find(line => line.includes('entrance'));
            const entranceIndex = lines.indexOf(entranceLine);
            const entrance = entranceIndex !== -1 ? lines[entranceIndex + 1] : '';

            // Try to extract row and seat from the line that has both numbers
            let row = '',
                seat = '';
            for (let line of lines) {
                if (/\brow\b.*\bseat\b/i.test(line)) {
                    const idx = lines.indexOf(line);
                    const nextLine = lines[idx + 1];
                    const numbers = nextLine?.match(/\d+/g);
                    if (numbers && numbers.length >= 2) {
                        row = numbers[0];
                        seat = numbers[1];
                        break;
                    }
                }
            }

            // Block from section line if available
            let block = '';
            const blockLine = lines.find(line => line.includes('section'));
            if (blockLine) {
                const blockIndex = lines.indexOf(blockLine);
                block = lines[blockIndex + 1] || '';
            }

            fields.teams = teamMatch ? teamMatch[1].trim() : '';
            fields.date = dateMatch ? dateMatch[1].trim() : '';
            fields.entrance = entrance.toUpperCase();
            fields.block = block.toUpperCase();
            fields.row = row;
            fields.seat = seat;

            return fields;
        }

        if (imagePath) {
            const img = new Image();
            img.crossOrigin = 'Anonymous';
            img.onload = () => {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);

                // OCR: Extract Text
                Tesseract.recognize(canvas, 'eng')
                    .then(({
                        data: {
                            text
                        }
                    }) => {
                        textOutput.textContent = text;

                        const fields = extractFields(text);

                        // Populate elements by ID
                        document.getElementById('teams').textContent = fields.teams;
                        document.getElementById('date').textContent = fields.date;
                        document.getElementById('entrance').textContent = fields.entrance;
                        document.getElementById('block').textContent = fields.block;
                        document.getElementById('row').textContent = fields.row;
                        document.getElementById('seat').textContent = fields.seat;

                        // Check if data is fully populated and generate PDF
                        if (isDataFullyPopulated()) {
                            const ticketElement = document.getElementById('ticketContent'); // Assuming this ID is for the content you want in the PDF
                            // html2pdf().from(ticketElement).set({
                            //     margin: 0,
                            //     filename: 'match-ticket.pdf',
                            //     image: {
                            //         type: 'jpeg',
                            //         quality: 0.98
                            //     },
                            //     html2canvas: {
                            //         scale: 2
                            //     },
                            //     jsPDF: {
                            //         unit: 'in',
                            //         format: 'a4',
                            //         orientation: 'portrait'
                            //     }
                            // }).save();
                            html2pdf()
                                .set({
                                    margin: 10, // Optional: margin in mm
                                    filename: 'match-ticket.pdf',
                                    image: {
                                        type: 'jpeg',
                                        quality: 0.98
                                    },
                                    html2canvas: {
                                        scale: 2
                                    },
                                    jsPDF: {
                                        unit: 'mm',
                                        format: 'a4',
                                        orientation: 'portrait'
                                    }
                                })
                                .from(ticketElement)
                                .outputPdf('datauristring')
                                .then(function(pdfBase64) {
                                    // Remove the prefix (data:application/pdf;base64,) to get just the base64 string
                                    const pureBase64 = pdfBase64.split(',')[1];

                                    // Send it to PHP using AJAX
                                    fetch('save_pdf', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify({
                                                fileData: pureBase64,
                                                fileName: 'match-ticket.pdf'
                                            })
                                        })
                                        .then(res => res.text())
                                        .then(response => {
                                            console.log("Server response:", response);
                                            alert("PDF saved successfully!");
                                        })
                                        .catch(error => {
                                            console.error("Error saving PDF:", error);
                                            alert("Error saving PDF.");
                                        });
                                });


                        }
                    });

                // QR Code: Scan
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, canvas.width, canvas.height);
                if (code) {
                    qrOutput.textContent = code.data;
                    qrContainer.innerHTML = '';
                    new QRCode(qrContainer, {
                        text: code.data,
                        width: 150,
                        height: 150
                    });
                } else {
                    qrOutput.textContent = "No QR code detected.";
                }
            };

            img.src = imagePath;
        }
    </script>



</body>

</html>