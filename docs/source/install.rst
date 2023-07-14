=============================
Installation and Dependencies
=============================

You can install the pvcErr package through composer.::


    require "pvc/err"


Note that the pvcException package requires pvcInterfaces, so you will see that show up in your vendor/pvc directory. It
also requires Nikita Popov's PHP Parser because there's a spot in the code where is necessary to parse php files in
order to extract the class string from a file.
