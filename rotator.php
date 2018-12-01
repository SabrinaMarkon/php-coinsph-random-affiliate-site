<?php
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

$allrotators = new Rotator();
$rotators = $allrotators->getAds();

if ($rotators) {

    $ads = new Ad();    

    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    foreach ($rotators as $rotator) {

        $rid = $rotator['id'];
        $rtitle = $rotator['title'];
        $rurl = $rotator['url'];
        $rshorturl = $rotator['shorturl'];
        $rdescription = $rotator['description'];
        $rimageurl = $rotator['imageurl'];

        $sql = "update ads set hits=hits+1 where id=?";
        $q = $pdo->prepare($sql);
        $q->execute([$rid]);

        # get clicks when people click too.
        ?>
        <article class="card">
            
            <div class="text-center"><img class="card-image" alt="<?php echo $rtitle; ?>" src="<?php echo $rimageurl; ?>" /></div>
            <p><div><a href="/click/<?php echo $rid ?>" target="_blank"><?php echo $rtitle; ?></a></div><div><?php echo $rdescription; ?></div></p>

        </article>
        <?php
    }
}

