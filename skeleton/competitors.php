<?php

// Things to notice:
// You need to add your Analysis and Design element of the coursework to this script
// There are lots of web-based survey tools out there already.
// It’s a great idea to create trial accounts so that you can research these systems. 
// This will help you to shape your own designs and functionality. 
// Your analysis of competitor sites should follow an approach that you can decide for yourself. 
// Examining each site and evaluating it against a common set of criteria will make it easier for you to draw comparisons between them. 
// You should use client-side code (i.e., HTML5/JavaScript/jQuery) to help you organise and present your information and analysis 
// For example, using tables, bullet point lists, images, hyperlinking to relevant materials, etc.

// execute the header script:
require_once "header.php";

// declare the variable
$option = "";

// is the user logged in?
if (!isset($_SESSION['loggedInSkeleton']))
{
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
}
else
{
	// user is logged in so display the design and the analysis of different websites...
	// Design and analysis
	echo "<h3>Design and Analysis</h3>";
	// display the button on the browser
	echo <<<_END
	<form action="competitors.php" method="post">
		<input type="submit" name="option" value="Design and Analysis">
		<input type="submit" name="option" value="Question Types">
		<input type="submit" name="option" value="Survey Monkey">
		<input type="submit" name="option" value="Google Forms">
		<input type="submit" name="option" value="Lime Survey">
		<input type="submit" name="option" value="Survey Planet">
		<input type="submit" name="option" value="Conclusion">
	</form>
_END;

	// is the option set using the post method?
	if(isset($_POST['option']))
	{
		// store the option sent via post method into the variable
		$option = $_POST['option'];
	}

	// check the option stored in the variabel and run the corresponding case
	switch($option)
	{
		case "Survey Monkey": surveyMonkey();
			break;
		
		case "Google Forms": googleForms();
			break;

		case "Lime Survey": limeSurvey();
			break;

		case "Survey Planet": surveyPlanet();
			break;

		case "Question Types": questionTypes();
			break;

		case "Conclusion": conclusion();
			break;

		default: designAndAnalysis();
			break;
	}
}

function designAndAnalysis()
{
	echo <<<_END
	<div class="container">
		<h4>Design and Analysis</h4>
		<p>
			During the design and analysis, I made a research on different survey websites which include Survey Monkey, Google Forms, Lime Survey and Survey Planet. To 
			design my website, I took ideas from these websites. For example, the idea of managing the account, profile and the password, was taken from Google. When 
			updating the password, the use is asked to enter their current password, which can be seen implemented when you update the password. However, if using the 
			administrator account, the administrator can change the passwords of the users without inputting their current password and when changing the admin account’s 
			own password, the admin has to input the current password.
		</p>
		<p>
			After researching different survey websites, I learned that survey planet’s website is the easiest to use and the design is simple and easy. To create the 
			survey in survey planet, you click on a button which displays a form where you have to enter the Title and instructions. Similarly, in this website you can 
			enter the title and a welcome message in order to create the survey. The process of adding questions into the survey is also inspired from survey planet 
			website. Once you create the survey and you open it to add questions, you have a new question button. When you press this button, it will take you to 
			another page where you enter the question, select the question type and enter the options or enter the instructions into the question.
		</p>
		<p>
			You can see the research completed of each competitor and the conclusion by clicking on the corresponding button above.
		</p>
	</div>
_END;
}

