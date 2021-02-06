<?php
/*
Plugin Name: Database Demo
Plugin URI:
Description: Database Query Demo
Version: 1.0
Author: LWHH
Author URI: https://farid.me
License: GPLv2 or later
Text Domain: database-demo
Domain Path: /languages/
*/
define("DBDEMO_DB_VERSION","1.2");

require_once 'class.dbdemousers.php';

function dbdemo_init(){
	global $wpdb;
	$prefix = 'wp_';
	$table_name = $wpdb->prefix.'person';

	$sql = "CREATE TABLE {$table_name} (
		id INT NOT NULL AUTO_INCREMENT,
		name VARCHAR(250),
		email VARCHAR(250),
		PRIMARY KEY (id)
	);";

	add_option("dbdemo_db_version",DBDEMO_DB_VERSION);

	require_once (ABSPATH."wp-admin/includes/upgrade.php");

	dbDelta($sql);

	if(get_option("dbdemo_db_version" != DBDEMO_DB_VERSION )){
		$sql = "CREATE TABLE {$table_name} (
			id INT NOT NULL AUTO_INCREMENT,
			name VARCHAR(250),
			email VARCHAR(250),
			age int,
			PRIMARY KEY (id)
		);";

		update_option("dbdemo_db_version",DBDEMO_DB_VERSION);
			
		dbDelta($sql);
	}

	

	
}

register_activation_hook(__FILE__,"dbdemo_init");


function dbdemo_drop_column() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'person';
	if ( get_option( "dbdemo_db_version" ) != DBDEMO_DB_VERSION ) {
		$query = "ALTER TABLE {$table_name} DROP COLUMN age";
		$wpdb->query( $query );
	}
	update_option( "dbdemo_db_version", DBDEMO_DB_VERSION );
}

add_action( "plugins_loaded", "dbdemo_drop_column" );


function dbdemo_load_data(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'person';
	$wpdb->insert($table_name,[
		'name' => 'tarek hasan',
		'email' => 'mdfarid7830@gmail.com'
	]);
	$wpdb->insert($table_name,[
		'name' => 'farid mia',
		'email' => 'tarek@gmail.com'
	]);
}

add_action("admin_enqueue_scripts",function($hook){
	if("toplevel_page_dbdemo" == $hook){
		wp_enqueue_style("dbdemo-css",plugin_dir_url(__FILE__) . "assets/css/form.css");
	}
});

register_activation_hook(__FILE__,"dbdemo_load_data");

register_deactivation_hook(__FILE__,"dbdemo_flush_data");
function dbdemo_flush_data(){

	global $wpdb;
	$table_name = $wpdb->prefix . 'person';
	$query = "TRUNCATE TABLE {$table_name}";
	$wpdb->query($query);
}

register_deactivation_hook(__FILE__,"dbdemo_flush_data");


add_action("admin_menu",function(){
	add_menu_page("DB Demo","DB Demo","manage_options","dbdemo","dbdemo_admin_page");
});

function dbdemo_admin_page(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'person';
	if(isset($_GET['p_id'])){
		if(!isset($_GET['n']) || !wp_verify_nonce($_GET['n'],"dbdemo_edit")){ // dbdemo_edit hoitece action ar nam
			wp_die(__("You are not authorized to do this","database-demo"));
		}

		if(isset($_GET['action']) && $_GET['action'] == 'delete'){
			$id = sanitize_key($_GET['p_id']);
			$wpdb->delete("{$table_name}",['id' => $id]);

			$_GET['p_id'] = null;
		}
	}

	
	$table_name = $wpdb->prefix . 'person';

	$id = $_GET['p_id'] ?? 0;

	$id = sanitize_key($id);
	$result = $wpdb->get_row("SELECT * FROM {$table_name} where id='{$id}'");

	// if($id){
	// 	$result = $wpdb->get_row("SELECT * FROM {$table_name} where id='{$id}'");

	// 	if($result){
	// 		echo "Name: {$result->name}<br/>";
	// 		echo "Email: {$result->email}<br/>";
	// 	}
	// }
	?>

<div class="form_box">
        <div class="form_box_header">
			<?php _e( 'Data Form', 'database-demo' ) ?>
        </div>
        <div class="form_box_content">
            <form action="<?php echo admin_url('admin-post.php');?>" method="POST">
			<?php 
				wp_nonce_field('dbdemo','nonce');
			?>
                <input type="hidden" name="action" value="dbdemo_add_record">
                <label>
                    <strong>Name</strong>
                </label><br/>
                <input type="text" name="name" class="form_text" value="<?php if ( $id ) {
					echo $result->name;
				} ?>"><br/>
                <label>
                    <strong>Email</strong>
                </label><br/>
                <input type="text" name="email" class="form_text" value="<?php if ( $id ) {
					echo $result->email;
				} ?>"><br/>
				<?php
				if ( $id ) {
					echo '<input type="hidden" name="id" value="' . $id . '">';
					submit_button( "Update Record" );
				} else {
					submit_button( "Add Record" );
				}


				?>
            </form>
        </div>
    </div>
    <div class="form_box" style="margin-top: 30px;">
        <div class="form_box_header">
			<?php _e( 'Users', 'database-demo' ) ?>
        </div>
        <div class="form_box_content">
				<?php 
				
					global $wpdb;
					$dbdemo_users = $wpdb->get_results("SELECT id,name,email FROM {$wpdb->prefix}person",ARRAY_A);
					//print_r($dbdemo_users);
					$dbtable = new DBTableUsers($dbdemo_users);
					$dbtable->prepare_items();
					$dbtable->display();
				?>
        </div>
    </div>
	<?php
}


add_action("admin_post_dbdemo_add_record",function(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'person';

	if(isset($_POST['submit'])){
		$nonce = sanitize_text_field($_POST['nonce']);

		if(wp_verify_nonce($nonce,'dbdemo')){

			$name = sanitize_text_field($_POST['name']);
			$email = sanitize_text_field($_POST['email']);
			$id = sanitize_text_field($_POST['id']);

			if($id){
				$wpdb->update("{$table_name}",['name' => $name,'email' => $email],['id' => $id]); 

				$nonce = wp_create_nonce('dbdemo_edit');
				wp_redirect(admin_url("admin.php?page=dbdemo&p_id=").$id . "&n={$nonce}");
			}else{
				$wpdb->insert("{$table_name}",['name' => $name,'email' => $email]); 
				// $new_id = $wpdb->insert_id;
				// wp_redirect(admin_url("admin.php?page=dbdemo&p_id=".$new_id));
				wp_redirect(admin_url("admin.php?page=dbdemo"));
			}
			
		}
		
		

	}

});

