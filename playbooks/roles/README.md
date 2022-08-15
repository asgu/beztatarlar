Репозитарий ролей Ansible   

# Комманда на подключение сабмодуля (Роли)
git submodule add -b prod ../../internal/roles.git playbooks/roles

# Комманда обновления сабмодуля (Роли)   
git submodule update --recursive --remote

# Роль Base
Базовые пакеты для сборки, распаковки скачивания и т.д.   

# Роль Laravel
Требует переменной:    
`document_root` - Путь к проекту

# Роль mailcatcher
Роль перехватчика писем.   
listen 0.0.0.0:25 smtp    
listen 0.0.0.0:1080 http   
Порт 1080 не открываем, оборачиваем в nginx в домен mail-*   

# Роль php
Требует переменной:   
в файле backend/conf/vagrant-config.yml   
`php_default_version` - Версия php   
Минимум: 5.6   
Максимум: 8.0   

# Роль Mysql
Требует переменной:   
в файле backend/conf/vagrant-config.yml   
`mysql_default_version` - Версия Mysql   
Минимум: 5.7   
Максимум: 8.0   

# Роль supervisor
Конфиги берет из папки supervisor (playbooks/supervisor)   
Имя и расширение фалов конфига queue.conf (без j2)   