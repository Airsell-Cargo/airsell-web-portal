<?php
// 1. Get Form Data
$objId    = $_POST['object_id'];
$code     = $_POST['event_code'];
$loc      = $_POST['location'];

// 2. Format the IATA ONE Record JSON-LD
$eventData = [
    '@context' => ['cargo' => 'https://onerecord.iata.org/ns/cargo#'],
    '@type'    => 'cargo:LogisticsEvent',
    'cargo:eventCode'     => $code,
    'cargo:eventDate'     => date('c'), // ISO 8601 format
    'cargo:eventTimeType' => 'ACTUAL',
    'cargo:recordedAtLocation' => [
        '@type' => 'cargo:Location',
        'cargo:locationCode' => $loc
    ]
];

// 3. Send to ONE Record via cURL
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://one-record-playground.p-eu.rapidapi.com/logistics-objects/$objId/events",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($eventData),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/ld+json",
        "X-RapidAPI-Key: YOUR_NEW_KEY", // Use a fresh key!
        "X-RapidAPI-Host: one-record-playground.p-eu.rapidapi.com"
    ],
]);

$response = curl_exec($curl);
curl_close($curl);

echo "Status Updated! Server Response: " . $response;
?>
