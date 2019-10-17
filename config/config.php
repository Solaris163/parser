<?php

define("DIR_ROOT", $_SERVER['DOCUMENT_ROOT']); //коорневая папка
define("DS", DIRECTORY_SEPARATOR);
define("SITE_URL", 'https://www.marathonbet.ru'); //url домашней страницы сайта, откуда парсятся старницы
define("LIST_URL", 'https://www.marathonbet.ru/su/events.htm?id=11'); //url страницы со списком футбольных матчей
define("GET_REQUEST_NEXT_PAGE_START", 'page='); //начало get-запроса для запроса следующей страницы
define("GET_REQUEST_NEXT_PAGE_END", 'pageAction=getPage'); //окончание get-запроса для запроса следующей страницы
define("NEXT_PAGE", 3); //страница, с которой нужно начинать get-запросы для запроса следующей страницы