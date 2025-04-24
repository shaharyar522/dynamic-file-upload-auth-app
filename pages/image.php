<!DOCTYPE html>
<html>
<head>
  <title>Image Text and QR Extractor</title>
</head>
<body>
  <h2>Upload Image</h2>
  <input type="hidden" name="qr_code" id="qrInput" value="">
  <input type="file" id="imageInput" accept="image/*">
  <canvas id="canvas" style="display: none;"></canvas>
  <div>
    <h3>Extracted Text:</h3>
    <pre id="textOutput"></pre>

    <h3>QR Code Data:</h3>
    <pre id="qrOutput"></pre>
  </div>

  <!-- Tesseract.js (OCR) -->
  <script src="https://cdn.jsdelivr.net/npm/tesseract.js@2.1.5/dist/tesseract.min.js"></script>

  <!-- jsQR (QR Reader) -->
  <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

  <script>
    const imageInput = document.getElementById('imageInput');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const textOutput = document.getElementById('textOutput');
    const qrOutput = document.getElementById('qrOutput');

    imageInput.addEventListener('change', (event) => {
      const file = event.target.files[0];
      if (!file) return;

      const img = new Image();
      img.onload = () => {
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.drawImage(img, 0, 0);

        // OCR: Extract Text
        Tesseract.recognize(canvas, 'eng')
          .then(({ data: { text } }) => {
            textOutput.textContent = text;
          });

        // QR: Try to extract QR code
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, canvas.width, canvas.height);
        if (code) {
          qrOutput.textContent = code.data;
        } else {
          qrOutput.textContent = "No QR code detected.";
        }
      };

      const reader = new FileReader();
      reader.onload = (e) => img.src = e.target.result;
      reader.readAsDataURL(file);
    });
  </script>
</body>
</html>
