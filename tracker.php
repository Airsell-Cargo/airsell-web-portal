function getTrackingTimeline($objectId, $apiKey) {
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://one-record-playground.p-eu.rapidapi.com/logistics-objects/$objectId/events",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Accept: application/ld+json",
            "X-RapidAPI-Key: $apiKey",
            "X-RapidAPI-Host: one-record-playground.p-eu.rapidapi.com"
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);
    
    $data = json_decode($response, true);
    
    // Sort events by date (Newest first)
    usort($data, function($a, $b) {
        return strtotime($b['cargo:eventDate']) - strtotime($a['cargo:eventDate']);
    });

    return $data;
}
