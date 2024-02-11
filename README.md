# Lara-Docker

An web application that has 2 pages, one(welcome page) has paginated actors in cards and by clicking on the card you go to second page(profile) in which you see the details of the actor.

I will provide also the .env to easily install the project.

To install the project.
1. Open Docker
2. Go on the Folder Lara-Docker and open console(cmd or git)
3. Execute these commands by copy pasting from my Makefile(I have also some others very helpful commands)
3.1-> docker-compose build --no-cache --force-rm
3.2-> docker-compose up -d
3.3-> docker exec laravel-docker bash -c "composer update"
3.4 (! to stop it) docker-compose stop
