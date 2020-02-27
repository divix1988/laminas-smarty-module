# laminas-smarty-module
Based on https://github.com/skillfish/zf3-smarty-module and modified for use with Laminas

# Installation
Although you could just clone this repository, it is highly recommended to install the module via composer.
- run `php composer.phar require divix1988/laminas-smarty-module`
- add Smarty to your Laminas modules Configuration e.g /config/modules.config.php
- change the View Manager Strategy to Smarty in your module or apllication config like:
```php
<?php
return [
  'view_manager' => [
    'strategies' => [
      'Smarty\View\Strategy'
    ],
  ],
];
```
you might also want to change your View Manager's Template Map to actually use .tpl files instead of the default .phtml
```php
<?php
return [
  'view_manager' => [
    'template_map' => [
      'layout/layout' => '/module/Application/view/layout/layout.tpl',
      'application/index/index' => '/module/Application/view/application/index/index.tpl',
      'error/404' => '/module/Application/view/error/404.tpl',
      'error/index' => '/module/Applicationview/error/index.tpl',
    ],
  ],
];
```
# Template Inheritance
In order for template inheritance to work, you must terminate your ViewModel inside your Controller with `$viewModel->setTerminal(true);` and make use of the smarty `{extends}` tag. Otherwise the ViewModel will render the default layout template and inheritance won't work.

### Example

layout.tpl
```html
<html>
<head>
  <title>{block 'title'}Page name{/block}</title>
</head>
<body>
  {block 'content'}{/block}
</body>
</html>
```
index.tpl
```html
{extends 'layout.tpl'}
{block 'title' append} - Index{/block}
{block 'content'}This is the index template{/block}
```
Controller
```php
public function indexAction()
{
    $viewModel = new ViewModel();
    $viewModel->setTerminal(true);
    return $viewModel;
}
```

will result in
```html
<html>
<head>
  <title>Page name - Index</title>
</head>
<body>
  This is the index template
</body>
</html>
```

# Requirements
The composer module currently requires:
```json
"require": {
  "php":                                ">5.4",
  "smarty/smarty":                      "~3.1.29",
  "laminas/laminas-stdlib":             "~3.0.1",
  "laminas/laminas-mvc":                "~3.0.1",
  "laminas/laminas-servicemanager":     "~3.1",
  "laminas/laminas-modulemanager":      "~2.7.1"
},
```
