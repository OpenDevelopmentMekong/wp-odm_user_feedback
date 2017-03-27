<?php
require_once('layout/form.php');
/**
 * Plugin Name: User Feedback Form
 * Plugin URI: http://www.opendevelopmentcambodia.net/
 * Description: The plugin that let's user to have feedback to ODC
 * Version: 2.1.0
 * Author: ODC IT team (HENG Huy Eng & HENG Cham Roeun)
 * Forked from: userfeedback (By Mr. HENG Cham Roeun)
 * Author URI: http://www.opendevelopmentcambodia.net/
 */
 global $wpdb;
 define("TABLE_FEEDBACK" , $wpdb->prefix . 'user_feedback_form');
 define("TABLE_REPLY" , $wpdb->prefix . 'user_feedback_form_reply');
 if (!class_exists('Odm_User_Feedback_Plugin')) :
     class Odm_User_Feedback_Plugin
     {
 		    public function __construct()
 		    {
          add_action("init", array($this, 'add_script'));
          add_action('admin_enqueue_scripts', array($this, 'enqueue_custom_admin_style'));
          add_action("init", array($this, 'load_text_domain'));
          add_action("admin_menu", array($this, 'user_feedback_form_menu'));
          add_action("admin_menu", array($this, 'user_feedback_form_sub_menu'));
          add_action("wp_footer", array($this, 'button_user_feedback_form'));
          add_action("wp_footer", array($this, 'FeedbackForm'));
          add_action("wp_ajax_nopriv_FeedbackForm", array($this, 'FeedbackForm'));
          add_action("wp_ajax_FeedbackForm", array($this, 'FeedbackForm'));
          add_action("wp_ajax_nopriv_FeedbackSubmission", array($this, 'FeedbackSubmission'));
          add_action("wp_ajax_FeedbackSubmission", array($this, 'FeedbackSubmission'));
          add_action("wp_ajax_nopriv_UploadFeedbackFile", array($this, 'UploadFeedbackFile'));
          add_action("wp_ajax_UploadFeedbackFile", array($this, 'UploadFeedbackFile'));
          add_action("wp_ajax_nopriv_DeleteUploadedFile", array($this, 'DeleteUploadedFile'));
          add_action("wp_ajax_DeleteUploadedFile", array($this, 'DeleteUploadedFile'));
          add_action("user_feedback_form", array($this, 'user_feedback_form_shortcode_function'));
 		    }

        public function user_feedback_form_shortcode_function(){
            $arg = array('is_popup_form'=>false, 'no_tab'=>1);
            return user_feedback_form_creation($arg);
        }

        public function load_text_domain() {
          load_plugin_textdomain( 'wp-odm_user_feedback', false,  dirname( plugin_basename( __FILE__ ) ) . '/i18n' );
        }

        public function button_user_feedback_form(){
        ?>
          <div id="wrap-feedback" class="wrap-feedback_fix_left">
            <div id="feedback-button" class="feedback-button">
              <a id="user_feedback_form"><?php _e('Contact us', 'wp-odm_user_feedback'); ?></a>
            </div>
            <img class="hide-feedbackbuttom"src="<?php echo plugins_url("wp-odm_user_feedback") ?>/images/left-circular.png" />
          </div>
          <img class="show-feedbackbuttom" src="<?php echo plugins_url("wp-odm_user_feedback") ?>/images/right-circular.png">
        <?php
        }

        public function add_script(){
        	wp_enqueue_style("user_feedback_form_buttoncss", plugins_url("wp-odm_user_feedback")."/style/button.css");
        	wp_register_script('user_feedback_form_buttonjs',plugins_url("wp-odm_user_feedback").'/js/button.js', array('jquery'));
          wp_enqueue_script('user_feedback_form_buttonjs');
        	}

        public function enqueue_custom_admin_style() {
                wp_register_style( 'user_feedback_admin_css', plugins_url("wp-odm_user_feedback"). '/style/admin-style.css', false, '1.0.0' );
                wp_enqueue_style( 'user_feedback_admin_css' );
        }

        public function FeedbackForm(){?>
            <div id="user_feedback_form_fix_left">
                <?php user_feedback_form_creation(array('is_popup_form'=>true, 'show_form'=>"all")); ?>
        	</div>
        <?php
        }

        public function FeedbackSubmission(){
        	global $wpdb;
          $uploads_dir = wp_upload_dir();
        	$request = $_REQUEST;
        	$insert = null;
        	$email_sender= $request["email"];
          $receiver = get_settings('admin_email');
        	if(empty($email_sender)){
        		$email_sender = $receiver;
        	}
        	$desc = $request["question_text"];
        	$type = $request["question_type"];
        	$file_name = $request["file_name"];
          $insert = $wpdb->insert(TABLE_FEEDBACK,
            	array(
            		'email'=> $email_sender,
            		'description'=> $desc,
            		'type'=> $type,
            		'file_upload'=> $file_name,
            	),
            	array(
                	'%s',
                	'%s',
                	'%s',
                	'%s'
            	)
        	);

          if($insert):
          	if(!empty($file_name)){
          		rename($uploads_dir['basedir'].'/user_feedback_form/temp/'.$file_name, $uploads_dir['basedir']."/user_feedback_form/".$file_name);
          	}

          	$headers = 'Content-type: text/html; charset=utf-8' . "\r\n";
          	$headers .= 'From: '. $email_sender . "\r\n";
          	$subject = 'Open Development Contact Form';
          	$message = "There is a feedback from user:".$email.": "."<br/>".
          	 "<strong>Message:</strong> "."<br/>".$desc;
      			$send = mail( $receiver , $subject, $message,  $headers);

            echo "Successful";
          	die();
          else:
            echo "Failed";
          	die();
          endif;
        }

        public function UploadFeedbackFile(){
        	$ext = pathinfo($_FILES['fileupload']['name'],PATHINFO_EXTENSION);
        	if(!in_array($ext, $this->UploadSupport())){
        		echo 'Invalid file type!';
        		die();
        	}
          $uploads_dir = wp_upload_dir();
          $uploaddir = $uploads_dir['basedir'].'/user_feedback_form/';
          $uploaddir_temp = $uploads_dir['basedir'].'/user_feedback_form/temp/';
          if (!is_dir($uploaddir) && !wp_mkdir_p($uploaddir)){
            die("Error creating folder $uploaddir");
          }
          if (!is_dir($uploaddir_temp) && !wp_mkdir_p($uploaddir_temp)){
            die("Error creating folder $uploaddir_temp");
          }
        	$tmp_name = $_FILES['fileupload']['tmp_name'];
          $original_name = $_FILES['fileupload']['name'];
        	$destination_name = $uploaddir_temp;
        	$permanent_file = $uploaddir.$_FILES['fileupload']['name'];
        	$new_destination_name =  $this->generateNewFileName($uploaddir_temp, $original_name);
        	move_uploaded_file($tmp_name, $new_destination_name);
        	$filename = basename($new_destination_name);
        	echo('img_uploaded:'.$filename);
        	die();
        }

        public function user_feedback_form_menu(){
        	add_menu_page( 'User Feedback Options', 'User Feedback', "edit_others_posts",  "user_feedback_form", array($this, 'user_feedback_form_option_content'), plugins_url("wp-odm_user_feedback").'/images/feedback-logo.png' );
          $this->user_feedback_form_sub_menu();

        }

        public function user_feedback_form_sub_menu(){
        	add_submenu_page( NULL, 'Feedback Detail', 'Feedback Detail', "edit_others_posts", 'feedback_detail', array(&$this, 'user_feedback_form_option_content_detail'));
        }

        public function user_feedback_form_option_content(){
        	require_once("admin/index.php");
        }

        public function user_feedback_form_option_content_detail(){
        	require_once("admin/detail.php");
        }

        public function CreateFeedbackTable(){
          $sql = "CREATE TABLE IF NOT EXISTS". TABLE_FEEDBACK . "(
                  	id INT( 10 ) NOT NULL AUTO_INCREMENT ,
                  	email VARCHAR( 100 ) NOT NULL ,
                  	description TEXT NOT NULL ,
                  	type VARCHAR( 50 ) NOT NULL ,
                  	file_upload TEXT NOT NULL ,
                  	date_submitted TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
                  	status BOOLEAN NOT NULL DEFAULT  '0' ,
                  	trash BOOLEAN NOT NULL DEFAULT  '0' ,
                  	PRIMARY KEY( id )
                  )DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";

          $sql .= "CREATE TABLE IF NOT EXISTS ". TABLE_REPLY." (
                    `id` int(10) NOT NULL AUTO_INCREMENT,
                    `feedback_id` int(10) NOT NULL,
                    `description` text NOT NULL,
                    `reply_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
                  )DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";

        	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        	dbDelta( $sql );
        }

        public function generateNewFileName($directory_part, $original_name){
          $new_filename = basename($original_name);
          $i = 2;
          while (file_exists($directory_part.$new_filename)) {
            $path_parts = pathinfo($original_name);
            $basefilename = $path_parts['filename'];
            $basefilename = preg_replace('/\(([0-9]*)\)$/', '', $basefilename);
            $basefilename .= '('.$i.')';

            $new_file_name = $basefilename.'.'.$path_parts['extension'];
            if (!file_exists($new_file_name)) {
                $new_filename =  $new_file_name;
            }
            $i++;
          }
        	return $directory_part.$new_filename;
        }

        public function DeleteUploadedFile(){
          $uploads_dir = wp_upload_dir();
        	$ext = pathinfo($_REQUEST['uploadedfile'],PATHINFO_EXTENSION);
        	if(!in_array($ext , $this->UploadSupport())){
        		echo 'Invalid file type!';
        		die();
      		}
          $removed_file = $uploads_dir['basedir'].'/user_feedback_form/temp/'.$_REQUEST['uploadedfile'];
      	  if(unlink($removed_file)):
            echo("Successful"); die();
          else:
            _e('Unable to delete file!', "wp-odm_user_feedback");
            die();
          endif;

        }

        public function UploadSupport(){
        	$support = array('gif','png','jpg','jpeg','pdf','doc','docx','xls','xlsx','zip','rar');
        	return $support;
        }

      }
endif;

$GLOBALS['userfeedback'] = new Odm_User_Feedback_Plugin();
register_activation_hook(__FILE__, array($GLOBALS['userfeedback'] , 'CreateFeedbackTable' ) );
 ?>
