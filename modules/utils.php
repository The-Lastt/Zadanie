<?php

function theme_format_phone_url($phone, $escape = true)
{
	$phone_link = explode('-', preg_replace('/[^0-9\+]+/', '-', str_replace(' ', '', $phone)))[0];

	if (!str_starts_with($phone_link, '+'))
		$phone_link = '+48' . $phone_link;

	$phone_link = 'tel:' . $phone_link;

	if ($escape)
		return esc_attr($phone_link);

	return $phone_link;
}

function theme_format_date($date)
{
	$current_year = date('Y');

	$months = [
		'01' => 'stycznia',
		'02' => 'lutego',
		'03' => 'marca',
		'04' => 'kwietnia',
		'05' => 'maja',
		'06' => 'czerwca',
		'07' => 'lipca',
		'08' => 'sierpnia',
		'09' => 'września',
		'10' => 'października',
		'11' => 'listopada',
		'12' => 'grudnia'
	];

	if (date('Y', strtotime($date)) == $current_year)
		return date('j', strtotime($date)) . ' ' . $months[date('m', strtotime($date))];

	return date('j', strtotime($date)) . ' ' . $months[date('m', strtotime($date))] . ' ' . date('Y', strtotime($date));
}

function theme_verify_recaptcha_response($response) 
{
	try
	{

		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data =
		[
			'secret'   => get_field('forms_recaptcha_secret_key', 'option'),
			'response' => $response,
			'remoteip' => $_SERVER['REMOTE_ADDR']
		];
				
		$options =
		[
			'http' =>
			[
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data) 
			]
		];
	
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		return json_decode($result)->success;
	}
	catch (Exception $e)
	{
		return null;
	}
}
