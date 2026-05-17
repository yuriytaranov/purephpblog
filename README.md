# Запуск

### Сборка проекта
```
docker compose build
```

### Запуск контейнеров
```
docker compose up -d
```

### Установка зависимостей
```
docker compose exec app composer install
```

### Создание структуры БД
```
docker compose exec app php cli.php migrate
```

### Наполнение базы тестовыми данными
```
docker compose exec app php cli.php seed
```