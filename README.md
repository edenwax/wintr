# wintr
[WIP] PHP/PDO image hosting script with account controls and admin panel

just a heads up, this isnt a serious project. just something i'm building in my spare time for funsies. 
i'm not well versed in PHP or programming in general so dont expect some wozniak level stuff here.

## features
wintr(0.2.2a) comes with:
- basic admin panel to manage users and uploads
- user accounts where users can manage uploads and edit account information
- random filename generator w/ duplicate checking
- removable/editable sassiness on account.php and upload.php
- inconsistent methods and syntax (clean up is on the to do list!)
- scrubbed inputs(whatever that means)

## bugs
- inconsistent queries, methods and syntax (not really a "bug" but whatever)
- deleting account doesnt automatically deleted the users hosted image. 
these "ghost" images wont show up in the acp either, you have to remove them from the dir.
- due to the use of flex box the listed images on uploads.php can display weird.
- images hosted at the same exact time may re-arrange themselves if an image is deleted in uploads.php
- bunny

# installation
if you wanna run this hot mess, there's a few things you gotta do:
1. you need apache and mysql installed
2. create a database and import wintr.sql into it
3. create a folder on your server (I use a folder named i) 
4. edit connect.php with your server and database information
5. edit connect.php with your domain & path (if necessary)
6. edit connect.php with your image folder
7. navigate to the url, it should open fine
8. log into the admin panel
9. username: admin
10. password: testing123
11. change your password

# edits
im open to people tweaking/fixing/improving this thing. id really like to see how i could do it better. :)

cheers
