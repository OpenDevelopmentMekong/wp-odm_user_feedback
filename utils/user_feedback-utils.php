<?php


  function user_feedback_gather_email_adresses($email){
    
  
    $admin_email = get_option('admin_email');
    $addtional_emails = $GLOBALS['user_feedback_options']->get_option('user_feedback_additional_emails');
    if (!empty($addtional_emails)):
      $admin_email = $admin_email . "," . $addtional_emails;
    endif;
    
    return $admin_email;
  }
  
?>
