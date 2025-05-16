# CSS

Kompilacja kodu SCSS:
```bash
npm run watch
```


## Zmienne SASS

`$page-width-xl` `$page-width-lg` `$page-width-md` `$page-width-sm` - wartości graniczne szerokości witryny

```scss
@use 'variables' as var;

@include var.screen-lg
{
	// Style gdy strona jest węższa niż $page-width-lg
}
```

`$mobile-nav-breakpoint` - szerokość graniczna dla strony mobilnej

```scss
@use 'variables' as var;

@include var.screen-mobile
{
	// Style dla wersji mobilnej strony
}
```

`$section-padding` - szerkość paddingu dla zawartości sekcji


## Klasy

### .section

Zawartość sekcji o szerkości maksymalnej odpowiadającej szerokości strony
