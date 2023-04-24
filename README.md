# LiveCoding
LiveCoding of Symfony with Docker

## Instructions for DB
The migration of the DB is created, only need to write the nexts commands in the php bash:

`php bin/console doctrine:database:create`

`php bin/console doctrine:migrations:migrate`

## Explanation of extra files
**Factory**

Is the folder where the own factory classes are created, in this folder we could find the Factory for create the connection to the DB.

**db.php**

Here goes the dependency injection for the container that has the DB connection.

## Evidencies for the challenge 3
**Create a new BlogPost**

`POST http://localhost:8080/blog`
[Link to image](https://drive.google.com/file/d/1QsHBA_nSeyneA4WXjPQX0g-eZ_o7Tcco/view?usp=share_link)

**Get the information of a blog spot (search it by slug)**

`POST http://localhost:8080/blog/new_people_in_miami`
[Link to image](https://drive.google.com/file/d/1m0Qh8a6CnZlnzpNGUqUR8SWOgponchs3/view?usp=share_link)

**Delete a blog spot using the slug**

`DELETE http://localhost:8080/blog/new_people_in_miami`
[Link to image](https://drive.google.com/file/d/1ZgKBmbpS7a9ZPCNWGhdAc7I9ohHbnVy9/view?usp=share_link)

**Create a new comment for a specific blog post**

`POST http://localhost:8080/comment`
[Link to image](https://drive.google.com/file/d/1pWVI6bD2xc622yFqMpQCMaQ9896Z3wG3/view?usp=share_link)

**Get the information for a comment (search it by id)**

`GET http://localhost:8080/comment/17`
[Link to image](https://drive.google.com/file/d/1WOf0WvD5GrFlMMwEDJsZUSqXLe5hlvDJ/view?usp=share_link)

**Delete a comment by the Id**

`DELETE http://localhost:8080/comment/3`
[Link to image](https://drive.google.com/file/d/1mBtUoeF_Tn_H749XpQlVlY_23uGUHoSo/view?usp=share_link)

**Get the list of comments for the specific post id, the list must be displayed by pages, and each page display only 10 comments at the time**

`GET http://localhost:8080/comment/post/1?page=1`
[Link to image](https://drive.google.com/file/d/1gzsQpfcc0rVF3plV8AoMh2kJtjgYaPGa/view?usp=share_link)