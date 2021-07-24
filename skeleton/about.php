<?php

// execute the header script:
require_once "header.php";

// Display how to use the website using heredoc
echo <<<_END
    <div class="container">
        <h4>How to use this website</h4>
        <h5>Take a survey & see results</h5>
        <p>
            You can take all the active surveys whether you are logged in or not and view the results as well. To participate in a survey, do to active surveys page
            and click on Take survey button related to the survey that you want to take. The surveys can be activated and deactivated by the
            owner of the users when they are logged in.
            <br><br>
            You can also see the results by clicking on the see results button.
        </p>
        <h5>How to create a survey</h5>
        <p>
            To create a survey, you have to create an account and log in. On the My surveys website, click on create a survey button 
            which will take you to a page where you enter the title of the survey and instructions or a welcome message.
            <br><br>
            Click on Create and it will create an instance of the survey on the table in My survey page. To add questions, click on design
            survey button. This will take you to another page which will display you a table and a new question button at the bottom.
            If the question are already created, you can edit them as well and add options or change the question type rather than deleting the
            question and creating it from scratch.
            <br><br>
            You can also delete the questions by clicking the questions, If the survey was active and the survey had responses, all the responses
            corresponding to that questions will be deleted. If you delete the survey, all the questions and the responses will be deleted.
            <br><br>
            The surveys can also be activated and deactivated. Those activated will be shown in the list on active surveys list.
            It also generates a unique URL which can be shared to different users which can be used to go straign to the Survey by 
            entering on the browses.
        </p>
        <h5>Account Management and Admin Tools</h5>
        <p>
            The users can create accounts, update their profiles and change  their passwords.
            <br><br>
            The admin account can manage all the users accounts, change their passwords and also manage their surveys which
            meanse the ability to modify, create and delete users surveys and questions.
        </p>
    </div>
_END;
// finish of the HTML for this page:
require_once "footer.php";

?>