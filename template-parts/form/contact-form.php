<?php
require_once get_template_directory() . '/inc/FormGenerator.php';

$form = new FormGenerator(rest_url('custom-form/v1/submit'));

$form->addField('text', 'name', 'Imię i nazwisko', true, 'form-control');
$form->addField('email', 'email', 'Adres e-mail', true, 'form-control');
$form->addField('text', 'phone', 'Numer telefonu', true, 'form-control');
$form->addField('textarea', 'message', 'Wiadomość', true, 'form-control');

echo $form->render();
?>
