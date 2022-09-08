<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://wingassistant.com/wp-content/uploads/2022/07/zendesk-logo-1.png" width="400"></a></p>

## Code Challenge
 Implemented using 
 
 php 7.4.15 (cli)
 
 "laravel/framework": "^8.75" 
 
 mysql 8.0.19
 
##

### Challange
Register a user seedind the data or creating a user registration route, the main purpose of this project is allow the registered user 
upload a .pdf file, and allow the user list all the files uploaded, but he can not see others users uploads.

##

### Authentication

- **[JWT^1.0](https://jwt-auth.readthedocs.io/)**

### Models

Users 

Files
##

### Project routes 
Restful API with the following routes
  
api/auth/register
 
api/auth/login (generate token)

api/upload/store (token required) 

upload/list (token required)



