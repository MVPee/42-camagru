all:	up

up:
		docker-compose up --build -d

down:
		docker-compose down

stop:
		docker-compose stop

clean:	down
		docker container prune --force

fclean:	clean
		docker system prune --all --force
		rm -rf data
		rm -rf php/public/uploads/*.png
re:	clean
	docker-compose up --build -d

.PHONY:		all up down clean fclean re
