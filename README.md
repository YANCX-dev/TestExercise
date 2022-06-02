# Не было реализовано
* Возвращения списка файлов и пагинация
* Тесты 

## Create draft [/api/v1/document]

### Клиент делает запрос на создание документа [POST]

+ Response 200

```js
{
    "document": {
        "id": "718ce61b-a669-45a6-8f31-32ba41f94784", 
        "status": "draft",
        "payload": {},
        "createAt": "2018-09-01 20:00:00+07:00",
        "modifyAt": "2018-09-01 20:00:00+07:00"
    }
}
```

## Get the document by id [/api/v1/document/{id}]

### Получение документа по id [GET]

+ Response 200 

```js
{
    "document": {
        "id": "01bdf523-bf13-4528-93f8-74f6e8c99b04",
        "status": "draft",
        "payload": [],
        "createAt": "2022-06-02T20:49:29+00:00",
        "modifyAt": "2022-06-02T20:49:29+00:00",
    }
}
```

+ Response 404

```js
{
    "File was not found"
}
```

## Editing the document [/api/v1/document/{id}]

### Редактирование документа [PATCH]

+ Request (application/json)

```js
    {
        "document": {
            "id": "718ce61b-a669-45a6-8f31-32ba41f94784",
            "status": "draft",
            "payload": {
            "actor": "The fox",
                "meta": {
                "type": "quick",
                "color": "brown",
            },
            "actions": [
                {
                    "action": "jump over",
                    "actor": "lazy dog",
                }
            ]
        },
            "createAt": "2018-09-01 20:00:00+07:00",
            "modifyAt": "2018-09-01 20:01:00+07:00",
    }
    }
```

+ Response 400


```js
{
    "Error. File already published"
}
```
+ Response 404

```js
{
    "File was not found"
}
```
## Publish the document [/api/v1/document/{id}/publish]

### Публикация документа [POST]

+ Response 200

```js
{
        "document": {
            "id": "718ce61b-a669-45a6-8f31-32ba41f94784",
            "status": "published",
            "payload": {
            "actor": "The fox",
                "meta": {
                "type": "cunning",
            },
            "actions": [
                {
                    "action": "eat",
                    "actor": "blob",
                },
                {
                    "action": "run away",
                }
            ]
        },
            "createAt": "2018-09-01 20:00:00+07:00",
            "modifyAt": "2018-09-01 20:03:00+07:00",
    }
}
```
+ Response 404

```js
{
    "File was not found"
}
```


