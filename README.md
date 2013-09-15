photo-explorer
==============

A simple php website used to explore your photo library on the internet


Installing photo-explorer
-------------------------

1. Copy all files into /var/www/photo-explorer

2. Edit the config.php file :
    - update the variables depending on your configuration

3. If you want to restrict access to your photos:
    - add a .htaccess file for authentication
    - update the auth.txt file to define the directories allowed for each user:

```
user1:/relative/path/to/photos1
user1:/relative/path/to/photos2
user2:/relative/path/
```


TODO
----

1. Better management of authentication:
    - change auth method (not using .htaccess)
    - admin page to manage users and auth paths
