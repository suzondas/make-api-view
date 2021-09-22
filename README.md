
## Make-API-View
<a target="_blank" href="https://preview.uxpin.com/eeda7afa952758ef327655911bd93cae8b9f37b7#/pages/105258367/simulate/no-panels?mode=i"><img src="https://i.postimg.cc/z8LKL6Fh/make-api-view.png" width="500px" /></a><br>
<h3>Simple platform to test your API and make the response view with following extraordinary options</h3>

- Test API with Parameter(s)
- Autofill Parameter(s) Value from another API response
- Ask Parameter(s) Value at run time
- Show the response as HTML table

### Technologies:
- [Laravel](https://laravel.com/) version: 5.59.0
- [Vue](https://vuejs.org) version: 2.6.14
- [Bootstrap](https://getbootstrap.com/docs/4.0) version: 4.0
- [MariaDB](https://mariadb.org/) mysql  Ver 15.1 Distribution 10.4.17-MariaDB
- [Php](http://www.php.net) version: 7.3.25
- [GuzzleHttp](https://docs.guzzlephp.org/en/stable/)

## Requirement:
- Php version 7.x.x
- MariaDB 10.x.x

## Installation:
- Make a directory
- Run:
<pre>$ git clone https://github.com/suzondas/Make-API-View.git</pre>
<pre>$ composer install</pre>
- Create .env file and set configuration as yours:
<pre>
 DB_CONNECTION=mysql
 DB_HOST=[host]
 DB_PORT=3306
 DB_DATABASE=[database_name]
 DB_USERNAME=[username]
 DB_PASSWORD=[password]
 </pre>
<pre>$ php artisan migrate</pre>
<pre>$ php artisan key:generate</pre>
<pre>$ php artisan serve</pre>
- Browse: http://127.0.0.1:8000

Found this package helpful? Please consider supporting my work!<br>
<a href='https://ko-fi.com/Q5Q16B7Z3' target='_blank'><img height='30' style='border:0px;height:30px;' src='https://cdn.ko-fi.com/cdn/kofi1.png?v=3' border='0' alt='Buy Me a Coffee at ko-fi.com' /></a>

## License
This application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
