<?
session_start();

$this_page = "property";
$_SESSION['role'] = ($_SESSION['role']!="1") ? $_SESSION['role'] : 6;

/** Login Class**/
require_once '../PLC/plc.class.php';

if($_GET['p'] == 6 || $_GET['p'] == 5 || $_GET['logout']==1){

	/** Login **/
	// ** set configuration
		include('../config/config.general.php');

		require_once '../PLC/plc.class.php';
		$dbAccess = array(
		  'dbName' => $settings['dbName'],
		  'dbUser' => $settings['dbUser'],
		  'dbPass' => $settings['dbPass'],
		  'dbPort' => '3306'
		 );

		$user = new flexibleAccess('',$dbAccess);
	if ( $_GET['logout'] == 1 ){
		$user->logout();
	}
	if ( !$user->autologin()){
		header("Location: ../PLC/index.php");
		exit; //To ensure security
	}else{
		$cookie 				= $user->read_cookie();
		$_SESSION['u_id'] 		= $user->userData[$user->tbFields['userID']];
		$_SESSION['u_name'] 	= $user->userData[$user->tbFields['login']];
		$_SESSION['u_email'] 	= $user->userData[$user->tbFields['email']];
		$_SESSION['role'] 		= $user->userData['role'];
		$_SESSION['property'] 	= $user->userData['property_id'];
		$_SESSION['u_time'] 	= date("Y-m-d H:i:s", time());
		$_SESSION['u_lang'] 	= $user->userData['lang_id'];
		$_SESSION["valid_user"] = TRUE;
	}
}

$_SESSION['role'] = ( $_SESSION['role']!='1' ) ? $_SESSION['role'] : 6;

// ** set configuration
	include('../config/config.general.php');
// ** database functions
	include('classes/database.class.php');
// ** localization functions
	include('classes/local.class.php');
// ** business functions
	include('classes/business.class.php');
// ** select cuisines styles functions
	include('classes/cuisines.class.php');
// ** select country functions
	include('classes/country.class.php');
// ** connect to database
	include('classes/connect.db.php');
// ** all database queries
	include('classes/db_queries.db.php');
// ** set configuration
	include('../config/config.inc.php');
// translate to selected language
	$_SESSION['language'] = ($_SESSION['language']) ? $_SESSION['language'] : 'en_EN';
	translateSite(substr($_SESSION['language'],0,2));
// ** get superglobal variables
	// special setup for properties
	if ( current_user_can( 'Property-Overview' )){
		$_SESSION['page'] = 1;
	}else{
		$_SESSION['page'] = 2;
	}

	include('includes/get_variables.inc.php');
// ** check booking
	include('classes/bookingrules.class.php');
// ** html header section
	include('views/header.html.php');

// ** begin page content
echo "<body>
	<!-- Begin control panel wrapper -->
	<div id='wrapper'>";

	// ** top bar
	include('views/topbar.part.php');
	
	// ** main menu
?>
	<!-- Begin empty main menu -->
	<div id="menu_wrapper">
		<ul class="nav margin-left-40">
			<li>
				<a href="#" <?if($_GET['p']=='2'){echo "class='active'";} ?> >
					Sign up Property	
				</a>
			</li>
			<li>
				<a href="#" <?if($_GET['p']=='7'){echo "class='active'";} ?> >
					Create Admin user	
				</a>
			</li>
		</ul>
	</div>
	<!-- End main menu -->

	<br class="clear"/>

	<!-- Begin content -->
	<div id="content_wrapper">
	
<?
	// ** content

		// property page wrapper
		include('register/property.page.php');
		
echo"</div>";

// ** modal messages
include('ajax/modal.inc.php');
	
// ** end layout
include('views/footer.part.php');

// ** html footer section
include('views/footer.html.php');

// close database connection
mysql_close();
?>