function questionTypes()
{
	echo <<<_END
	<div class="container">
		<h4>Question Types</h4>
		<p>
			There are many types of questions that can be used in a survey. Below is a list of most popular question types:
			<ul>
				<li><b>Multiple choice questions:</b> These can be divided into a number of subcategories:</li>
				<ul>
					<li><b><i>Single answer vs multiple answers:</i></b>
						<p>
							The most popular multiple-choice question is the singe answer question. They use radio buttons, circles and the respondent can choose one option 
							from the given list. These are powerful tool for binary questions, nominal scales and questions with ratings. Multiple answers on the other hand 
							use checkboxes or square boxes and the respondent can choose more than one answer from the given list.
						</p>
					</li>
					<li><b><i>Other answer option: </i></b>
						<p>
							One of the issues with the multiple-choice questions is that the respondent is forced to select from one of the options and this can affect 
							the accuracy of the results. As a solution to this “other” option can be added with an optional comment box.
						</p>
					</li>
					<li><b><i>Rating scales: </i></b>
						<p>
							In rating scales question type, a scale of answer options is displayed from any range 1-10, 1-100, Very Poor-Excellent etc. The respondent 
							selects one of the options that best suits. In these types of questions, it is important to give a context to the respondent and explain the 
							values of the scale.
						</p>
					</li>
					<li><b><i>Likert scales: </i></b>
						<p>
							Likert scales are used to gauge the opinions, feeling and likeness and unlikeliness of the respondent. The answers displayed are a scale 
							with a range of answers, from Strongly disagree to Strongly agree or Very unlikely to very likely.
						</p>
					</li>
					<li><b><i>Matrix: </i></b>
						<p>
							When a survey consists of a series of questions, with same response options e.g. likert scales or rating scales, these questions are known as matrix question.
						</p>
					</li>
					<li><b><i>Dropdown:</i></b>
						<p>
							Dropdown questions allow the respondent to select one of the answers from a long list of options which can be scrolled up and down
						</p>
					</li>
				</ul>
				<li>
					<b>Open ended questions: </b>
					<p>
						In these types of questions, user has to type an answer into a comment box. However, according to survey monkey, open ended questions are not 
						the best option if you are looking to analyse the data.
					</p>
				</li>
				<li>
					<b>Demographic questions: </b>
					<p>
						If you are looking to find out more about your target audience, demographic questions are the best option. These types of questions are used 
						when you are interested in gathering information about your target audience background, income level, age group and more. Best option to find 
						our what your target audience do.
					</p>
				</li>
			</ul>
		</p>
	</div>
_END;
}

function surveyMonkey()
{
	echo <<<_END
	<div class="container">
		<h4>Survey Monkey</h4>
		<h5>User Account Setup/Login Process</h5>
		<p>
			The process of signing up is easy in survey monkey. As you go to the homepage of the website you are shown two options to login 
			and to sign up. When you click on sign-up page, it takes to the sign-up page in which the user is asked to enter the details. The 
			details include:
			<ul>
				<li>Username</li>
				<li>Password</li>
				<li>Email</li>
				<li>First Name</li>
				<li>Last Name</li>
			</ul>
		</p>
		<p>
			After filling the form, the user clicks on the sign-up button and the page is redirected to the dashboard. The process takes no 
			more than five minutes and it is a very easy process. 
		</p>
		<p>
			It also gives options to sign-up using social media accounts and directly from email address. When you click on a social media or 
			email account button, it takes to the account page you have clicked on and all you must do is login to that service and give 
			permissions or access to you email or social media account.
		</p>
		<p>
		To login to your account, you have to input your username and the password or you can also login using your social media account or 
		the email address. You have the option to remember your details to login quicker next time.
		</p>
		<h5>Layout / Presentation of surveys</h5>
		<p>
			Survey monkey has a very simple layout and presentation of surveys. You one question at a time and as you respond to that question, 
			it moves the page to the next question. When all the questions in the page are answered you press to the next button which is at 
			the bottom of the page. If logic is placed on one of the questions and you select the corresponding answer, it will take you to the 
			page that has been assigned next to show the questions on that page. The design of the presentation and layout is easy with no 
			distractions and it is easy to navigate from one question to another and from one page to another.
		</p>
		<h5>Question Types</h5>
		<p>
			Survey monkey offers more than 15 different question types which include multiple choice, text box, Net Promoter score and A/B 
			comparisons. Survey monkey also has over 1600 questions written by experts which can be used for the surveys.
			Here is a list of question types that are offered by survey monkey:
			<ol>
				<li>Multiple Choice</li>
				<li>Checkboxes</li>
				<li>Star Rating</li>
				<li>Dropdown</li>
				<li>Matrix/Rating Scale</li>
				<li>File Upload</li>
				<li>Net Promoter Score</li>
				<li>Image Choice</li>
				<li>Comment Box</li>
				<li>Single Textbox</li>
				<li>Slider</li>
				<li>Ranking</li>
				<li>Payment Stripe</li>
				<li>Matrix of dropdown</li>
				<li>Multiple Textboxes</li>
				<li>Date / Time</li>
				<li>Contact Information</li>
				<li>Text</li>
				<li>Image</li>
				<li>Text A/B Questions</li>
				<li>Image A/B Questions</li>
			</ol>
		</p>
		<h5>Ease of Use</h5>
		<p>
			The features in survey monkey are easily accessible. To create a new survey, you have a button available in every page once you are 
			logged in which can be seen on the top of the page in navigation bar. You click the button and displays options i.e. “Start from 
			scratch” or copy existing survey. To create a new survey, you click on start from scratch and you are asked to enter the name and a 
			category of the survey from a drop-down menu. As you press the Create Survey button once the fields are filled, you are ready to 
			populate your survey with questions.
		</p>
		<p>
			All the features and are then accessible on the same page. On the top you have all the management tools such as Summary, Design, 
			Preview, Responses, Analysis of results and Present results. On the design sections, all the features are easily accessible on the 
			left which include the question bank, builder that consists of all the question types, appearance, logic and options or setting for 
			the survey. You also have the add new question button on the body of the survey at the bottom of the last questions. The question types 
			and answers are available next to each question field and answer field which are easily accessible. When adding the options, it creates 
			addition field automatically under the last field you are editing automatically. The logic feature is accessible from each question 
			menu which next to each question area along with other options as you can see in the screenshot below. All the features are easily 
			#accessible and using this service is very easy.
		</p>
		<p>
			Preview of the survey, collecting the responses, analysing the results and present results options are on the top and easily accessible 
			as mentioned before.
		</p>
		<img src="img/survey_monkey.jpg" width="1020" height="488" alt="Survey monkey website screenshot">
	</div>
_END;
}

