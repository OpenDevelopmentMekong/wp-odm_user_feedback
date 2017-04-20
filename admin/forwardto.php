<?php

if(!function_exists('add_action')){
	echo 'Hi there!  I\'m just a plugin part, I can\'work directly.';
	exit;
	}
if ( !current_user_can( 'edit_others_posts' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
global $wpdb;
$forward_feedback_table = $wpdb->prefix . 'user_feedback_form_forward';
$feedback_table = $wpdb->prefix . 'user_feedback_form';
$id = $_REQUEST['id'];

$wpdb->query( $wpdb->prepare( "UPDATE $feedback_table SET status = '%d' WHERE id = '%d'AND status = %d", 1, $_REQUEST['id'], 0 ) );

$resultset = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$feedback_table." WHERE id = %d", $_REQUEST['id']));

$email = $resultset->email;
$desc = $resultset->description;
$file = $resultset->file_upload;
if ($file) :
	$uploads_dir = wp_upload_dir();
	$attached_link = $uploads_dir['baseurl'].'/user_feedback_form/'.$file;
	$file_upload = "<a target='_blank' href='".$attached_link."'>$file</a>";
endif;

$date_submitted = $resultset->date_submitted;
$trash_command =($resultset->trash==1?'undo_trash':'trashed');
$trash_display =($resultset->trash==1?'Undo Trash':'trash');

if(isset($_REQUEST['forwardto_email']) && !empty($_REQUEST['forwardto_email'])){
	$forward_desc = $_REQUEST['forward_desc'];
	$forwared_mail = $_REQUEST['forwardto_email'];
	$insert = $wpdb->insert($forward_feedback_table,
	array(
		'feedback_id'=>$id,
		'forwarded_mail'=>$forwared_mail,
		'description'=>$forward_desc
	),
	array(
		'%d',
		'%s',
		'%s'
	)
	);

	$admin_email = get_option('admin_email');
	$headers = 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'From: '. $admin_email . "\r\n". "CC:".$admin_email;
	$subject = 'FW: ODC Contact Form';
	$feedback_url = get_bloginfo("url").'/wp-admin/admin.php?page=feedback_detail&id='. $id;
	$view_message = '<a href="'.$feedback_url.'" title="View this feedback" rel="permalink">'.$feedback_url.'</a>';
	$message = $forward_desc;
	$message .= "<br/><strong>View message on web:</strong> ".$view_message;
	$message .= "<p><strong>Forwarded Message:</strong><br/>". "From:&nbsp; ".$email. "<br/> On: ".$date_submitted."</p>";
	$message .= "<strong>User's message:</strong><br/>". nl2br($desc);

	if (isset($file_upload) && !empty($file_upload)) :
		$message .= "<p><strong>Attached file:</strong><br/>". $file_upload. "</p>";
	endif;

	$mail = mail( $forwared_mail , $subject, $message,  $headers); 
  echo('<div id="message" class="updated below-h2"><p>'.($mail ==true?'Email Sent':'Something went wrong, please try again').'</a></p></div>');
		$update = $wpdb->update( $feedback_table, array('status'=>3), array('id'=>$id));

}elseif(isset($insert) && ($insert==false)){
	echo('<div id="message" class="updated below-h2"><p>Can not forward.</p></div>');
}


$forwardset = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$forward_feedback_table." WHERE feedback_id = %d", $_REQUEST['id']));
?>
<div class="wrap">
<div id="icon-edit" class="icon32 icon32-posts-law_regulation"><br></div>
<h2>Forward Feedback To</h2>
<div id="poststuff" class="feedback_wrap">
	<div id="post-body-content">
		<div class="postarea">
			<div class="reply-feedback-container-1 box-shadow">
				<form action="admin.php?page=feedback_forward&id=<?php echo($_REQUEST['id']); ?>" method="post">
					<table class="reply-feedback" cellpadding="10px" style="width:100%">
						<tr>
							<td>
							<?php
							if($email =="Anonymous user" || $email == get_option('admin_email')):
									echo "<strong>Noted: This is an anonymous user.</strong>";
							endif;
							?>
							</td>
						</tr>
						<?php
						if(isset($forwardset)):
							foreach($forwardset as $forward){
							?>
							<tr>
								<td>
									<div style="float:right"><?php
										$date = date_format(date_create($forward->forwarded_date),'M d, Y');
										$time = date_format(date_create($forward->forwarded_date),'h:i:s A');
										$date_submitted = '<strong>'.$date.'</strong><br/><a><span class="count">'.$time.'</span></a>';
										echo($date_submitted); ?>
									</div>
									<strong>Forwarded to:
										<?php echo $forward->forwarded_mail; ?>
									</strong>
									<?php
									if($forward->description){
											echo "<p>". nl2br($forward->description)."</p>";
									}
									?>
								</td>
							</tr>
							<?php
							}
						endif;
						?>
						<tr>
							<td style="background-color:#eaeaea; border-bottom:1px solid #ffffff; height:30px;">
								<label for="forwardto">Email</label>
								<input type="email" name="forwardto_email" id="forwardto" size=50 width="100%" />
							</td>
						</tr>
						<tr>
							<td style="background-color:#eaeaea; border-bottom:1px solid #ffffff; height:30px;">
								<textarea name="forward_desc" rows="5" placeholder="Message" style="width:100%"></textarea>
							</td>
						</tr>
						<tr>
							<td>
								<input type="submit" name="submit" value="Forward" id="apply-button" class="button action" style="float:right" />
							</td>
						</tr>
						<tr>
							<td>
								<h3 style="border:none">Forwarded Message:</h3>
								<hr style="border-bottom:none; margin-bottom:0px;" />
								<p>From:&nbsp;<strong><?php echo $email; ?></strong></p>
								<p>
								 <strong>Message:</strong><br />
									 <?php echo nl2br($desc); ?>
								</p>
								<?php if (isset($file_upload) && !empty($file_upload)) : ?>
								<p>
								<strong>Attached File:</strong> <br/><strong><?php echo $file_upload; ?></strong>
								</p>
								<?php endif; ?>
							</td>
						</tr>
					</table>
				</form>
				<p style="text-align:right">
				 	 <a class="small-font" href="admin.php?page=user_feedback_form&id=<?php echo($_REQUEST['id']); ?>&action=delete" title="Delete this feedback" rel="permalink" onclick="javascript:return(confirm('This action could not rollback. Are you sure?'));">Delete</a>&nbsp;|&nbsp;
				 	 <a class="submitdelete small-font" title="Move this feedback to the Trash" href="admin.php?page=user_feedback_form&id=<?php echo($id); ?>&action=<?php echo $trash_command?>"><?php echo($trash_display) ?></a>
				</p>

			</div>


		</div>
	</div>
</div>

</div>
