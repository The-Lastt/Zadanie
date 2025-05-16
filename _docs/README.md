# Proadax.pl — domyślny szablon motywu

## Budowa szablonu

plik / folder    | zawartość
-----------------|----------
assets/          | pliki statyczne szablonu oraz style
page-sections/   | pliki poszczególnych sekcji strony
page-templates/  | szablony stron
_sections/       | biblioteka gotowych sekcji
_docs/           | dokumentacja
header.php       | nagłówek strony
footer.php       | stopka strony
index.php        | główny plik motywu
style.css        | style strony generowane z SCSS **nie edytujemy tego pliku ręcznie**
404.php          | strona błędu 404

## Style

Kompilacja kodu SCSS:
```bash
npm run watch
```

[Informacje o stylach](CSS.md)

## Tworzenie szablonów stron

Szablony stron znajdują się w katalogu `page-templates/`

Przykładowy szablon:

```php
<?php
/**
 * Template Name: Strona głowna
 */

get_header(); ?>

<div class="page-home">
	<!-- Sekcja zdefiniowana dla jednego szablonu -->
	<section class="header">
		<h1>Strona głowna</h1>
		<p><?php the_field('home_text') ?></p>
	</section>

	<!-- Sekcje zdefiniowane w osobnym pliku dla wielu szablonów -->
	<?php the_section('test') ?>
	<?php the_section('gallery') ?>
</div>

<?php get_footer();

```

Pola ACF dla szablonu definowane są w pliku `page-templates/<nazwa_szablonu>.acf.php`:

```php
<?php

return [
	[
		'label' => 'Strona głowna',
		'name' => 'home_tab',
		'type' => 'tab'
	],
	[
		'label' => 'Tekst na stronie głownej',
		'name' => 'home_text',
		'type' => 'text',
		'default_value' => 'Lorem ipsum...'
	]
];
```

Przy dodawaniu sekcji do szablonu za pomocą `the_section('<nazwa_sekcji>')`, dodane sekcje muszą być wypisane w pliku `acf-fielfs/page_sections.json` aby poprawne pola ACF były wyświetalne w edytorze strony.

## Tworzenie sekcji

Sekcje definiowane są za pomocą tagu `<section>`:
```php
<section class="NAZWA_SEKCJI">
	Zawartość sekcji
</section>
```

Sekcje mogą być umeszczane 2 miejscach:
 * w pliku szablonu strony
 * w osobnym pliku w katalogu `page-sections/`

Gdy sekcja dodawana jest w osobnym pliku dostępna jest ona z poziomu wielu podstron

Pola ACF dla sekcji definowane są w pliku `page-sections/<nazwa_szablonu>.acf.php`:
```php
<?php

return [
	[
		'label' => 'Test',
		'name' => 'test_tab',
		'type' => 'tab'
	],
	[
		'label' => 'Tekst testowy',
		'name' => 'test_text',
		'type' => 'text'
	]
];

```

<!-- są one automatycznie doklejane do pól szablonu strony na której dana sekcja została użyta. -->