function googleForms()
{
	echo <<<_END
	<div class="container">
		<h4>Google Forms</h4>
		<h5>User Account Setup/Login Process</h5>
		<p>
			Google forms is a service from Google. To use Google forms, you are required to create a Google account or login using your 
			existing Google Account. To create a google account, you have to fill a form which consists of the following details: 
			<ul>
				<li>First Name</li>
				<li>Last Name</li>
				<li>Username/Email Address</li>
				<li>Password</li>
				<li>Date of Birth</li>
				<li>Gender</li>
			</ul>
			Optional details:
			<ul>
				<li>Telephone number</li>
				<li>Recovery Email Address</li>
			</ul>
		</p>
		<p>
			Once you fill these details in two separate pages, it takes you to terms and conditions and privacy page where you have to choose 
			the privacy options and agree to the terms and conditions and finish by clicking on Create account button. It is not a long 
			process, however you have to fill some additional fields which a person would not like to fill and the process is completed on 
			more than three pages.
		</p>
		<p>
			To login to the google forms service, all you have to do is input your username and password.
		</p>
		<h5>Layout / Presentation of surveys</h5>
		<p>
			When taking a survey that is created using Google Forms, the questions are displayed on the page without any other elements on 
			the page. You will see the questions and answers only. To answer the questions, user has to scroll down the page and answer 
			each question which means that it does not move to the next question automatically. At the very bottom you can see the next 
			button which takes you to the next page of the survey. Depending on the answers in the previous page, it will take you to the 
			page according to the logic that is set. Once you finish the survey you press the submit button which stores your results.
		</p>
		<h5>Question Types</h5>
		<p>
			Google forms offers a range of question types, however it lacks some of the most popular question types that were mentioned 
			earlier e.g. demographic questions.<br>
			Below you can see a list of question types offered by Google forms:
			<ol>
				<li>Short Answer</li>
				<li>Paragraph</li>
				<li>Multiple Choice</li>
				<li>Checkboxes</li>
				<li>Dropdown</li>
				<li>File Upload</li>
				<li>Linear Scale</li>
				<li>Multiple Choice Grid</li>
				<li>Checkbox Grid</li>
				<li>Date</li>
				<li>Time</li>
			</ol>
		</p>
		<h5>Ease of Use</h5>
		<p>
			Creating a survey using Google form is very easy. On the Google Forms website homepage, you will find some templates and Start a 
			new form option with blank title at the bottom. You click on that and you are ready to create a new survey or form.
		</p>
			<img src="img/google_forms.jpg" width="1020" height="488" alt="Google Forms website screenshot">
		<p>
			As you can see in the picture, you have all the options and features accessible on a single page with a send button on the top. 
			On the left there is a small bar to add questions, title and description, images and videos and a page break or a section. 
			Logic is accessible from the bottom left of the question section or next to the required toggle button.
		</p>
		<p>
			You can preview the survey or the form by clicking on the eye button on the top and sharing is as easy as clicking on the send 
			button and selecting how to share i.e. using email, link, social media etc. The responses or results can be seen next to the 
			questions on the top.
		</p>
		<p>
			Google forms is easy to use, all features are accessible, sharing and analysing the results is easy as well. You do not have to 
			jump from one page to another to access all the features and have everything on the same page which makes it simple and easy to 
			use.
		</p>
		
	</div>
_END;
}

