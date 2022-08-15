<?php


namespace Modules\Language\Database\Seeders;


use Illuminate\Database\Query\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BackRuSeeder extends Seeder
{
    public function run(): void
    {
        $defaultMessages = $this->getDefaultMessages();
        $currentMessages = $this->getCurrentMessages();
        $messages = $this->merge($defaultMessages, $currentMessages);
        $this->upsert($messages);
    }

    /**
     * @return array
     */
    private function getCurrentMessages(): array
    {
        $values = $this->getQuery()
            ->select('message_values')
            ->where('type', 'backend')
            ->where('code', 'RU')
            ->first();

        return json_decode($values->message_values, true);
    }

    /**
     * @param array $defaultMessages
     * @param array $currentMessages
     * @return array
     */
    private function merge(array $defaultMessages, array $currentMessages): array
    {
        $res = [];
        foreach ($defaultMessages as $key => $messages) {
            $newMessage = $messages;
            $currentMessage = null;
            if (is_array($currentMessages)) {
                foreach ($currentMessages as $curKey => $curMessage) {
                    if ($curKey == $key) {
                        $currentMessage = $curMessage;
                    }
                }
            }
            if (is_array($messages) && is_array($currentMessage)) {
                $newMessage = $this->merge($messages, $currentMessage);
            } elseif (!is_null($currentMessage)) {
                $newMessage = $currentMessage;
            }
            $res[$key] = $newMessage;
        }

        return $res;
    }

    /**
     * @param array $messages
     */
    private function upsert(array $messages)
    {
        $this->getQuery()->updateOrInsert(
            [
                'code' => 'RU',
                'type' => 'backend'
            ],
            [
                'code' => 'RU',
                'type' => 'backend',
                'message_values' => json_encode($messages)
            ]
        );
    }

    /**
     * @return Builder
     */
    private function getQuery(): Builder
    {
        return DB::table('app_language_messages');
    }

    private function getDefaultMessages(): array
    {
        return [
            'netiLibrary' => [
                'errorHandler' => [
                    'authentication' => 'Ваш запрос был сделан с недопустимыми учетными данными.',
                    'validation' => 'Указанные данные неверны.',
                    "requestValidation" => "Некорректные данные запроса"
                ],
                'validation' => [
                    "fileModelExtension" => "Неверный формат файла. Разрешены только: :extension.",
                    "phone"              => "\":input\", неверный формат телефона.",
                    "timeZone"           => "\":input\" неверный формат часового пояса."
                ]
            ],
            'app' => [
                'position' => [
                    'errors' => [
                        'notFound' => 'Должность не найдена'
                    ]
                ],
                'mukhtasibat' => [
                    'errors' => [
                        'notFound' => 'Мухтасибат не найден'
                    ]
                ],
                'parish' => [
                    'errors' => [
                        'notFound' => 'Приход не найден'
                    ]
                ],
                'teacher' => [
                    'errors' => [
                        'notFound' => 'Учитель не найден'
                    ]
                ],
                'api' => [
                    'confirm' => [
                        'hashNotFound' => 'Не удалсь найти токен подтверждения',
                        'hashExpired' => 'Время подтверждения истекло',
                    ]
                ],
                'certificate' => [
                    'errors' => [
                        'notFound' => 'Сертификат не найден'
                    ]
                ],
                'lesson' => [
                    'errors' => [
                        'notFound' => 'Урок не найден',
                        'topicNotFound' => 'Топик не найден',
                        'topicNotAccessible' => 'Этот топик вам еще не доступен',
                        'testNotAccessible' => 'Этот тест вам еще не доступен',
                        'topicWithExistingPriority' => 'Топик с таким порядковым номером уже существует'
                    ],
                    'topic' => [
                        'types' => [
                            'type_video' => 'Видео',
                            'type_text' => 'Чтение',
                            'type_audio' => 'Аудио',
                            'type_video_text' => 'Видео и чтение',
                            'type_video_audio' => 'Видео и аудио',
                            'type_text_audio' => 'Чтение и аудио',
                            'type_video_text_audio' => 'Видео, чтение и аудио',
                        ]
                    ]
                ],
                'test' => [
                    'errors' => [
                        'notFound' => 'Тест не найден',
                    ],
                    'question' => [
                        'errors' => [
                            'notFound' => 'Вопрос к тесту не найден',
                            'questionWithExistingPriority' => 'Вопрос с таким порядковым номером уже существует'
                        ],
                    ],
                    'answer' => [
                        'errors' => [
                            'notFound' => 'Ответ к вопросу не найден',
                            'cantDelete' => 'Нельзя удалить правильный ответ',
                        ],
                    ]
                ],
                'success' => 'Успешно'
            ],
            'mainPage' => [
                'blocks' => [
                    'main' => [
                        'hint' => "«Без - татарлар»",
                        'header' => "Бесплатный курс татарского языка для начинающих",
                        'content' => "Популярный курс татарского языка в формате онлайн. Изучайте татарский язык с нуля под руководством опытных",
                    ],
                    'project' => [
                        'hint' => "Курс «Без - татарлар» - это:",
                        'header' => "О проекте ",
                        'items' => [
                            '1' => "Лучшие преподаватели Республики Татарстан",
                            '2' => "Бесплатное дистанционное обучение",
                            '3' => "Приложения на IOS и Android",
                            '4' => "Контент на русском и татарском языках",
                        ]
                    ],
                    'aboutUs' => [
                        'hint' => "О НАС",
                        'header' => "«Без-татарлар» («Мы - татары») -",
                        'paragraphs' => [
                            '1' => "это базовый курс татарского языка, разработанный специалистами Российского исламского института при поддержке Духовного управления мусульман Республики Татарстан.",
                            '2' => "Курс позволяет с легкостью освоить основы татарского языка, грамматики и религиозной риторики вне зависимости от уровня подготовки слушателя. ",
                            '3' => 'В основе курса – учебное пособие “Татар теле. Башлап өйрәнүчеләр өчен” ("Татарский язык. Для начинающих"). Программа интенсивного курса направлена на изучение разговорного татарского языка, освоение ситуативных лексических наборов и развитие коммуникативных навыков.',
                            '4' => "Изучайте татарский язык в любом месте и в любое время! Вам понадобятся лишь смартфон и интернет! ",
                        ],
                    ],
                ]
            ],
            'user' => [
                "errors" => [
                    "emailExists" => "Пользователь с такой почтой уже зарегистрирован"
                ],
                "profile" => [
                    "errors" => [
                        "notFilled" => "Нужно заполнить профиль пользователя",
                        "certificateExists" => "У пользователся уже есть сертификат",
                        "incorrectNameValue" => "Неверный формат ФИО",
                        "notExists" => "Запись не найдена"
                    ]
                ],
                "registration" => [
                    "email" => [
                        "confirmed" => "Email успешно подтверждён"
                    ],
                    "confirm" => [
                        "body" => "Для подтверждения регистрации прейдите по ссылке :link",
                        "title" => "Подтвердите регистрацию"
                    ]
                ],
                "auth" => [
                    'logout' => [
                        'success' => 'Выход успешно осуществлён'
                    ],
                    'errors' => [
                        'wrongEmail' => 'Неверный логин',
                        'incorrectStatus' => 'Вам не разрешена авторизация',
                        'incorrectPassword' => 'Неверный пароль',
                        'blocked' => 'Пользователь заблокирован',
                        'needConfirm' => 'Вы не подтвердили почту'
                    ]
                ],
                'resetPassword' => [
                    'errors' => [
                        'wrongEmail' => 'Пользователь с таким email не зарегистрирован'
                    ],
                    'email' => [
                        'title' => 'Восстановление пароля',
                        'body' => 'Перейдите по ссылке, чтобы задать новый пароль :link',
                        'sent' => 'На указанную вами почту выслано письмо со ссылкой на восстановление пароля',
                    ],
                    'success' => 'Пароль успешно изменён'
                ],
                'changeEmail' => [
                    'email' => [
                        'title' => 'Подвтерждение новой почты',
                        'body' => 'Подтвердите почту, перейдя по ссылке :link',
                        'sent' => 'На указанную вами почту выслано письмо со ссылкой на подтверждение почты'
                    ]
                ],
                'changePassword' => [
                    'success' => 'Пароль успешно изменён',
                    'errors' => [
                        'notSamePasswords' => 'Пароли не совпадают',
                        'passwordInUse' => 'Пароль уже используется'
                    ]
                ],
                'email' => [
                    'confirmed' => 'Почта успешно подтверждена',
                    'alreadyConfirmed' => 'Пользователь уже подтвердил почту',
                    'confirmSent' => 'На указанную вами почту выслано письмо со ссылкой на подтверждение почты'
                ]
            ],
            'socialMedia' => [
                'errors' => [
                    'invalidProvider' => 'Неизвестный провайдер соц. сети',
                    'emailDoesNotExist' => 'Отсутствует email',
                    'userNotConfirmedEmail' => 'Этот email ожидает подтверждения',
                    'cantCreateUser' => 'Не удалось зарегистрировать пользователя',
                    'cantShowForm' => 'Ошибка отображения формы авторизации соц. сети :provider',
                    'cantGetUserInfo' => 'Не удалсь получить данные о пользователе соц. сети :provider',
                ],
                'success' => 'Успешная авторизация'
            ],
            'validation' => [
                "in" => "Передано неверное значение",
                "integer" => "Это поле должно содержать целочисленное значение",
                "same" => "Это поле должно содержать такое же значение, как поле :field",
                "requiredField" => "Поле :field обязательно для заполнения",
	            "required" => "Это поле обязательно для заполнения",
                "agreement" => "Нужно согласиться с условиями ",
                "dateFormat" => "Дата должна иметь формат :format",
                'fileModelExtension' => 'Разрешены следующие форматы: :extension',
                'required_without_all' => 'Одно из полей :fields должно быть заполнено',
                'integerField' => 'Поле :field должно быть числовым',
                'activeUrlField' => 'Поле :field должно быть ссылкой',
                'maxStringField' => 'Значение поля :field содержит слишком много символов',
                'incorrectDate' => 'Введена не допустимая дата',
                'exists' => "Запись с таким значением поля не существует",
                'max' => [
                    'string' => "Превышена максимально допустимая длина",
                ],
                'min' => [
                    'stringField' => 'Минимальная допустимая длина строки :min символов'
                ],
                'email' => 'Неверный формат почты'
            ],
            'columns' => [
                "tests" => [
                    "title" => "Название",
                    "status" => "Статус"
                ],
                "users" => [
                    "email" => "Почта",
                    "password" => "Пароль",
                    "agreement" => "Согласие с условиями",
                    "repeatPassword" => "Подтверждение пароля"
                ],
                "lessons" => [
                    "title" => "Название урока",
                    "status" => "Статус",
                    "priority" => "Порядковый номер"
                ],
                "parishes" => [
                    "title" => "Наименование",
                    "status" => "Статус"
                ],
                "teachers" => [
                    "name" => "ФИО",
                    "photo" => "Фото",
                    "status" => "Статус",
                    "description" => "Должность"
                ],
                "positions" => [
                    "title" => "Должность",
                    "status" => "Статус"
                ],
                "dateFormat" => [
                    "ddMmYyyy" => "дд.мм.гггг"
                ],
                "lessonTests" => [
                    "status" => "Статус",
                    "test_id" => "Тест",
                    "lesson_id" => "Урок"
                ],
                "testAnswers" => [
                    "answer" => "Ответ",
                    "is_correct" => "Отметка правильности ответа",
                    "question_id" => "Вопрос"
                ],
                "lessonTopics" => [
                    "audio" => "Аудио",
                    "title" => "Название",
                    "video_url" => "Ссылка на видео",
                    "content_text" => "Текст"
                ],
                "mukhtasibats" => [
                    "title" => "Мухтасибат",
                    "status" => "Статус"
                ],
                "testQuestions" => [
                    "test_id" => "Тест",
                    "priority" => "Порядок",
                    "question" => "Вопрос",
                    "question_id" => "Вопрос"
                ],
                "topic_user_progress" => [
                    'topic_id' => 'Топик',
                    'user_id' => 'Ученик',
                    'lesson_id' => 'Урок',
                ],
                'lesson_user_progress' => [
                    'user_id' => 'Ученик',
                    'lesson_id' => 'Урок',
                ],
                'lesson_topics' => [
                    'title' => 'Название',
                    'video_title' => 'Название видео',
                    'content_title' => 'Заголовок текста',
                    'audio_title' => 'Заголовок аудио',
                ]
            ],
            'auth' => [
                'failed' => 'Введен неверный логин или пароль'
            ]
        ];
    }
}
