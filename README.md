# Jeu du président / President game

Mutiplayer online president card game - playable in a browser. Includes a game chat.

## Usage

| Command         | Description                |
| --------------- | -------------------------- |
| `make list`     | List all make instructions |
| `make build`    | Build Docker images        |
| `make start`    | Start Docker Compose       |
| `make stop`     | Stop Docker Compose        |
| `make db-clean` | Clean database data        |

Project is then accessible though : http://localhost:8080/.

## Docker containers

| Container | Description                                                       | Public port |
| --------- | ----------------------------------------------------------------- | ----------- |
| `app`     | App entrypoint. One index.html file working with Riot.js and AJAX | 8080        |
| `server`  | PHP REST API working as server                                    | 8081        |
| `psqldb`  | PostgreSQL for data storage                                       |             |
| `proxy`   | NGINX reverse proxy for CORS policies                             | 8082        |

## Screenshots

### Home page
![Capture d’écran du 2024-03-14 16-27-36](https://github.com/LoukaDOZ/jeu-du-president/assets/46566140/59d9270a-fc0c-46be-9cf3-8a8c52496ad6)

### Lobby
![Capture d’écran du 2024-03-14 16-30-38](https://github.com/LoukaDOZ/jeu-du-president/assets/46566140/2c7581c9-71af-4e75-bcb8-6e3f61aebbbb)

### Play screen
![Capture d’écran du 2024-03-14 16-30-54](https://github.com/LoukaDOZ/jeu-du-president/assets/46566140/4da2edd9-f312-493e-a5af-a865f567fd28)

### Highlighting playable cards based on the one selected
![Capture d’écran du 2024-03-14 16-31-10](https://github.com/LoukaDOZ/jeu-du-president/assets/46566140/5691c938-0a08-46b9-b29c-73a2a26249a4)

### Highlighting playable cards based one the stack
![Capture d’écran du 2024-03-14 16-31-34](https://github.com/LoukaDOZ/jeu-du-president/assets/46566140/3376207e-c2c8-4d54-ae28-1aea0560d632)

### Playing multiple cards
![Capture d’écran du 2024-03-14 16-31-17](https://github.com/LoukaDOZ/jeu-du-president/assets/46566140/a2fc8ec7-891c-476c-b1b7-189b80922497)

### Play same card or pass
![Capture d’écran du 2024-03-14 17-15-47](https://github.com/LoukaDOZ/jeu-du-president/assets/46566140/aaa6f118-9fb2-40e1-a0ee-e8b5f53803bc)

### Folding
![Capture d’écran du 2024-03-14 16-35-59](https://github.com/LoukaDOZ/jeu-du-president/assets/46566140/e2ed098c-faf3-4c8b-be30-6a2912c55359)

### Chat
![Capture d’écran du 2024-03-14 16-39-48](https://github.com/LoukaDOZ/jeu-du-president/assets/46566140/fc67c995-7f2f-4247-81d6-4c614e11688e)

### Chat notification
![Capture d’écran du 2024-03-14 16-40-07-2](https://github.com/LoukaDOZ/jeu-du-president/assets/46566140/c771ab70-cb21-4f56-87a1-b11f78e02039)

### Revolution
![Capture d’écran du 2024-03-14 16-41-54](https://github.com/LoukaDOZ/jeu-du-president/assets/46566140/00fbe1d2-416b-421c-be72-96721ce9400a)
![Capture d’écran du 2024-03-14 16-42-05](https://github.com/LoukaDOZ/jeu-du-president/assets/46566140/742e7f44-95dc-4d6f-ab30-d6783daa3e52)

### A player as finished
![Capture d’écran du 2024-03-14 16-44-13](https://github.com/LoukaDOZ/jeu-du-president/assets/46566140/1b296041-8b97-4c3c-811e-8c3703db5198)

### Ending screen
![Capture d’écran du 2024-03-14 16-44-53](https://github.com/LoukaDOZ/jeu-du-president/assets/46566140/1f9799a9-45bd-43ab-8d66-c613a6570f09)

### Exchanging cards at the begining of the next games based on position from last game
![Capture d’écran du 2024-03-14 16-45-27](https://github.com/LoukaDOZ/jeu-du-president/assets/46566140/cfbe7417-f619-4ed4-aabf-554a3d958dac)
