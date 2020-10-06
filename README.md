# Date Easy - Laravel App

## About Application

This App is developed in Laravel 8, and backend database as MySQL. This application find and shows users around you based on geo location.
This app also able to match mutual likes.

## Features

- Registration with Name, Email, Password, Location (PC/Browser geolocation - latitude, longitude), Date of Birth and Gender
- Upload single profile photo
- Login panel with email and password
- See other user list (in a table or any other simplified view) around 5 KM (Using geolocation distance driven query)
- Show user Name, image, distance, gender and age in user list
- A like and dislike button for each user. Keep like and dislike mapping in database.
- Mutual like indication - Show a popup with a message (It's a Match!) if user like one person and the liked person previously likes him. ( Use Case: Consider two user - ‘A’ and ‘B’ . User ‘A’ logged in and likes ‘B’s profile. Once ‘B’ logged in and likes ‘A’s profile - a simple popup will be invoked with the message.)

### Third party libraries

- **[Laravel-Geoip](https://lyften.com/projects/laravel-geoip/doc/)**
- **[Image Intervention](http://image.intervention.io/getting_started/installation)**

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review code and provide feedback.

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Date Easy is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
