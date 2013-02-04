<?php
/*
 *  Active Member Page
 *
 *  Copyright (C) 2013 Jonathan Gillett, Computer Science Club
 *  All rights reserved.
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once "inc/auth.php";
require_once "inc/db_interface.php";
require_once "inc/validate.php";

session_start();

$mysqli_conn = new mysqli("localhost", $db_user, $db_pass, $db_name);

/* check connection */
if (!valid_mysqli_connect($mysqli_conn))
{
    printf("Connection failed: %s\n", mysqli_connect_error());
    exit();
}

/* Validate the first and last name input, if the input is valid then verify 
 * if it belongs to a member. Any other information can be used to verify the
 * member in-case their name was misspelled, although this could be exploited...
 */
if (isset($_POST['first_name']) && isset($_POST['last_name']) 
    && isset($_POST['student_number']))
{
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $student_number = $_POST['student_number'];
    $access_account;

    /* If the name and student # is valid verify that they exist in the database */
    if (valid_first_name($first_name) && valid_last_name($last_name)
        && valid_student_num($student_number))
    {
        /* If the access account is != -1 then the info provided belongs to a member */
        $access_account = verify_member($mysqli_conn, $first_name, $last_name, $student_number, $AES_KEY);
        if ($access_account !== -1)
        {
            echo "Access Account: " . $access_account;
            /* Add the member to the active members list if they are not already added */
            if (! is_active($mysqli_conn, $access_account))
            {
                /* Record the member as active! */
                add_member($mysqli_conn, $first_name, $last_name, $access_account);

                /* Member has been recorded as active, redirect to main page */
                $_SESSION['isactive'] = "isactive";
                header('Location: index.php');
            }
            else
            {
                /* Member has already been marked as active... */
                $_SESSION['alreadyactive'] = "alreadyactive";
                header('Location: index.php');
            }
        }
        else
        {
            /* Information provided does not match any club members, redirect to main page */
            $_SESSION['notmember'] = "notmember";
            header('Location: index.php');
        }
    }
    else
    {
        /* Invalid data, redirect to main page */
        $_SESSION['invalid'] = "invalid";
        header('Location: index.php');
    }
}
else
{
    /* Invalid data, redirect to main page */
    $_SESSION['invalid'] = "invalid";
    header('Location: index.php');
}
?>