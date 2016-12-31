<?php
//Check write perm
$error = "";
if(!is_writable('.')) $error = $error."<b style='color: red'>Fatal Error: No write permission</b><br>";
if(extension_loaded('gd') && function_exists('gd_info')) $error."<b style='color: red'>Fatal Error: PHP Extension GD is not installed";
if($error == ""):
	echo $error;
	exit;
endif;
$installed = false;
$mode = "";
#todo
#if (!file_exists('bintro.jpg')) {
#	if(@copy('http://media2.giga.de/2014/04/watsapp-rcm992x0.png', 'bintro.jpg')){
#	echo 'Test';
#	}
#}
$spsite = "Install";
include '.data/all.php';
include '.data/header.php';

//Install Settings
$settings->logo = "";

//Destroy Session
if(isset($_SESSION['loggedin'])){
	$_SESSION['loggedin'] = false;
	session_destroy();
}

//Step 1 - Mode choose
if (!isset($_GET['mode']) && !isset($_POST['dbuser']) && !isset($_POST['salt']) && !file_exists('.settings/config.php')){
        echo "
        <form>
        " . $lang->mode . ":<br>
        <select name='mode'>
        <!--<option value='fsql'>" . $lang->folderlite . "</option>-->
        <option value='fmyma'>" . $lang->foldersql . "</option>
        <option value='dmyma'>" . $lang->poolbased . "</option>
        </select><br>
        <input type='submit' class='".$color."' value='" . $lang->send . "'></input>
        </form>
        ";
} elseif (isset($_GET['mode'])) {
    //Step 2 - Set Database
    echo "
    <form action='install.php' method='post'>
    <input type='hidden' name='mode' value='" . $_GET['mode'] . "'></input>";
    if ($_GET['mode'] == 'fmyma' | $_GET['mode'] == 'dmyma') {
    	echo $lang->dbuser .":<br>
    	<input name='dbuser' value=''></input><br>
	" . $lang->dbwd .":<br>
	<input name='dbwd' type='password'></input><br>
    	" . $lang->dbhost .":<br>
	<input name='dbhost' value=''></input><br>
    	" . $lang->dbank .":<br>
	<input name='dbank' value=''></input><br>";
    } elseif ($_GET['mode'] == 'fsql') {
	echo $lang->dbankfolder .":<br>
	<input name='dbankfolder' value='".getcwd()."/.data/'></input><br>";
    }
	echo"<input type='submit' class='".$color."' value='" . $lang->send . "'></input>
    </form>";
} elseif (isset($_POST['dbuser']) && !file_exists('.settings/config.php')) {
	//Step 3 - Test Database
	echo $lang->testdb."<br>";
	if ($_POST['mode'] == 'fmyma' | $_POST['mode'] == 'dmyma') {
	    @$conn = new mysqli($_POST['dbhost'], $_POST['dbuser'], $_POST['dbwd']);
	} else {
	    if (!file_exists($_POST['dbankfolder'] . 'mysqlitedb.db')) $db = sqlite_open($_POST['dbankfolder'] . 'mysqlitedb.db', 0660, $error);
	    $db = new SQLite3($_POST['dbankfolder'] . 'mysqlitedb.db');
	}
	if ($conn->connect_error) {
		die("<b style='color: red'>Fatal Error: Connection failed - " . $conn->connect_error . "</b><br><a href='?mode=" . $_POST['mode'] . "'>Retry</a>");
	}
	if(!file_exists('.settings')) mkdir('.settings');
	if(!file_exists('.plugins')) mkdir('.plugins');
	$datei = fopen(".settings/config.php", "a+");
	fwrite($datei, '<?php'."\r\n");
	fwrite($datei, '$mode = \''.$_POST['mode']."';\r\n");
	if ($_POST['mode'] == 'fmyma' | $_POST['mode'] == 'dmyma') {
	    fwrite($datei, '$dbuser = \''.$_POST['dbuser']."';\r\n");
	    fwrite($datei, '$dbwd = \''.$_POST['dbwd']."';\r\n");
	    fwrite($datei, '$dbhost = \''.$_POST['dbhost']."';\r\n");
	    fwrite($datei, '$dbank = \''.$_POST['dbank']."';\r\n");
	    fwrite($datei, '$zeigher_version = \'1.0\';'."\r\n");
	} else {
	    fwrite($datei, '$sqlitefolder = \''.$_POST['dbankfolder']."';\r\n");
	}
	fwrite($datei, "?>\r\n");
	fclose($datei);
	echo "<script>self.location.href='install.php'</script>";

} elseif (file_exists('.settings/config.php') && $installed != true && !isset($_GET['ready']) && !isset($_GET['settings']) && !isset($_POST['repass'])) {
	//Step 4 - Set first User
	echo "
    <form action='install.php' method='post'>
    " . $lang->user .":<br>
    <input name='user'></input><br>

	" . $lang->mainmail .":<br>
    <input name='mainmail'></input><br>
    
    " . $lang->password .":<br>
    <input name='password' type='password'></input><br>
    
    " . $lang->repass .":<br>
    <input name='repass' type='password'></input><br>
    
    <input type='submit' class='".$color."' value='" . $lang->send . "'></input>
    
    </form>
    ";
  
} elseif (file_exists('.settings/config.php') && isset($_POST['repass'])) {
	//Step 5 - Create Tebles
    include '.settings/config.php';
    $username = strtolower($_POST['user']);
    $password = $_POST['password'];
    $repass = $_POST['repass'];
    $mainmail = $_POST['mainmail'];
    if ($username == '') {
      	echo $lang->unemp;
    } elseif ($password == '') {
    	echo $lang->pwemp;
    } elseif ($repass == '') {
    	echo $lang->pwreemp;
    } elseif ($password != $repass) {
    	echo $lang->pwsame;
    } else {
	if ($mode == 'fmyma' | $mode == 'dmyma') {
    	    $db = new mysqli($dbhost, $dbuser, $dbwd, $dbank);
	    if (mysqli_connect_errno()) {
	    	printf("Can't connect to MySQL Server. Errorcode: %s\n",mysqli_connect_error());
	    	exit;
	    }
	} else {
	    $db = new SQLite3($sqlitefolder . 'mysqlitedb.db');
	}
    $db->query("ALTER DATABASE '$dbank' CHARACTER SET utf8 COLLATE utf8_unicode_ci;");
    $db->query("CREATE TABLE settings (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    setting VARCHAR(50) NOT NULL,
    value VARCHAR(50) NOT NULL,
    name VARCHAR(50) NOT NULL,
    userid VARCHAR(25) NOT NULL
    )");

    $db->query("CREATE TABLE user (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    user VARCHAR(25) NOT NULL,
    pass VARCHAR(100) NOT NULL,
    email VARCHAR(50) NOT NULL,
    lastlogin DATETIME,
    free INT(1),
    isad INT(1),
    premium INT(1)
    )");
    
	$db->query("CREATE TABLE files (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    userid VARCHAR(25) NOT NULL,
    folder VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    date DATETIME NOT NULL,
    orfile INT(11),
    name VARCHAR(255) NOT NULL,
    pro INT(11) NOT NULL,
    con INT(11) NOT NULL,
    view INT(11) NOT NULL
    )");

    $db->query("CREATE TABLE comments (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    userid VARCHAR(25) NOT NULL,
    comment TEXT NOT NULL,
    objectid VARCHAR(255) NOT NULL,
	type VARCHAR(255) NOT NULL,
    sub INT(11),
    date DATETIME NOT NULL
    )");

    $db->query("CREATE TABLE points (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    objectid VARCHAR(50) NOT NULL,
    points VARCHAR(50)
    )");    
    	$db->query("CREATE TABLE tags (
    	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
		tagname VARCHAR(50) NOT NULL,
		userid VARCHAR(25) NOT NULL
		)");

		$db->query("CREATE TABLE tagparents (
    	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		type VARCHAR(50) NOT NULL,
    	parent VARCHAR(50) NOT NULL,
    	objectid VARCHAR(50) NOT NULL
    	)");

    	$db->query("CREATE TABLE votes (
    	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    	type VARCHAR(50) NOT NULL,
    	objectid VARCHAR(50) NOT NULL,
    	userid VARCHAR(25) NOT NULL,
	    conpro INT(32) NOT NULL
    	)");
	$db->query("CREATE TABLE plugins (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        active INT(1)
        )");
	
	mkdir(".trash", 0700);
	mkdir(".files", 0700);
    if (!isset($mainmail)) $mainmail = "root@localhost.local";
    $ph = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $db->query("INSERT INTO user (user, pass, email, isad, free) VALUES ('$username', '$ph', '$mainmail', 1, 1)");
    $db->query("INSERT INTO points (type, objectid, points) VALUES ('user', '1', '60')");
    if ($mode == 'dmyma') {
    	$db->query("INSERT INTO tags (tagname, userid) VALUES ('$username', '0')");
    	$db->query("INSERT INTO tagparents (parent, type, objectid) VALUES ('0', 'tag', '1')");
		$id = $db->query("SELECT id FROM user WHERE user='$username'")->fetch_object()->id;
		$db->query("INSERT INTO tagparents (parent, type, objectid) VALUES ('1', 'user', '$id')");
    }
    echo "<script> self.location.href='install.php?settings' </script>";
    }
} elseif (isset($_GET['settings']) && $_SERVER['REQUEST_METHOD'] != 'POST') {
  echo "
    <form action='install.php?settings' method='post'>
    " . $lang->theme .":<br>
    <input name='theme' value='default'></input><br>
    
    ".$lang->regist .":<br>
    <select name='regist'>
    <option value='true'>" . $lang->y . "</option>
    <option value='false'>" . $lang->n . "</option>
    </select><br>
    
    " . $lang->regnow .":<br>
    <select name='regnow'>
    <option value='true'>" . $lang->y . "</option>
    <option value='false'>" . $lang->n . "</option>
    </select><br>
    
    " . $lang->use .":<br>
    <select name='use'>
    <option value='none'>" . $lang->y . "</option>
    <option value='all'>" . $lang->n . "</option>
    </select><br>
	<input type='submit' class='".$color."' value='" . $lang->send . "'></input>
    </form>
    ";
} elseif (isset($_GET['settings']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
	if ($mode == 'fmyma' | $mode == 'dmyma') {
    	    $db = new mysqli($dbhost, $dbuser, $dbwd, $dbank);
	    if (mysqli_connect_errno()) {
	    	printf("Can't connect to MySQL Server. Errorcode: %s\n",mysqli_connect_error());
	    	exit;
	    }
	} else {
	    $db = new SQLite3($sqlitefolder . 'mysqlitedb.db');
	}
	$theme = $_POST['theme'];
	$regist = $_POST['regist'];
	$regnow = $_POST['regnow'];
	$use = $_POST['use'];
	$mainmail = $_POST['mainmail'];
	$db->query("INSERT INTO settings (setting, value, name, userid) VALUES ('theme', '$theme', '', '0')");
	$db->query("INSERT INTO settings (setting, value, name, userid) VALUES ('regist', '$regist', '', '0')");
	$db->query("INSERT INTO settings (setting, value, name, userid) VALUES ('regnow', '$regnow', '', '0')");
	$db->query("INSERT INTO settings (setting, value, name, userid) VALUES ('use', '$use', '', '0')");
	$db->query("INSERT INTO settings (setting, value, name, userid) VALUES ('mainmail', '$mainmail', '', '0')");
	$db->query("INSERT INTO settings (setting, value, name, userid) VALUES ('points', 'true', '', '0')");
	$db->query("INSERT INTO settings (setting, value, name, userid) VALUES ('color', 'green', '', '0')");
	$db->query("INSERT INTO settings (setting, value, name, userid) VALUES ('wishes', 'false', '', '0')");
	$db->query("INSERT INTO settings (setting, value, name, userid) VALUES ('qrcode', 'false', '', '0')");
	$db->query("INSERT INTO settings (setting, value, name, userid) VALUES ('search', 'false', '', '0')");
	$db->query("INSERT INTO settings (setting, value, name, userid) VALUES ('stitel', '$stitel', '', '0')");
	$db->query("INSERT INTO settings (setting, value, name, userid) VALUES ('logo', 'false', '', '0')");
    $db->query("INSERT INTO isad (iright, ivalue) VALUES ('getpasshash', '8')");
    $db->query("INSERT INTO isad (iright, ivalue) VALUES ('update', '8')");
    $db->query("INSERT INTO isad (iright, ivalue) VALUES ('randc', '1')");
    $db->query("INSERT INTO isad (iright, ivalue) VALUES ('seemailaddr', '6')");
    $db->query("INSERT INTO isad (iright, ivalue) VALUES ('imprint', '8')");
    $db->query("INSERT INTO isad (iright, ivalue) VALUES ('promotion', '8')");
    $db->query("INSERT INTO isad (iright, ivalue) VALUES ('news', '2')");
    $db->query("INSERT INTO isad (iright, ivalue) VALUES ('settings', '8')");
    $db->query("INSERT INTO isad (iright, ivalue) VALUES ('isad', '8')");
    $db->query("INSERT INTO isad (iright, ivalue) VALUES ('fileeditor', '8')");
    $db->query("INSERT INTO isad (iright, ivalue) VALUES ('description', '3')");
	$datei = fopen(".settings/config.php", "a+");
	fwrite($datei, '<?php'."\r\n");
	fwrite($datei, '$installed = true'.";\r\n");
	fwrite($datei, '?>');
	fclose($datei);
	echo "<script> self.location.href='install.php?ready' </script>";
} elseif (isset($_GET['ready'])) {
    echo $lang->ready;
} else {
    echo 'Error - Installed: '.$installed ;
}
?>
