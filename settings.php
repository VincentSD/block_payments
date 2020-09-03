<?php
if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configcheckbox('block_payments/showcourses',
                   get_string('showcourses', 'block_payments'),
                   get_string('showcoursesdesc', 'block_payments'),
                   0));
} 
