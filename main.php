<?php
$showcontent = new PageContent();
echo $showcontent->showPage('Home Page');
if ($referid == '') { $referid = 'admin'; }
echo $referid;
?>