<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['fileData']) || !isset($data['fileName'])) {
        http_response_code(400);
        echo "Invalid input.";
        exit;
    }

    $pdfData = base64_decode($data['fileData']);
    $fileName = preg_replace('/[^a-zA-Z0-9_\-.]/', '_', $data['fileName']); // Safe file name

    $uploadDir = dirname(__DIR__) . '/upload/pdfs/';

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create directory if not exists
    }

    $filePath = $uploadDir . $fileName;

    if (file_put_contents($filePath, $pdfData)) {
        echo "PDF saved to: " . $filePath;
    } else {
        http_response_code(500);
        echo "Failed to save PDF.";
    }
}
