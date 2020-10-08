<?php

require_once('config.php');
$t = $config['trello'];

$host = 'https://api.trello.com';
$endpoint = '/1/lists/' . $t['list'] . '/cards';
$params = [
  'fields' => 'name',
  'customFieldItems' => true,
  'attachments' => true,
  'attachment_fields' => 'url',
  'key' => $t['key'],
  'token' => $t['token']
];

$url = $host . $endpoint . '?' . http_build_query($params);

function curlGet($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

$links_response = curlGet($url);

//$links_response = file_get_contents($url);
$links_response = json_decode($links_response, true);

$links = [];
foreach ($links_response as $link_r) {

  $link = [
    'name' => $link_r['name']
  ];

  if (count($attachments = $link_r['attachments'])) {
    $link['image'] = $link_r['attachments'][0]['url'];
  }

  if (count($customFieldItems = $link_r['customFieldItems'])) {
    foreach ($customFieldItems as $field) {
      $fieldId = $field['idCustomField'];

      switch ($fieldId) {
        case '5c6405c00456d7824f5c2fe9':
          $link['link'] = $field['value']['text'];
          break;
        case '5c6405acfbb82540c2ff100c':
          $link['description'] = $field['value']['text'];
          break;
      }
    }
  }

  array_push($links, $link);
}

file_put_contents('data.json', json_encode($links));

?>
