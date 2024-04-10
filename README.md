# Tech Interview Stage notes

Before running the PHP server from the root of this repository,
you will need to run `db_init.sql`. This will set up the user
that is configured within the `/inc/definitions.php` file. As
well as this, it creates a DB and table for the user logins to
go into. The default username/password for the app is
`admin:changeme`, with the username/password for the database
connection being found in `/inc/definitions.php`. All the
database connection settings that are configured beyond standard
can all be found within there also.

## Task 1

## Task 2

### Changes

* Changed the on-page-render to instead use a form to generate
  the results and populate some fields (see /weather/index.php)
* Used JQuery to capture the form submission and send an AJAX
  request to the server requesting the location. (see
  /js/script.js:70)
* Passed a CSRF value in the request as a layer of protection
  against the server endpoint being hit by a potentially
  malicious user (see /js/script.js:59&75)
* Check that the user is authenticated (stored in session) and
  that the CSRF token provided belongs to the current session.
  If either of these fails, then the request is rejected
  (see /actions/weather.php:7&13)
* The inputted city name is sanitized to prevent any kind of
  unintended malicious requests going out to the API
  (see /actions/weather.php:41)
* The API call that was provided was a deprecated method
  (according to the docs here ->
  <https://openweathermap.org/current#builtin>). I switched to
  making a call to the GeoCoding API instead to get the lat/lon
  of the location (as returned by the GeoCoding API) and then
  using that in the weather API call. (see /actions/weather:48-49)
* When handling the response, any errors sent by either the
  weather endpoint or by the server are displayed as a notice
  (see /js/script.js:80-82&93)
* If the response does not contain an error, various fields
  are updated with the relevant data. (see /js/scripts:84-88)

### Overall notes

The major security flaw that I found was that any user of the site
could access the information. This may have been an intended feature,
but as it was not mentioned, and I had already implemented a login
and authentication method in the previous task (task 1), I felt it
suitable to implement that and enforce users to be signed in to
even be able to access the page.

I decided to add a second security level as well, in the form of the CSRF token. This is to ensure that anyone making a request to the
server for the weather data would need more than just a suitable `PHPSESSID` cookie to access the data, but also have the most recent CSRF token
applied to the session.

Finally, I also decided that having a single 'location' accessible
on this page, I would expand it to allow the user to search for a
location and get the weather for that location. That meant I needed to
move the API calls from the page load to a server action that could be
requested via some JS to have a dynamic page.

## Task 3
