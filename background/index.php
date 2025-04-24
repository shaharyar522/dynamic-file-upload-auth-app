<!-- index.html -->
<!DOCTYPE html>
<html>
<head>
  <title>Generate PDF</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<button id="generatePdfBtn">Generate PDF</button>
<p id="status"></p>

<script>
  $('#generatePdfBtn').on('click', function () {
    $('#status').text('Please wait...');

    $.ajax({
      url: 'generate_pdf.php',
      method: 'POST',
      dataType: 'json', // expecting JSON now
      success: function (response) {
        if (response.status === 'success') {
          $('#status').text('PDF generated successfully.');
        } else {
          $('#status').text('Failed to generate PDF.');
        }
      },
      error: function () {
        $('#status').text('Error generating PDF.');
      }
    });
  });
</script>


</body>
</html>
