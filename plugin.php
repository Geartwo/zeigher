<?php
$id = escape('id');
if($_GET['activate'] === "true"): $hook->install($db->query("SELECT name FROM plugins WHERE id = '$id'")->fetch_object()->name);
else: $hook->uninstall($db->query("SELECT name FROM plugins WHERE id = '$id'")->fetch_object()->name);
endif;
exit;
