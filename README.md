# AdsApp

AdsApp — система управления наружной рекламой, предназначенная для учета договоров, рекламных объектов, фотоотчетов и контроля их размещения.

---

## Возможности

### REST API

- Аутентификация через Laravel Sanctum
- Управление договорами
- Управление рекламными объектами
- Управление фотоотчетами
- Загрузка фотографий
- Получение фотографий
- Удаление фотографий

### Административная панель

Реализована на Filament.

Доступные справочники:

- Пользователи
- Роли
- Регионы
- Города
- Контрагенты
- Типы рекламы
- Статусы объектов
- Статусы фотоотчетов

---

## Архитектура

Проект состоит из двух независимых компонентов.

- **REST API** — реализует бизнес-логику приложения и предоставляет интерфейс для мобильного приложения, веб-клиента или внешних интеграций.
- **Filament** — административная панель для управления пользователями и справочниками.

Фотографии хранятся в файловой системе Laravel.

Для аутентификации API используется **Laravel Sanctum**.

---

## Технологии

- PHP 8.3
- Laravel 13
- MySQL 8.4
- Docker
- Filament 5
- Laravel Sanctum

---

## Запуск проекта

Все сервисы запускаются через Docker Compose.

```bash
git clone <repository>

cd AdsApp

cp .env.example .env

docker compose up -d

docker compose exec app composer install

docker compose exec app php artisan key:generate

docker compose exec app php artisan migrate

docker compose exec app php artisan storage:link
```

---

## Проверка качества кода

```bash
docker compose exec app composer check
```

Выполняются проверки:

- PHPStan
- PHPUnit

---

## Структура API

### Authentication

```text
POST   /api/login
GET    /api/me
POST   /api/logout
```

### Contracts

```text
GET        Получение списка
GET/{id}   Получение договора
POST       Создание договора
PUT        Обновление договора
DELETE     Удаление договора
```

### Advertising Objects

```text
GET        Получение списка
GET/{id}   Получение объекта
POST       Создание объекта
PUT        Обновление объекта
DELETE     Удаление объекта
```

### Photo Reports

```text
GET        Получение списка
GET/{id}   Получение фотоотчета
POST       Создание фотоотчета
PUT        Обновление фотоотчета
DELETE     Удаление фотоотчета
```

### Photos

```text
GET        Получение списка
GET/{id}   Получение фотографии
POST       Загрузка фотографии
DELETE     Удаление фотографии
```

---

## Хранение файлов

Фотографии сохраняются в:

```text
storage/app/public/photo-reports
```

После выполнения команды

```bash
php artisan storage:link
```

они доступны по адресу

```text
/storage/photo-reports
```

---

## Административная панель

Административная панель доступна по адресу:

```text
/admin
```

Реализована на **Filament**.

---

## License

This project is licensed under the MIT License.

---

## Автор

**Владимир Агаджанян**