<?php

require_once('config.php');
$t = $config['trello'];

$c = $config['cloudinary'];

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

$links_response = file_get_contents($url);
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

?>

<!DOCTYPE html>
<html lang="de-AT">
<head>
  <meta charset="utf-8" />
  <title>mar·ti·na</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- <link rel="stylesheet" type="text/css" media="screen" href="/assets/styles.css" /> -->
  <style><?= file_get_contents('assets/styles.css') ?></style>
</head>
<body>
  <main>
    <!-- <img class="me" src="assets/me-smaller.jpg" alt="Portrait von Mir"/> -->
    <img class="me" src="https://res.cloudinary.com/dvdb/image/upload/c_fill,h_600,q_auto:best,w_600,f_auto/me-smaller.jpg" alt="Portrait von Mir"/>
    <h1>mar·ti·na</h1>
    <ul>
      <?php foreach($links as $link): ?>
        <li>
          <a href="<?= $link['link']; ?>">
            <div class="linkbutton">
              <?php if($link['image']): ?><img alt="" class="linkimage" src="<?= $c . $link['image'] ?>" /><?php endif ?>
              <div class="linktext">
                <?php if($link['description']): ?><div class="linkdescription"><?= $link['description']; ?></div><?php endif ?>
                <div class="linkname"><?= $link['name']; ?></div>
              </div>
            </div>
          </a>
        </li>
      <?php endforeach ?>
    </ul>
  </main>
</body>
</html>