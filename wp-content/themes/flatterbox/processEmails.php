<?php
/* Template Name: Process Emails */

$remind = 0;
$summary = 0;

if ( isset($_GET['remind']) ) : $remind = $_GET['remind']; endif;
if ( isset($_GET['summary']) ) : $summary = $_GET['summary']; endif;

if ( $remind == 1 ) :
	processFlattererReminder(intval(get_field('first_reminder_days', 'option'))); // 5
	processFlattererReminder(intval(get_field('second_reminder_days', 'option'))); // 2
endif;


if ( $summary == 1 ) :
	processFlaterboxCreateSummary();
endif;

?>