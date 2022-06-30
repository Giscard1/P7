Projet 7 OpenClassrooms - 

Installation

Step 1 : Clone the project.

Step 2 : Run in your console "composer install".

Step 3 : Open the .env file go to the line 32 and change the database connection information. 

Step 4 : Write in your console 
         - "php bin/console doctrine:database:create"
         - "php bin/console doctrine:migrations:migrate"
         
Step 5 : For random initial dataset run in your console :
         - "php bin/console doctrine:fixtures:load"
                   
Step 6 : Have fun.

