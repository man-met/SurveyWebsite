6G5Z2107 - 2CWK50 - 2018/19
<StudentName> Muhammad Qasim Khokhar Awan
<StudentNumber> 14050520


SETUP:
...
1) Download the Zip folder and unzip all the files
2) Copy the PHPASSESSMENT FOLDER into htdocs
3) Run the following url on the browser after running XAMPP
URL = http://localhost/PHPAssessment/skeleton/create_data.php
This might take a few seconds to create the dummy data...
4) Once the page creates the data go to the sign up page
sign_up page = http://localhost/PHPAssessment/skeleton/sign_up.php
5) Now you are ready to use the website.
 You can create you own account and use it.

USERNAMES           PASSWORDS
admin               secret
barrym              letmein
a                   test
b                   test
briang              password
mandyb              abc123
timmy               secret95

DOCUMENTATION:
...
SIGNING UP
The user can create its own personal account by going on to the sign up page by entering 
username and the password, username cannot be the one that already exists
sign_up page = http://localhost/PHPAssessment/skeleton/sign_up.php

...
SIGNING IN
The user can sign in to his account by going into the sign in page
Sign in page = http://localhost/PHPAssessment/skeleton/sign_in.php

...
UPDATE ACCOUNT DETAILS
After signing in the user goes into the login page where the user can update the profile details and password

To update profile click on update profile button on the accounts.php page

To update the password the user has to verify first by entering the current password and then entering the password the user would like to change it to

...
MY SURVEYS PAGE
In my surveys page, you can create, edit, delete, activate and share a survey by using the URL which can be seen in the last field.
The user can also export a CSV file once the user starts receiving the responses from the respondents and the user can 
also see the number of responses to the survey on the table.

To create a new survey, you click on the create survey button, where you can set the title and a welcome message or a instructions for the participant of the survey.

...
ADDING QUESTIONS INTO THE SURVEY
The user can add a range of questions by clicking on the design survey and new question button on the questions page.
The user is also able to edit the questions that are already existing in the survey

There are four types of questions available to choose from at the moment which include
1) Multiple choice
2) Dropdown
3) Short Answer
4) Paragraph

The use can edit and delete questions rather than deleting and starting the survey from scratch.

...
SURVEY TEMPLATES
Survey templates are available at the bottom of the surveys_manage.php for all the users to use as an example.

...
ACTIVE SURVEYS
The survey website consists of an active surveys page which can be accessed to the users whether they are loggedin or not which means
that the surveys are open and anyone can participate.

To take part in a survey, you have to click on the Take Survey button on the active_surveys.php page which will take you through
each question one by one and it ends up by submitting the responses.

The results of the survey can be seen by clicking on the see results button on the active surveys page which displays the results of the survey
and displays the charts on the page along with summaries and number of times an option has been chosen.

The user is also able to see total number of responses given to each question and number of times the same options is being selected.

...
ADMIN TOOLS
The user of the admin account is able to update the profile and the passwords of the user. The admin can update the passwords of the other users without
entering their current password while when updating the admins own password the admin has to enter his own current password.

The admin is also able to select a user from a dropdown list and create, edit, delete, activate, share and more using their username from admins own account.
The admin is able to manage the accounts and the surveys and is able to amend anything.

MORE FEATURES WITH MORE QUESTION TYPES COMING SOON :)