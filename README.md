# Backend для системы учета рабочего времени сотрудников компании Magneex.
> Работает с репозиторием **Tuhtarov/magneex-frontend**

## Инициализация проекта:
> необходимо прописать следующие **консольные команды** в корне репозитория

### Устанавливаем пакеты:
```angular2html
composer install
```

### Инициализируем БД:
> строка подключения к БД в .env
> для выполнения команд необходима утилита symfony-cli
```shell
symfony console doctrine:database:create # создаём бд
```
```shell
symfony console doctrine:migrations:migrate # создаём актуальные таблицы
```
```shell
symfony console doctrine:fixtures:load # грузим тестовые данные
```

### Инициализируем realtime сервер Centrifuge (нужен docker) для трансляции QR
```shell
docker build -p 3000:3000 -t magneex-centrifuge centrifuge/
```
* для того, что бы у backend был доступ к centrifuge, необходимо сопоставить ключи из конфига centrifuge/centrifugo-config.json (TOKEN_HMAC_KEY и API_KEY) с ключами в .env 

### Генерируем обязательные файлы JWT для авторизации пользователей:
```shell
symfony console lexik:jwt:generate-keypair --overwrite
```



## 2 вариант: запуск backend сервера в docker контейнере на 8000 порту
```shell
docker build -t magneex-backend . 
```
```shell
docker run -p 8000:80 -d --rm --name magneex-backend magneex-backend
```
```shell
docker exec -it magneex-backend bash # входим в шелл сервера backend
```
> Инициализируем БД, утилита symfony предустановлена.
> Если нужно, правим .env файл, через команду: vim .env 
```shell
symfony console doctrine:database:create 
```
```shell
symfony console doctrine:migrations:migrate 
```
```shell
symfony console doctrine:fixtures:load 
```
```shell
symfony console lexik:jwt:generate-keypair --overwrite
```
```
exit # уходим обратно в host машину
```
### Пользуемся :)))

