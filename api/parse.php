<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');

if (empty($raw)) {
    echo json_encode(['error' => 'No XML data provided']);
    exit;
}

// Vulnerable: LIBXML_NOENT resolves external entities (XXE)
libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadXML($raw, LIBXML_NOENT | LIBXML_DTDLOAD);

$errors = libxml_get_errors();
libxml_clear_errors();

if (!empty($errors)) {
    echo json_encode(['error' => $errors[0]->message]);
    exit;
}

echo json_encode(['result' => $dom->saveXML()]);
