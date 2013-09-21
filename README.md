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

Configuration example
---------------------

1. $PHOTOS_BASE_DIR should be an absolute directory, so if you plan to put your photos in a subdir of photo-explorer, you can use the getcwd() php method as shown in the example.

2. Thumbnails can be either in a subdirectory of the original photos (.thumb subdir in the example bellow), or in a dedicated folder tree (same tree as the original photos, in that case $THUMB_SUBDIR should be empty)

3. $PAGE_SIZE set the defaut number of photos to display on a page

4. Change $AUTH_MODE_ENABLED to 1 if you want to switch to the authenticated mode

``` php
$PHOTOS_BASE_DIR = getcwd()."/photos";
$THUMB_BASE_DIR = getcwd()."/photos";
$THUMB_SUBDIR = ".thumb";
$PAGE_SIZE = 50;
$AUTH_MODE_ENABLED=0;
```


Thumbnail script example
------------------------

This is an example of a bash script that allows to create thumbnails from an original photo directory tree.
It can be scheduled in a crontab.

``` bash
#!/bin/bash

THUMBNAIL_BASE="/target/path/to/thumbnails/"
THUMBNAIL_SIZE="800x800>"
PHOTO_BASE="/path/to/my/photos/"


function process_directory {
        local photo_subdir=$1
        photo_dir=$PHOTO_BASE$photo_subdir
        thumb_dir=$THUMBNAIL_BASE$photo_subdir

        echo 'exploring directory '$photo_dir

        # create thumbnails if it does not already exists or if the thumbnail is too old
        cd $photo_dir
        for image in `/bin/ls *.png *.PNG *.jpg *.JPG *.jpeg *.JPEG *.gif *.GIF 2>/dev/null`
        do
                if [ ! -e $thumb_dir/$image ] || [ $image -nt $thumb_dir/$image ]
                then
                        if [ ! -e $thumb_dir ]
                        then
                                mkdir -p $thumb_dir
                        fi

                        if [ -e $thumb_dir/$image ] && [ $image -nt $thumb_dir/$image ]
                        then
                                echo 'regenerating old thumbnail for '$photo_dir$image >&2
                        else
                                echo 'creating thumbnail for '$photo_dir$image >&2
                        fi
                        thumbnail=$thumb_dir/$image
                        /usr/bin/convert "$image" -thumbnail "$THUMBNAIL_SIZE" "$thumbnail"
                fi
        done

        # remove thumbnails that are not in the photo directory
        cd $thumb_dir
        for image in `/bin/ls *.png *.PNG *.jpg *.JPG *.jpeg *.JPEG *.gif *.GIF 2>/dev/null`
        do
                if [ ! -e $photo_dir/$image ]
                then
                        echo 'removing thumbnail '$thumb_dir$image >&2
                        /bin/rm "$thumb_dir/$image"
                fi
        done

        for subdir in `/bin/ls -d */ 2>/dev/null`
        do
                if [ ! -e $photo_dir/$subdir ]
                then
                        echo 'removing directory '$thumb_dir$subdir >&2
                        /bin/rm -rf "$thumb_dir/$subdir"
                fi
        done


        # browse sub directories
        cd $photo_dir
        for subdir in `/bin/ls -d */ 2>/dev/null`
        do
                process_directory $photo_subdir$subdir
        done


}


SAVEIFS=$IFS
IFS=$(echo -en "\n\b")

echo 'Generating thumbnails from '$PHOTO_BASE' to '$THUMBNAIL_BASE >&2
echo '' >&2
echo 'Maximum size of thumbnails: '$THUMBNAIL_SIZE >&2
echo '' >&2
echo '' >&2
process_directory ""
```

TODO
----

1. Better management of authentication:
    - change auth method (not using .htaccess)
    - admin page to manage users and auth paths
