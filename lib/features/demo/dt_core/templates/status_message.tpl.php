<?php

/**
 * @file
 * Contains the markup for the message block.
 */
?>
<?php if ($message_title): ?>
  <div class="messages<?php print $message_classes; ?>">
    <h3><?php print ($message_type ? '<span class="sr-only">' . $message_type . ': </span>' : '') . $message_title; ?></h3>
    <?php if ($message_body): ?>
      <?php print $message_body; ?>
    <?php endif; ?>
  </div>
<?php endif; ?>
