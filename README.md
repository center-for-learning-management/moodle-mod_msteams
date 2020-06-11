# moodle-mod_msteams

## General information

This plugin combines two existing sources:
* It uses the url module as a basement and works like that.
* It is inspired by the [atto_teamsmeeting-Plugin](https://github.com/enovation/moodle-atto_teamsmeeting) that allows the schedule a Microsoft Teams Meeting using an external tool called "[meetingapp](https://github.com/OfficeDev/msteams-app-lms-meetings#frequently-asked-questions)"

## Prerequisites

You need a working installation of [meetingapp](https://github.com/OfficeDev/msteams-app-lms-meetings#frequently-asked-questions).
You can install it on your own infrastructure using a prepared tool from enovation -->
[more info](https://github.com/enovation/msteams-app-lms-meetings), or use the pre-defined app provided by enovation.

## How does it work?

Simply add the MS Teams activity to your course page. It allows you to schedule an MS Teams meeting within an iframe and catches
the meeting url, which is stored as url-parameter in a hidden form field.

That way this activity works exactly as the url module with the only difference, that the URL is directly retrieved from Microsoft Teams.

Here are some impressions:

1. Create meeting
![Step 1](/doc/msteams-1.png)
2. Enter meeting details
![Step 2](/doc/msteams-2.png)
3. Fill out module creation form
![Step 3](/doc/msteams-3.png)
4. Click the activity on the course page to enter meeting
![Step 4](/doc/msteams-4.png)
