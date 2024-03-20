build:
	docker compose build

start:
	docker compose up -d

stop:
	docker compose down

clean: db-clean
	docker rmi jeu-du-president-app
	docker rmi jeu-du-president-server
	docker rmi jeu-du-president-proxy

db-clean:
	sudo rm -rf ./db/data/
