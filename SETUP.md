SETUP INSTRUCTIONS
==================

DATABASE SETUP
--------------

1.  Create a table to store the active members for the current year/semester the access_account
    if a foreign key to the main club members table with all the info for that club member. First/last
    name are just hear for simple visibility without using a join (just to get a quick list of names!)
    
    ```sql
    CREATE TABLE active_2013  
    (  
        access_account SMALLINT UNSIGNED NOT NULL,  
        first_name VARCHAR(32),  
        last_name VARCHAR(32),  
        PRIMARY KEY(access_account),  
        FOREIGN KEY(access_account) REFERENCES ucsc_members(access_account)  
            ON DELETE CASCADE ON UPDATE CASCADE  
    );
    ```

ACTIVE MEMBER PAGE SETUP
------------------------

1.  Edit the configuration file **inc/auth.php** and set the following options according to the current
    database and server configuration.

    ```php
    /* Database access */
    $db_user = '';
    $db_pass = '';
    $db_name = '';

    /* AES ENCRYPT/DECRYPT KEY */
    $AES_KEY = '';
    ```
