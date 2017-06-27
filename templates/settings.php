<div class="wrap">
    <h2>User feedback settings</h2>
    <form method="post" action="options.php">
        <?php @settings_fields('user_feedback-group'); ?>
        <?php @do_settings_fields('user_feedback-group'); ?>

        <?php
          $additional_emails = $GLOBALS['user_feedback_options']->get_option('user_feedback_additional_emails');
        ?>

        <table class="form-table">
          <tr valign="top">
            <th scope="row"><label for="user_feedback_additional_emails"><?php _e('Additional emails',"wp-odm_user_feedback") ?></label></th>
            <td>
              <textarea class="full-width" name="user_feedback_additional_emails" placeholder="email1, email2"><?php echo $additional_emails;?></textarea>
              <p class="description"><?php _e('Specify a comma-separated list of email addresses to send emails to',"wp-odm_user_feedback") ?></p>
            </td>
          </tr>
        </table>
        <?php @submit_button(); ?>
    </form>
</div>
