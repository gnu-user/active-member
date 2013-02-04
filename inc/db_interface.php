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
 * @param mysqli $mysqli_conn The mysqli connection object
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
 * A Function which determines if a user has already been set as active
 *
 * @param mysqli $mysqli_conn The mysqli connection object
 * @param int $access_Account The unique (primary key) access account number of the user
 * @return boolean True if the user is already active
 */
function is_active($mysqli_conn, $access_account)
{
    /* Set the active members table for current year */
    date_default_timezone_set('America/Toronto');
    $active_table = 'active_' . date('Y');
     
    if ($stmt = $mysqli_conn->prepare("SELECT 
                                                EXISTS(
                                                    SELECT 1
                                                    FROM "
                                                        . $active_table . 
                                                    " WHERE 
                                                        access_account=?
                                                )"))
                {
        /* bind parameters for markers */
        $stmt->bind_param('i', $access_account);

        /* execute query */
        $stmt->execute();

        /* bind result variables */
        $stmt->bind_result($is_active);

        /* fetch value */
        $stmt->fetch();

        /* close statement */
        $stmt->close();
    }

    return $is_active;
}


/**
 * A function which adds a person who is an active member to the active 
 * members table if they do not already exist in the table.
 *
 * @param mysqli $mysqli_conn The mysqli connection object
 * @param string $first_name the first name of the individual
 * @param string $last_name the last name of the individual
 * @param int $access_account The unique (primary key) access account number of the user
 *
 */
function add_member($mysqli_conn, $first_name, $last_name, $access_account)
{
    /* Set the active members table for current year */
    date_default_timezone_set('America/Toronto');
    $active_table = 'active_' . date('Y');

    /* Add the user to the active members table */
    if ($stmt = $mysqli_conn->prepare("INSERT INTO ".$active_table." VALUES (?, ?, ?)"))
    {
        /* bind parameters for markers */
        $stmt->bind_param('iss', $access_account, $first_name, $last_name);

        /* execute query */
        $stmt->execute();

        /* close statement */
        $stmt->close();
    }
}

?>