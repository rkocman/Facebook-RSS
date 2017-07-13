Facebook RSS
========
This application creates an aggregated RSS of all Facebook pages liked by the user. It is designed as a web application that should run on a server and that can maintain multiple independent accounts for different users.

Since it is no longer possible to access the Facebook Timeline through the Facebook API, it is also far more difficult to access private posts created by other users. This kills practically all attempts for a traditional RSS export that would cover all the activity on someone's account. However, posts on Facebook pages are almost always public which gives an opportunity for at least some viable exports. This is one of them. If the list of your liked pages isn't a complete mess, you can get a reliable feed for your RSS reader.

**Project status:** completed (maintained)


## Installation Guide

To set up the application you will need several things beforehand:
1. A web server that supports PHP (>=5.4) and MySQL
2. Dependency Manager for PHP: [Composer](https://getcomposer.org/)
3. Own [Facebook App](https://developers.facebook.com/) on API v2.9

The installation process follows: 
1. Clone or download this repository
2. Install all dependencies with Composer: `composer install`
3. Fill `config.default.php` and rename it to `config.php`
4. Upload the app on the server
5. Run the app and log in as an admin
6. Initialize the database for regular users
7. Try to create a user account to check if the FB authentication process is correctly set. 
8. ?
9. PROFIT!


## User Guide

Just follow instructions in the app: First, sign up with some name and password. Second, connect your account with your Facebook. Now you can go see the list of liked pages or generate the RSS feed.

To access the RSS feed directly just use its URL and appropriate login details.

The access token that connects the FB account expires after approximately 2 months (this cannot be avoided). When this happens, you will be asked to reconnect your account. The notice will be delivered via the RSS feed.


## License
MIT License