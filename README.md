# Translater text in PHP


Github:

```bash
git clone https://github.com/r0ld3x/google-translater-php
cd translate
composer install
```


Composer:

```bash
composer require r0ld3x/google-translater-php
```

# Uses

## 1. Use with composer

```php
require 'path/to/vendor/autoload.php';

use r0ld3x\Translate;
$tr = new Translate;
```


## 2. Manually

```bash
git clone https://github.com/r0ld3x/google-translater-php
cd translate
composer install
```

### Google

> $tr->tr('Your text to translate', 'source_lang', 'target_lang');

```php
$res = $tr->tr("Te quiero", 'es', 'en');
echo $res; 
// I love you
```

