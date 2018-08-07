Facebook RSS
========
This application creates an aggregated RSS of all Facebook pages liked by the user. It is designed as a web application that should run on a server and that can maintain multiple independent accounts for several users.

Since it is no longer possible to access the Facebook Timeline through the Facebook API, it is also inconveniently difficult to access private posts created by other users. This kills practically all attempts for a traditional RSS export that would cover all the activity on someone's account. However, posts on Facebook pages are almost always public which gives an opportunity for at least some viable exports. This is one of them. If the list of your liked pages isn't a complete mess, you can get a reliable feed for your RSS reader.

**Project status:** completed (archived)

**Why archived:**
After the incident where Facebook was leaking personal information to the various analytical companies, the Facebook API got severely restricted.
The apps now need a special permission even if they want to access only the public posts on the pages (Page Public Content Access). For this permission the apps need to pass a thorough review process. 
As it currently stands, it seems that this is a no go for personal, non-commercial apps.


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
5. Run the app and log in as the admin
6. Initialize the database for regular users
7. Try to create a user account to check if everything is set correctly
8. ?
9. PROFIT!


## User Guide

Just follow instructions in the app: First, sign up with some username and password. Second, connect your account with your Facebook. Now, you should be able to generate the RSS feed or check the list of liked pages.

To access the RSS feed directly just use its URL and appropriate credentials.

The access token that connects this app with your FB account expires after approximately 2 months (this cannot be avoided). When this happens, you will be asked to reconnect your account. The notice will be delivered via the RSS feed.


## License
MIT License