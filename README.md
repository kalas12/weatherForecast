# Weather Forecast API

## Описание
Микросервис для агрегации данных прогноза погоды из нескольких свободных источников.

### Возможности:
- Добавление локаций для мониторинга погоды
- Получение среднего прогноза погоды для указанной локации за указанный период
- Фоновая джоба для сбора данных

## Стэк технологий
- PHP 8.2
- Laravel 11.9
- MySQL
- Docker

## API Swagger документация
http://localhost:8000/api/documentation#/Locations

## Установка и запуск

### 1. Клонирование репозитория
```bash
git clone https://github.com/kalas12/weatherForecast.git
```

### 2. Настройка окружения
Скопируйте файл .env.example в .env

### Заполните файл .env следующими переменными:

#### Параметры подключения к базе данных:
- DB_CONNECTION
- DB_HOST
- DB_DATABASE
- DB_USERNAME
- DB_PASSWORD

#### Ключи и урлы внешних сервисов
- WEATHERAPI_API_KEY
- WEATHERAPI_AP_BASE_URI
- VISUALCROSSING_API_KEY
- VISUALCROSSING_API_BASE_URI

### 3. Установка зависимостей
```bash
composer install
```
### 4. Запуск Docker-контейнеров
```bash
docker-compose up -d
```
### 5. Зайти в контейнер
```bash
docker-compose exec app bash
```
### 6. Миграции базы данных
```bash
php artisan migrate
```
### 7. Загрузка сидов
```bash
php artisan db:seed
```
### 8. Запуск фоновой задачи
```bash
php artisan queue:work
```
### 9. Запуск в ручном режиме Schedule для запроса в внешние сервисы
```bash
php artisan schedule:run
```
#### Настройка кроны автоматического запуска 
```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```
