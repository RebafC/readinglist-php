# On the shoulders of giants

Thanks to Rogier for the basis of this repo.

My intention is to utilise more of the options available in PHP 8.2+, use some OO techniques, & utilize some (to my mind) useful open source tools.

## Readinglist

Readinglist allows you to run your own collection of "urls to read". For example: you are reading a webpage and you want to mark it as "read later", you can use this service quickly send the page title and url into your own database. From there, the service generates an RSS feed containing all these urls. You can import this RSS feed into your own, favorite RSS client (e.g.. TinyTinyRSS)

My preference is to be able to see the list, which is now the default.

## Requirements

* Server running PHP
* Mysql database

## Installation instructions

* Copy source files to your webserver or check out latest version: `git clone git@github.com:rogierlommers/readinglist.git`.
* Copy content of example config file to config.inc.php: `cp config.inc.php.sample config.inc.php` and insert your mysql database connection.
* Run the install.php script: `php install.php` (or within browser).
* Open index.php in browser
* Create bookmarklet which points to freshly installed reading list app with this javascript

```javascript
javascript:location.href='http://yourserver.com/readinglist/add/'+encodeURIComponent(window.location.href)+'/title/'+encodeURIComponent(document.title)
```

* Add url application to your favorite RSS reader.

I have used the Symfony server to run the application locally. After my changes, the application failed to add new pages until I defined a domain locally with Symfony's servce.
