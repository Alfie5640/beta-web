# Web Project (BETA_WEB)

The objective of this project is to start learning about web development and how each piece connects. (full stack) I will learn about implementing a RESTful API securely using php. I plan to further my knowledge on relation databases (mysql) and handling private information securely. 

## Overview

So far I have:

- Implemented RESTful API endpoints   <br>
A backend which communicates with the database and does not use a session to store variables. (Stateless)

- Furthered Relational database knowledge  <br>
Created an entity relation diagram which connects certain fields within tables to show who each column (property) belongs to. (PK/FK)

- Implemented Prepared statements   <br>
Prevent against sql injection, by confirming a sql statement with the database before binding the variables to conditions.

- Implemented JWT  <br>
Allows storage of ‘global’ variables without the use of a session which keeps the api restful. Created by concatenating a header, payload and signature, encoded in base64 and with a secret key in a private .env file.


- Implemented Data sanitisation/validation  <br>
Further protects against sql injection by ensuring character length / character requirements are met. 


- Implemented Http response codes   <br>
Gives additional information to the end user and developer when an error occurs - different http codes have different meanings.

- Contributed to my Learning of version control using git / github

- NOTE that in this project my api endpoints are based on actions, whereas In the future, they will be revolving around resources.


## Structure

- `styles/` - Stylesheets
- `js/` - JavaScript logic
- `php/` - PHP backend scripts
- `api` - PHP scripts grouped on resources
- `sql/` - MySQL schema
- `docs/` - Gantt charts, wireframes, use case diagrams, etc.
