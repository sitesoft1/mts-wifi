<?php
//CURL DEFAULT POST
function wcCurl($queryData, $queryUrl){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $queryData,
    ));
    
    $result = curl_exec($curl);
    curl_close($curl);
    
    return $result;
}
//CURL DEFAULT POST END


$queryUrl = 'https://api.tarifnik.ru/v1.2/make_handle';

$suggestion = [
    'value' => 'Новосибирская обл, г Новосибирск, ул Виктора Уса, 13',
    'unrestricted_value' => '630088, Новосибирская область, г Новосибирск, ул Виктора Уса, 13',
    'data' => [ 'postal_code' => '630088', 'etc' => '...' ]
];

$queryData = http_build_query(array(
    '_personal_token' => '56ad24efca4f548cf1624cbbd40a0965',
    'phone' => 79861328042,
    'campaign' => 102,//102 - Yandex, 120 - google
    'name' => 'Александр',
    'fam' => 'Милано',
    'pname' => 'Викторовна',
    'comment' => 'пробный комментарий 912',
    'region_name' => 'Новосибирская область',
    'region_id' => 36,
    'suggestion' => json_encode($suggestion)
));

$result = wcCurl($queryData, $queryUrl);

echo '<pre>'; var_dump($result); echo '</pre><br><hr>';