function limeSurvey()
{
	echo <<<_END
	<div class="container">
		<h4>Lime Survey</h4>
		<h5>User Account Setup/Login Process</h5>
		<p>
			Upon landing on the Lime Survey homepage, you can see options to register and to login with some additional menu options. The log in and sign up buttons are 
			quite visible with a register button on the body of the page. To sign up you have to fill the following fields:
			<ul>
				<li>Username</li>
				<li>Email</li>
				<li>Password</li>
			</ul>
		</p>
		<p>
			When you click on to the Sign-up button, it takes you to a page where you are shown a message to validate you email to complete the sign-up process. 
			#This requires you to log in to your email, open the email received from lime survey and clicking on the link in the email. The link takes to another 
			page that displays the following message:<br><br>

			<b>“Thank you for confirming your email address. You have been automatically logged in!”</b>
		</p>
		<p>
			The whole process of signing up takes a while as you have to login to the email and confirm you email address.
		</p>
		<p>
			There are two options to login to the website, either using your username and password or using your email or social media account.
		</p>
		<h5>Layout / Presentation of surveys</h5>
		<p>
			When you take a survey created using lime survey, a description of the survey is displayed with optional welcome message. As you click on next, you can 
			see only one question at a time. After every response, you click on next button which takes you to the next question. The layout is simple, the questions 
			and answers are presented in a nice way and one question per page. It finishes the survey with an end message where the user can see a thank you for taking 
			the survey message.
		</p>
		<h5>Question Types</h5>
		<p>
			Lime survey has a range of question which are categorised in different section. You can see the categories of questions and subcategories below:
			<ul>
				<li>Single Choice questions</li>
				<ul>
					<li>5-Point Choice</li>
					<li>List Dropdown</li>
					<li>List Radio</li>
					<li>List with Comment</li>
				</ul>
				<li>Arrays</li>
				<ul>
					<li>10-Point Choice/5-Point Choice</li>
					<li>Increase/Same/Decrease</li>
					<li>Numbers</li>
					<li>Texts</li>
					<li>Yes/No/Uncertain</li>
					<li>Array By Column</li>
					<li>Array Dual Scale</li>
				</ul>
				<li>Mask Questions</li>
				<ul>
					<li>Date/Time</li>
					<li>Equation</li>
					<li>File Upload</li>
					<li>Gender</li>
					<li>Language Switch</li>
					<li>Multiple Numerical Input</li>
					<li>Numerical Input</li>
					<li>Ranking</li>
					<li>Text Display</li>
					<li>Yes/No</li>
				</ul>
				<li>Text Questions</li>
				<ul>
					<li>Huge Free Text</li>
					<li>Long Free Text</li>
					<li>Multiple Short Text</li>
					<li>Short Free Text</li>
				</ul>
				<li>Multiple Choice Questions</li>
				<ul>
					<li>Multiple Choice</li>
					<li>Multiple Choice With Comments</li>
				</ul>
			</ul>
		</p>
		<p>
			As it can be seen, lime survey has a range of questions, however, with some of the lists above, it is not very clear what kind 
			of questions they are unless the user selects and tests it. This might consume some time when creating surveys. Lime survey has 
			all the popular question types available for the users.
		</p>
		<h5>Ease of Use</h5>
		<p>
			Lime survey has a different process of creating a survey compared to most of the survey websites. To start creating a website, 
			you have to login to the website and create an instance or create a survey application environment. Here you set the 
			administration URL and a user URL is automatically generated. It takes a while to setup the environment. Once the environment 
			is ready, you click on the administration URL which directs to another page where you have to login again using username and the 
			password. Once logged in to that page you, a menu will be displayed with different options which can be seen in the image below. 
			To start creating a survey, you click on the create survey button and you are redirected onto another page where you have to go 
			through all the settings for your page. Complete the setting save and now you are ready to create your survey. This process is very 
			long and is very time consuming when your start using this service.
		</p>
			<img src="img/lime_survey.jpg" width="1020" height="488" alt="Lime Survey website screenshot">
		<p>
			Once you complete the process you are on the page where you can start creating the survey. The tools and the features for 
			creating the survey are not accessible easily and you have to jump from one question group to another. The questions, answers, 
			logic, statistics and design tools are available on one page, however, to save the questions, moving from one question group to 
			another, previewing the survey and sending requires the user to navigate which as mentioned earlier is time consuming and can 
			be confusing and difficult for some people to use. Lime survey has a complex design of the survey website which makes it 
			difficult to use. Below you can see a screenshot which reflects the complexity of the website.
		</p>
		<p>
			<img src="img/lime_survey_website.jpg" width="1020" height="488" alt="Lime Survey website screenshot">
		</p>
		<br><br>
	</div>
_END;
}

