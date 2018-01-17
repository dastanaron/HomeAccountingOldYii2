#!/usr/bin/env bash

config[0]='dastanaron' #name for git
config[1]='flow199@yandex.ru' #email to git configure
config[2]='git@github.com:dastanaron/HomeAccounting.git' #repository

parseFolder() {

echo $1 | awk -F "[\/\.]" '/\//{print $3}'

}

pathToProject() {
    folder=$(parseFolder ${config[2]})

    #path=`echo $(dirname "$(readlink -e "$0")")/$folder`
    path=`echo $(pwd)/$folder`
    echo -e "\033[36;1mПуть до проекта $path, если он не верен, то введите путь, если верен, то просто нажмите\033[0m \033[31mEnter\033[0m"

    read new_path

    if [ "$new_path" != "" ]
        then
        path=$new_path
    fi

    cd $path
}
stepsInstall() {

    if [ "$1" = "--step=1" ]
    then
        git config --global user.name ${config[0]}
        git config --global user.email ${config[1]}
        echo -e "\033[36;1mGit сконфигурирован \033[0m"
    fi

    if [ "$1" = "--step=2" ]
    then
        echo -e "\033[36mЗапущено клонирование \033[0m"
        git clone ${config[2]}
    fi

    if [ "$1" = "--step=3" ]
    then
        echo "\033[36;1mДанный этап не требует перезапуска, путь будет проверен при поэтапном запуске везде, где он будет требоваться\033[0m"
    fi

    if [ "$1" = "--step=4" ]
    then
        pathToProject
        echo -e "\033[36mНачинаю обновление библиотек\033[0m"
        composer update
    fi

    if [ "$1" = "--step=5" ]
    then
        pathToProject

        ./init

        echo -e "\
\033[36m
теперь сконфигурируйте базу данных и пропишите настройку в
common/config/main-local.php,
после этого, запустите ./yii migrate или далее отвечайте да, на все вопросы установщика\033[0m"
    fi

    if [ "$1" = "--step=6" ]
    then
        pathToProject
        nano common/config/main-local.php
    fi

    if [ "$1" = "--step=7" ]
    then
        pathToProject
        ./yii migrate
    fi

    if [ "$1" = "--help" ]
    then
        echo -e "\
        \033[36;1m
        Список команд:
            --help - выведет текущее сообщение
            --step - выбирает нужный этап,
                передается так: --step=1 [где \"1\" - это номер этапа]
                \033[0m
            "
    fi

}

    if [ "$1" != "" ]
    then
        stepsInstall $1
        exit
    fi


#Конфигурация GIT (step 1)
stepsInstall --step=1

#Клонирование репозитория (step2)

stepsInstall --step=2

#Путь к проекту (step3)
pathToProject


#Запуск композера (step4)_
echo -e "\033[36mНачинаю обновление библиотек\033[0m"
composer update

#Инициализация (step5)

echo -e "\033[36;1mПриготовьтесь выбирать версию программы\033[0m"

./init

echo -e "\
\033[36m
теперь сконфигурируйте базу данных и пропишите настройку в
common/config/main-local.php,
после этого, запустите ./yii migrate или далее отвечайте да, на все вопросы установщика\033[0m"


#Редактирование конфига БД (step6)
echo -e "\033[36;1mХотите открыть конфиг в редакторе?\033[0m[y/n]"

read answer

if [[ $answer = "y" ]]
    then
    nano common/config/main-local.php
fi

#Запуск миграции (step7)
echo -e "\033[36;1mКонфиг сохранен, приступаем к миграциям \033[0m"

./yii migrate

echo -e "\
\033[36;1m
Если скрипт провалился на каком то из этапов,
вы можете запустить команду с аргументом --step=\$s, где \$s это номер этапа и данный этап будет выполнен заново

Этапы:
    1. Конфигурация GIT
    2. Клонирование репозитория
    3. Путь к проекту
    4. Запуск композера
    5. Инициализация
    6. Редактирование конфига БД
    7. Запуск миграции
\033[0m
"



while true
do
echo -e "\033[36;1mВведите номер этапа и скрипт выполнит ее.\033[0m Или введите \"q\"-для выхода, \"l\" - для вывода списка этапов"

read step
if [ "$step" = "q" ]
    then exit
fi

if [ "$step" = "l" ]
    then
    echo -e "\
\033[36;1m
Этапы:
    1. Конфигурация GIT
    2. Клонирование репозитория
    3. Путь к проекту
    4. Запуск композера
    5. Инициализация
    6. Редактирование конфига БД
    7. Запуск миграции
\033[0m
"
fi

if [ "$step" != "" ]
    then
    stepsInstall --step=$step
fi
done





