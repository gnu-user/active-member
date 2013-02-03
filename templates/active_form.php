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

/*
 * The active members form, the user uses it to simply verify that they show
 * interest in the club and are active members by entering one or more pieces
 * of identifiable information such as their name or student id.
 *
 * DEPENDENCIES
 * ------------
 * 
 * This template uses one of the post variables to indicate an error if the
 * criteria was not found (such as a student's email or id).
 * 
 * $first_name
 * $last_name
 * $student_number
 * $email
 *
 */

?>
<section id="register">
    <div class="page-header">
        <h1>Active Member Form</h1>
    </div>
    <div class="row">
        <div class="span8">
            <!--  Display an error if they entered invalid credentials -->
            <?php
                if (isset($invalid))
                {
                    echo '<div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>Invalid Information Procided!</strong> The information you provided is not valid
                            please enter valid information.
                          </div>';
                }
                elseif (isset($notmember))
                {
                    echo '<div id="notmember" class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>No registered club member found for that information!</strong> No registered club
                            member was found matching the information provided, if you are a registered club member
                            then please contact <a href="mailto:admin@cs-club.ca">admin@cs-club.ca</a>
                          </div>';
                }
                elseif (isset($isactive))
                {
                    echo '<div id="active" class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>You are now active!</strong> Thank you, you have now been marked as active, your
                            name will be submitted to the Student Association.
                          </div>';
                }
                else
                {
                    echo '<div id="activeinfo" class="alert alert-info">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <p>
                                Submit <strong>one or more</strong> of the following pieces of information about 
                                yourself to nominate yourself as an active club member if you are <strong>already 
                                a club member</strong>.
                            </p>
                            <p>
                                You may only nominate yourself as an active club member if you are <strong>genuinely interested
                                in the club</strong> and are <strong>currently enrolled at UOIT/DC</strong>.
                            </p>
                          </div>';
                }
            ?>
            <form class="well form-horizontal" action="active.php" method="post" accept-charset="UTF-8">
                <fieldset>
                    <!--  First & Last Name -->
                    <div class="control-group">
                        <label for="first_name" class="control-label">First Name:</label>                
                        <div class="controls">
                            <input id="first_name" name="first_name" required type="text" maxlength="31" pattern="^(([A-Za-z]+)|\s{1}[A-Za-z]+)+$" placeholder="First name..."/>            
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="last_name" class="control-label">Last Name:</label>             
                        <div class="controls">
                            <input id="last_name" name="last_name" required type="text" maxlength="31" pattern="^(([A-Za-z]+)|\s{1}[A-Za-z]+)+$" placeholder="Last name..."/>                       
                        </div>
                    </div>
                    <!-- Enter your student number... -->
                    <div class="control-group">
                        <label for="student_number" class="control-label">Student Number:</label>               
                        <div class="controls">
                            <input type="text" id="student_number" name="student_number" maxlength="9" pattern="^\d{9}$" placeholder="100123456..."/>              
                        </div>
                    </div>
                    <!-- Enter Email Address-->
                    <div class="control-group">
                        <label for="email" class="control-label" >Email Address:</label>               
                        <div class="controls">
                            <input type="text" id="email" name="email" maxlength="63" pattern="^.+@(.+\..+)+$" placeholder="something@email.com..."/>              
                        </div>
                    </div>
                    <!-- Sign Up -->
                    <div class="control-group">
                        <div class="controls">
                            <button type="submit" id="active_user" name="active_user" class="btn btn-inverse">I'm Active!</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</section>
