![WikiLipe](lib/imgs/wikilipe.png "WikiLipe")

# WikiLipe

WikiLipe is a simple personal wiki that uses [Markdown](https://daringfireball.net/projects/markdown/syntax) syntax.

## Demo

Check a live demo [here](http://felipeng.net/wikilipe-demo)

## Instalation

1. Download or clone this repository into a web server with php module running.
1. Get access the `index.php` and the WikiLipe will warn whether adjusting the directory permission is necessary.

For security reasons it is highly recommendable to use `.htaccess` with authentication for getting the WikiLipe access.

Example:
```
AuthType Basic
AuthName "WikiLipe"
AuthUserFile /etc/apache2/htpasswd/wikilipe.passwd
Require valid-user
# Allow to access the wikilipe_app.png without authentication
SetEnvIf Request_URI "/wikilipe_app.png$" LogoURI
Order Deny,Allow
Deny from all
Allow from env=LogoURI
Satisfy any
```

## Customization

### Syntax Highlight - Languages

The pre built version of highlight.js contains 23 commonly used languages; however, it is possible to create a custom bundle including only the languages you need using this [tool](https://highlightjs.org/download/)

### Syntax Highlight - Style

It is also possible to change the syntax highlight's [style](https://highlightjs.org/static/demo/)

### Showdownjs - Extensions

It is possible to add more showdown.js' [extensions](https://github.com/showdownjs/showdown/wiki)

## Components

* [jQuery](https://jquery.com)
* Markdown Converter - [showdown.js](https://github.com/showdownjs/showdown)
    * Markdown extensions - Table Of Contents - [showdown-toc](https://github.com/JanLoebel/showdown-toc)
* Syntax Highlighter - [highlight.js](https://highlightjs.org)
* FamFamFam icons - [FamFamFam](http://www.famfamfam.com/lab/icons/silk/)
* The logo was created using [FlamingText](http://www6.flamingtext.com)

## Contributing

If you wish to contribute you can submit an issue or feel free to issue a pull request.

## License

WikiLipe is released under the MIT License. See LICENSE file for details.
