<?php
// Basevalues
$dbuser = 'zeigher';
$dbhost = 'localhost';
$dbank = 'zeigher';
$installed = 'false';

require_once "functions.php";
$password = $lang->password;

function db_contest($dbhost, $dbuser, $dbwd, $dbank) {
	@$db = new mysqli($dbhost, $dbuser, $dbwd, $dbank);
        if($db->connect_error):
		return "<b style='color: red'>Fatal Error: Connection failed - $db->connect_error</b>";
	else:
		return true;
	endif;
}

// Check write perm
$error = "";
if(!is_writable('.')) $error .= "<b style='color: red'>Fatal Error: No write permission</b><br>";
if(!extension_loaded('gd') || !function_exists('gd_info')) $error .= "<b style='color: red'>Fatal Error: PHP Extension GD is not installed</b><br>";
if($error != ""):
	echo $error;
	exit;
endif;

// Destroy Session
if(isset($_SESSION['loggedin'])):
	session_destroy();
endif;

// Set Steps
unset($_GET['f']);
if(empty($_GET)) echo "<script>self.location.href='?step=1'</script>";
if(isset($_GET['step'])) $step = $_GET['step'];
if(file_exists("config.php")):
	include "sql.php";
else:
	$step = 1;
endif;
if($step > 2 && !$db->query("SELECT 1 FROM settings LIMIT 1;")) echo "<script>self.location.href='?step=2'</script>";

// Write config file
if(isset($_GET['dbhost']) && isset($_GET['dbuser']) && isset($_GET['dbwd'])):
        if($_GET['dbwd'] == "" && isset($dbwd)):
                $password = $dbwd;
        else:
                $password = $_GET['dbwd'];
        endif;
        if(isset($_GET['test'])):
                echo db_contest($_GET['dbhost'], $_GET['dbuser'], $password, $_GET['dbank']);
        elseif(db_contest($_GET['dbhost'], $_GET['dbuser'], $password, $_GET['dbank'])):
		if(file_exists("config.php")) unlink("config.php");
                $conffile = fopen("config.php", "a+");
                fwrite($conffile, "<?php\r\n");
                fwrite($conffile, "\$dbuser = '".$_GET['dbuser']."';\r\n");
                fwrite($conffile, "\$dbwd = '$password';\r\n");
                fwrite($conffile, "\$dbhost = '".$_GET['dbhost']."';\r\n");
                fwrite($conffile, "\$dbank = '".$_GET['dbank']."';\r\n");
                fclose($conffile);
                echo true;
        endif;
endif;

// Add first user
if(isset($_GET['username']) && isset($_GET['password'])):
        if(!$db->query("SELECT 1 FROM user LIMIT 1;")->num_rows):
		$username = $db->real_escape_string(strtolower($_GET['username']));
		$password = password_hash($_GET['password'], PASSWORD_DEFAULT);
		$db->query("INSERT INTO user (username, password, admin, free) VALUES ('$username', '$password', 1, 1)");
		if($db->error):
			echo "<b style='color: red'>Fatal Error: User creation failed - $db->error</b>";
		else:
			echo 1;
		endif;
	else:
		echo "<b style='color: red'>Fatal Error: User already created</b>";
	endif;
	exit;
endif;

// Start steps
switch($step):
case "1": //Step 1 - Set Database
	if(isset($dbwd)) $password = $lang->notchanged;
	if(file_exists("config.php")) echo "<b>Info: config.php already exist</b><br>";
    	echo "$lang->dbuser:<br>
    	<input id='dbuser' value='$dbuser'></input><br>
	$lang->dbwd:<br>
	<input id='dbwd' type='password' placeholder='$password'></input><br>
    	$lang->dbhost:<br>
	<input id='dbhost' value='$dbhost'></input><br>
    	$lang->dbank:<br>
	<input id='dbank' value='$dbank'></input><br>
	<input type='submit' class='$color' onclick='check_db(\"&test\");' value='$lang->check'></input>
	<span id='dbresult'></span>";
	?>
	<script>
	function check_db (test) {
	xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			if(test != ""){
				if(xmlhttp.response == "1"){
					dbresult.innerHTML = "<input type='submit' class='$color' onclick='check_db(\"\");' value='<?php echo $lang->continue; ?>'></input><span id='conferror'></span>";
				} else {
	  				dbresult.innerHTML = xmlhttp.response;
				}
			} else {
				if(xmlhttp.response == "1"){
					self.location.href='?step=2';
				} else {
                                        conferror.innerHTML = xmlhttp.response;
                                }
			}
                }
        }
        xmlhttp.open("GET","?dbuser="+ dbuser.value + "&dbwd=" + dbwd.value + "&dbhost=" + dbhost.value + "&dbank=" + dbank.value + test,true);
        xmlhttp.send();
	}
	</script>
	<?php
	break;


case "2": //Step 2 - Init Database
	if(!file_exists("data/trash")) mkdir("data/trash", 0700);
	if(!$db->query("SELECT 1 FROM settings LIMIT 1;")):
		$db->multi_query(file_get_contents('init.db'));
		$error = $db->error; //TODO - Clean error
		if(!$db->query("SELECT 1 FROM settings LIMIT 1;")):
			echo "<b style='color: red'>Fatal Error: Initialisation failed - $error</b><br>";
			echo "<input type='submit' class='$color' value='$lang->retry' onclick=\"self.location.href='?step=2';\"></input></script>";
			exit;
		else:
			echo "<b>Info: DB Initialized</b><br>";
		endif;
	else:
		echo "<b>Info: DB already Initialized, nothing to do.<br>";
	endif;
	echo "<input type='submit' class='$color' value='$lang->continue' onclick=\"self.location.href='?step=3';\"></input></script>";
	break;


case "3": //Step 3 - First User
        if(!$db->query("SELECT 1 FROM user LIMIT 1;")->num_rows):
		echo "$lang->user: <input id='user'></input><br>
		$lang->password: <input id='password' type='password'></input><br>
    		<input type='submit' class='$color' value='$lang->continue' onclick='adduser();'></input><span id='userresult'></span>";
		?>
		<script>
		function adduser () {
	        xmlhttp=new XMLHttpRequest();
	        xmlhttp.onreadystatechange=function() {
	                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
	                	if(xmlhttp.response == "1"){
	                        	self.location.href='?step=4';
	                        } else {
	                        	userresult.innerHTML = xmlhttp.response;
	                        }
	                }
	        }
	        xmlhttp.open("GET","?username="+ user.value + "&password=" + password.value,true);
	        xmlhttp.send();
	        }
		</script>
		<?php
	else:
		echo "<b>Info: User already created, nothing to do.</b><br>";
		echo "<input type='submit' class='$color' value='$lang->continue' onclick=\"self.location.href='?step=4';\"></input>";
	endif;
	break;
case "4": //Step 4 - Create Tables
	$conffile = fopen("config.php", "a+");
        fwrite($conffile, "\$installed = 1;\r\n");
        fclose($conffile);
	echo "<b>Info: Installation finished</b><br>";
        echo "<input type='submit' class='$color' value='$lang->continue' onclick=\"self.location.href='.';\"></input>";
	break;
case "5":    
	echo "<b style='color: red'>Fatal Error: Zeigher is already installed.</b><br>";
	break;
endswitch;
?>
