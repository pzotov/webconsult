# Тестовое задание для Webconsult

## Задание

Ниже представлена практическая задача. Вам нужно реализовать ее на YII2 с использованием ORM и Active record . Шаблон возможно использовать basic.

### Задача:
В Компании 3 менеджера по продажам. 

| № | ФИО          | Оклад(рублей) |
|---|--------------|---------------|
| 1 | Хельга Браун | 20 000        |
| 2 | Барак Обама  | 30 000        |
| 3 | Денис Козлов | 40 000        |

За каждый обработанный звонок, менеджер получает фиксированный бонус к окладу. Существует система увеличения бонуса в зависимости от общего количества обработанных звонков в месяц.

Таблица начислений бонусов:

| Шаг начисления за месяц | Название категорий | Бонусы начисления к окладу |
|-------------------------|--------------------|----------------------------|
| До 100(включительно)    | Начальная          | 100                        |
| До 200(включительно)    | Средняя            | 200                        |
| Более 300(включительно) | Высшая             | 300                        |

_**Примечание:** В данном месте неточность в условиях задачи, т.к. не указана категория, по которой будет рассчитываться бонус при количество звонков от 201 до 299 включительно. 
В приложении это реализовано как отдельная категория **"Недостающая категория"** с бонусом 0. Таким образом за звонки с 201-го по 299-й не начисляются бонусы._ 

Входные данные. Общая статистика посуточно за обработанные звонки

| День       | Менеджер 1 | Менеджер 2 | Менеджер 3 |
|------------|------------|------------|------------|
| 1.01.2015  | 10         | 10         | 10         |
| 2.01.2015  | 40         | 20         | 10         |
| 3.01.2015  | 40         | 10         | 10         |
| 4.01.2015  | 30         | 30         | 30         |
| 5.01.2015  | 10         | 10         | 10         |
| 6.01.2015  | выходной   | выходной   | выходной   |
| 7.01.2015  | выходной   | выходной   | выходной   |
| 8.01.2015  | 10         | 10         | 10         |
| 9.01.2015  | 20         | Не работал | 10         |
| 10.01.2015 | 30         | Не работал | 30         |
| 11.01.2015 | 10         | Не работал | 10         |
| 12.01.2015 | 20         | Не работал | 20         |
| 13.01.2015 | выходной   | выходной   | выходной   |
| 14.01.2015 | выходной   | выходной   | выходной   |

## Структура базы данных

~~~~
CREATE TABLE `bonus_category` (
  `id` int(11) NOT NULL,
  `bottom_line` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `manager` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `salary` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `stat` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `manager_id` int(11) NOT NULL DEFAULT '0',
  `calls` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `bonus_category`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `bottom_line` (`bottom_line`);

ALTER TABLE `manager`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `stat`
  ADD PRIMARY KEY (`id`), ADD KEY `manager_id` (`manager_id`), ADD KEY `date` (`date`);


ALTER TABLE `bonus_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `manager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `stat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
~~~~

## Итоговая ЗП менеджеров за январь 2015

| Менеджер     | Итоговая ЗП |
|--------------|-------------|
| Хельга Браун | 50000 руб.  |
| Барак Обама  | 39000 руб.  |
| Денис Козлов | 60000 руб.  |

## Как развернуть проект

Загрузите проект командами
~~~~
git clone https://github.com/pzotov/webconsult.git
cd webconsult/.yii
composer update
~~~~

Далее в файле _.yii/config/db.php_ укажите доступы к своей БД

Также работу данного приложения можно увидеть по адресу [https://webconsult.zotov.info/](https://webconsult.zotov.info/)

