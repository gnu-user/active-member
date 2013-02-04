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

/**
 * A function which returns the access account number and full name of a user who
 * exists in the registered members table. Use this method to verify that the name
 * and student number provided belongs to a registered club member.
 * 
 * @param mysqli $mysqli_accounts The mysqli connection object for the ucsc accounts DB
 * @param string $first_name the first name of the individual
 * @param string $last_name the last name of the individual
 * @param string $student_number the student number of the individual
 * @param string $AES_KEY The AES encrypt/decrypt key for the password
 *
 * @return int An int containing the access account of the user if they exist, -1 otherwise
 */
function verify_member($mysqli_conn, $first_name, $last_name, $student_number, $AES_KEY)
{
    $members_table = 'ucsc_members';
    $member_name = $first_name . ' ' . $last_name;
    $access_account = -1;

    /* Get the student number and access account that matches the person's name */
    if ($stmt = $mysqli_conn->prepare("SELECT 
                                            AES_DECRYPT(student_id, ?), 
                                            access_account 
                                        FROM 
                                            ".$members_table." m 
                                        WHERE 
                                            CONCAT
                                                (
                                                    m.first_name, 
                                                    ' ', 
                                                    m.last_name
                                                ) 
                                            LIKE ?"))
    {
        /* bind parameters for markers */
        $stmt->bind_param('ss', $AES_KEY, $member_name);

        /* execute query */
        $stmt->execute();

        /* bind result variables */
        $stmt->bind_result($student_id, $cur_access_account);

        /* Check if the student id matches any of the club members with the same name */
        while ($stmt->fetch())
        {
            echo "student id: " . (string) $student_id;
            echo "cur access: " . $cur_access_account;
            /* Remove the salt from the student id and verify the student numbers match, 
             * use the access_account of matching student
             */
            if (strcmp($student_number, substr($student_id, 8)) === 0)
            {
                $access_account = $cur_access_account;
            }
        }

        /* close statement */
        $stmt->close();
    }

    return $access_account;
}



/**
 * TODO test and make sure it actually works
 * Used to check if a given nominee (with their first, last and user name) is a nominee
 * capable to be elected for the given position.
 *
 * @param mysqli $mysqli_elections The mysqli connection object for the ucsc elections DB
 * @param string $nominee the full name of the nominee
 * @param string $user_name the user name of a potential nominee
 * @param string $position the position that the nominee might hold
 * @return boolean $is_nominee TRUE if the nominee is found in the database with the given position
 */
function is_nominee($mysqli_elections, $nominee, $position)
{
    $is_nominee = FALSE;
    
    $current_year = date('Y');
    
    $members_table = "members_" . $current_year;
    $positions_nom_table = "positions_nom_" . $current_year;

    if ($stmt = $mysqli_elections->prepare("SELECT EXISTS(
                                                    SELECT 1 FROM ".$members_table." m INNER JOIN ".$positions_nom_table.
                                                        " p ON p.reference = m.access_account WHERE
                                                            p.position LIKE ? AND CONCAT
                                                                (
                                                                    m.first_name,
                                                                    ' ',
                                                                    m.last_name
                                                                )
                                                                LIKE ?
                                                             )"))
    {
        /* bind parameters for markers */
        $stmt->bind_param('ss', $position, $nominee);

        /* execute query */
        $stmt->execute();

        /* bind result variables */
        $stmt->bind_result($is_nominee);

        /* fetch value */
        $stmt->fetch();

        /* close statement */
        $stmt->close();
    }
    return $is_nominee;
}


/** 
 * A function which verifies the login information provided by the user
 * returns true if the login username and password provided are valid
 * 
 * @param mysqli $mysqli_accounts The mysqli connection object for the ucsc accounts DB
 * @param string $username The username of the person logging in
 * @param string $password The password of the person logging in
 * @param string $AES_KEY The AES encrypt/decrypt key for the password
 * @return boolean True if the login information provided is valid
 */
function verify_login($mysqli_accounts, $username, $password, $AES_KEY)
{
    $user_match = '';
    $pass_match = '';
    
    /* Get the username from the database if it exists */
    if ($stmt = $mysqli_accounts->prepare("SELECT username FROM ucsc_members WHERE username LIKE ?"))
    {
        /* bind parameters for markers */
        $stmt->bind_param('s', $username);

        /* execute query */
        $stmt->execute();

        /* bind result variables */
        $stmt->bind_result($user_match);

        /* fetch value */
        $stmt->fetch();

        /* close statement */
        $stmt->close();
    }
    
    /* If username found, verify the password provided for that username */
    if (strcasecmp($username, $user_match) === 0)
    {
        if ($stmt = $mysqli_accounts->prepare("SELECT AES_DECRYPT(password, ?) FROM ucsc_members WHERE username LIKE ?"))
        {
            /* bind parameters for markers */
            $stmt->bind_param('ss', $AES_KEY, $username);
    
            /* execute query */
            $stmt->execute();
    
            /* bind result variables */
            $stmt->bind_result($pass_match);
    
            /* fetch value */
            $stmt->fetch();
    
            /* close statement */
            $stmt->close();
        }
        /* Verify the password, remove the salt from password stored in DB */
       if (strcmp($password, substr($pass_match, 8)) === 0)
       {
           return true;
       }
    }
  
  /* Invalid username or password or both */
    return false;
}



?>