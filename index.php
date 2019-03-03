<?php

require_once('config.php');

$c = $config['cloudinary'];

$links = json_decode(file_get_contents('data.json'), true);

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