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

function valid_mysqli_connect($mysqli_connection)
{
    if ($mysqli_connection->connect_errno)
    {
        return FALSE;
    }
    return TRUE;
}

/**
 * Check that the user has entered valid data, do not attempt
 * to login to the account otherwise
 *
 * Validate the username and password before verifying if they are correct
 * @param string $username the username post data.
 * @return boolean TRUE if the input for the username is valid
 */
function valid_username($username)
{
    if (preg_match('/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/', $username)
            && strlen($username) < 32)
    {
        return TRUE;
    }
    return FALSE;
}


/**
 * Validate the password 
 * @param string $password the password post data
 * @return boolean TRUE if the input for the password is valid
 */
function valid_password($password)
{
    if (preg_match('/^[a-zA-Z0-9\!\$\%\^\&\*\(\)\_\?]{6,31}$/', $password))
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}


/**
 * Validate the first name
 * @param string $first_name the first_name post data
 * @return boolean TRUE if the input is valid
 */
function valid_first_name($first_name)
{
    if (preg_match('/^(([A-Za-z]+)|\s{1}[A-Za-z]+)+$/', $_POST["first_name"]) && strlen($_POST["first_name"]) < 32)
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}


/** 
 * Validate the last name
 * @param string $last_name the last_name post data
 * @return boolean TRUE if the input is valid
 */
function valid_last_name($last_name)
{
    if (preg_match('/^(([A-Za-z]+)|\s{1}[A-Za-z]+)+$/', $_POST["last_name"]) && strlen($_POST["last_name"]) < 32)
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}


/**
 * Validate the student number
 * @param string $student_number the student_number post data
 * @return boolean TRUE if the input is valid
 */
function valid_student_num($student_number)
{
    if (preg_match('/^\d{9}$/', $_POST["student_number"]))
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}


/**
 * Validate the email
 * @param string $first_name the first_name post data
 * @return boolean TRUE if the input is valid
 */
function valid_email($email)
{
    if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) && strlen($_POST["email"]) < 64)
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}
?>