function surveyPlanet()
{
	echo <<<_END
	<div class="container">
		<h4>Survey Planet</h4>
		<h5>User Account Setup/Login Process</h5>
		<p>
			Survey Planet’s homepage has link to sign-up page on both the body and on the navigation bar. When you click on the page, a 
			simple form is displayed that has following fields:
			<ul>
				<li>Full Name</li>
				<li>Email</li>
				<li>Verify Email</li>
				<li>Password</li>
			</ul>
		</p>
		<p>
			It also has a captcha to make sure it is not a robot signing up. When you verify that you are not a robot you can click on to 
			the sign-up button. The sign-up button link takes you to the same sign-up page but has a message on the top of the form:
			<br><br>
			<b>“Welcome to Survey Planet! A confirmation email has been sent to qasim.ak786@gmail.com, please click on the confirmation link in the email to activate your account.”</b>
		</p>
		<p>
			In order to activate your account, you have to confirm your email address.  In order to confirm you have to login into the email 
			address, open the email received and click on the link received from the website. After clicking the link, you are taken to a 
			page which tells you that your email has been verified and you can now login and create surveys with a link to login page. After 
			confirming the email address, you have to input your email and the password in order to login.
		</p>
		<h5>Layout / Presentation of surveys</h5>
		<p>
			Survey planet starts the survey with a message and a begin button at the beginning. One question is shown at a time with a next 
			button at the bottom. Once a question has been answered, you might not have option to go back and amend your answers. At the end 
			of the survey you click on submit button and a message is displayed that says thank you for your time. The questions are presented 
			nicely, and the design of the page is simple with no distractions. It moves from one question to the next one very smoothly.
		</p>
		<h5>Question Types</h5>
		<p>
			Survey planet offers the most popular question types and some additional question types.
			<br>
			The list can be seen below:
			<ol>
				<li>Multiple Choice</li>
				<li>Essay</li>
				<li>Rating</li>
				<li>Scale</li>
				<li>Form</li>
				<li>Scoring</li>
				<li>Range</li>
				<li>Date/Time</li>
				<li>Image Choice</li>
			</ol>
			<br>
			The process of selecting the question type is very simple and easy.
		</p>
		<h5>Ease of Use</h5>
		<p>
			Survey planet website has an easy access to create a website. Once you are logged in, you can see the surveys that are already 
			being created along with an option to create a new survey. You click on New survey, enter the title and welcome message and 
			finishing pressing on the save button which takes you to a page where you can start adding the question (See the picture below).
		</p>
			<img src="img/survey_planet.jpg" width="1020" height="488" alt="Google Forms website screenshot">
		<p>
			As you can see in the image, on the left you have a menu which makes the navigation easy. You can access the settings, questions 
			of the survey to add, edit and view, themes, preview of the survey, sharing and results.  To add a new question, you can click on 
			the button which is present on the body of the page which takes you to the page where you can type in your page, choose the type of 
			the question and for every question that you add, you have to go to the previous page and follow the same process. To add logic, you 
			have to click on question branching.
		</p>
		<p>
			The tools are very accessible, and the website is easy to use. However, you have to navigate from one page to another to access 
			and implement the features.
		</p>
	</div>
_END;
}

function conclusion()
{
	echo <<<_END
	<div class="container">
		<h4>Conclusion</h4>
		<p>
			After looking on the various aspects of different survey websites, Google forms and survey planet website design is simple and the service is very easy to 
			use compared to the survey monkey and Lime survey website that have a very complex design and you have to spend a fair amount of time to understand how to 
			create questions, understand different types of question and creating logic for questions. After creating a survey in Google forms, you can create questions, 
			pages, create logic and preview the survey on the same page, which eliminates the work of navigating from one page to another. Survey planet which also has a 
			simple and sleek design, to create the survey and questions as well as the logic, you have to navigate through different pages which consumes time.
		</p>
		<p>
			The survey monkey and lime survey on the other hand have a very complex design, however the user has a range of question types to choose from with analysis 
			tools. Survey monkey has a very easy process of setting up the account. The downside of using survey monkey is that you have to pay for some services such as 
			logic, which if compared to Google Forms is not as good as Google forms service is free.
		</p>
		<p>
			Lime survey has a very complex process of setting up an account. Once you are signed up on the website, you have to create an instance of the account. This 
			takes you to another page where you have to enter the username and the password again. When creating a survey, you have to go through a range of options before 
			you start adding the questions.
		</p>
		<p>
			To conclude, I would say that the Google Forms website is easiest to use compared to the other websites while survey monkey has a lot more different types of 
			questions compared to Google Forms. Survey Monkey also has a simple design, however if you compare it to Google Forms, Google forms has all the question types 
			that are offered by survey planet and is easier to use.
		</p>
	</div
_END;
}
// finish off the HTML for this page:
require_once "footer.php";
?>