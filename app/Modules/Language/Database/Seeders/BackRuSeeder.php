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
                    'authentication' => '?????? ???????????? ?????? ???????????? ?? ?????????????????????????? ???????????????? ??????????????.',
                    'validation' => '?????????????????? ???????????? ??????????????.',
                    "requestValidation" => "???????????????????????? ???????????? ??????????????"
                ],
                'validation' => [
                    "fileModelExtension" => "???????????????? ???????????? ??????????. ?????????????????? ????????????: :extension.",
                    "phone"              => "\":input\", ???????????????? ???????????? ????????????????.",
                    "timeZone"           => "\":input\" ???????????????? ???????????? ???????????????? ??????????."
                ]
            ],
            'app' => [
                'position' => [
                    'errors' => [
                        'notFound' => '?????????????????? ???? ??????????????'
                    ]
                ],
                'mukhtasibat' => [
                    'errors' => [
                        'notFound' => '???????????????????? ???? ????????????'
                    ]
                ],
                'parish' => [
                    'errors' => [
                        'notFound' => '???????????? ???? ????????????'
                    ]
                ],
                'teacher' => [
                    'errors' => [
                        'notFound' => '?????????????? ???? ????????????'
                    ]
                ],
                'api' => [
                    'confirm' => [
                        'hashNotFound' => '???? ???????????? ?????????? ?????????? ??????????????????????????',
                        'hashExpired' => '?????????? ?????????????????????????? ??????????????',
                    ]
                ],
                'certificate' => [
                    'errors' => [
                        'notFound' => '???????????????????? ???? ????????????'
                    ]
                ],
                'lesson' => [
                    'errors' => [
                        'notFound' => '???????? ???? ????????????',
                        'topicNotFound' => '?????????? ???? ????????????',
                        'topicNotAccessible' => '???????? ?????????? ?????? ?????? ???? ????????????????',
                        'testNotAccessible' => '???????? ???????? ?????? ?????? ???? ????????????????',
                        'topicWithExistingPriority' => '?????????? ?? ?????????? ???????????????????? ?????????????? ?????? ????????????????????'
                    ],
                    'topic' => [
                        'types' => [
                            'type_video' => '??????????',
                            'type_text' => '????????????',
                            'type_audio' => '??????????',
                            'type_video_text' => '?????????? ?? ????????????',
                            'type_video_audio' => '?????????? ?? ??????????',
                            'type_text_audio' => '???????????? ?? ??????????',
                            'type_video_text_audio' => '??????????, ???????????? ?? ??????????',
                        ]
                    ]
                ],
                'test' => [
                    'errors' => [
                        'notFound' => '???????? ???? ????????????',
                    ],
                    'question' => [
                        'errors' => [
                            'notFound' => '???????????? ?? ?????????? ???? ????????????',
                            'questionWithExistingPriority' => '???????????? ?? ?????????? ???????????????????? ?????????????? ?????? ????????????????????'
                        ],
                    ],
                    'answer' => [
                        'errors' => [
                            'notFound' => '?????????? ?? ?????????????? ???? ????????????',
                            'cantDelete' => '???????????? ?????????????? ???????????????????? ??????????',
                        ],
                    ]
                ],
                'success' => '??????????????'
            ],
            'mainPage' => [
                'blocks' => [
                    'main' => [
                        'hint' => "???????? - ??????????????????",
                        'header' => "???????????????????? ???????? ???????????????????? ?????????? ?????? ????????????????????",
                        'content' => "???????????????????? ???????? ???????????????????? ?????????? ?? ?????????????? ????????????. ???????????????? ?????????????????? ???????? ?? ???????? ?????? ???????????????????????? ??????????????",
                    ],
                    'project' => [
                        'hint' => "???????? ???????? - ?????????????????? - ??????:",
                        'header' => "?? ?????????????? ",
                        'items' => [
                            '1' => "???????????? ?????????????????????????? ???????????????????? ??????????????????",
                            '2' => "???????????????????? ?????????????????????????? ????????????????",
                            '3' => "???????????????????? ???? IOS ?? Android",
                            '4' => "?????????????? ???? ?????????????? ?? ?????????????????? ????????????",
                        ]
                    ],
                    'aboutUs' => [
                        'hint' => "?? ??????",
                        'header' => "????????-?????????????????? (?????? - ??????????????) -",
                        'paragraphs' => [
                            '1' => "?????? ?????????????? ???????? ???????????????????? ??????????, ?????????????????????????? ?????????????????????????? ?????????????????????? ???????????????????? ?????????????????? ?????? ?????????????????? ?????????????????? ???????????????????? ?????????????????? ???????????????????? ??????????????????.",
                            '2' => "???????? ?????????????????? ?? ?????????????????? ?????????????? ???????????? ???????????????????? ??????????, ???????????????????? ?? ?????????????????????? ???????????????? ?????? ?????????????????????? ???? ???????????? ???????????????????? ??????????????????. ",
                            '3' => '?? ???????????? ?????????? ??? ?????????????? ?????????????? ????????????? ????????. ???????????? ?????????????????????? ?????????????("?????????????????? ????????. ?????? ????????????????????"). ?????????????????? ???????????????????????? ?????????? ???????????????????? ???? ???????????????? ???????????????????????? ???????????????????? ??????????, ???????????????? ?????????????????????? ?????????????????????? ?????????????? ?? ???????????????? ?????????????????????????????? ??????????????.',
                            '4' => "???????????????? ?????????????????? ???????? ?? ?????????? ?????????? ?? ?? ?????????? ??????????! ?????? ?????????????????????? ???????? ???????????????? ?? ????????????????! ",
                        ],
                    ],
                ]
            ],
            'user' => [
                "errors" => [
                    "emailExists" => "???????????????????????? ?? ?????????? ???????????? ?????? ??????????????????????????????"
                ],
                "profile" => [
                    "errors" => [
                        "notFilled" => "?????????? ?????????????????? ?????????????? ????????????????????????",
                        "certificateExists" => "?? ?????????????????????????? ?????? ???????? ????????????????????",
                        "incorrectNameValue" => "???????????????? ???????????? ??????",
                        "notExists" => "???????????? ???? ??????????????"
                    ]
                ],
                "registration" => [
                    "email" => [
                        "confirmed" => "Email ?????????????? ??????????????????????"
                    ],
                    "confirm" => [
                        "body" => "?????? ?????????????????????????? ?????????????????????? ???????????????? ???? ???????????? :link",
                        "title" => "?????????????????????? ??????????????????????"
                    ]
                ],
                "auth" => [
                    'logout' => [
                        'success' => '?????????? ?????????????? ??????????????????????'
                    ],
                    'errors' => [
                        'wrongEmail' => '???????????????? ??????????',
                        'incorrectStatus' => '?????? ???? ?????????????????? ??????????????????????',
                        'incorrectPassword' => '???????????????? ????????????',
                        'blocked' => '???????????????????????? ????????????????????????',
                        'needConfirm' => '???? ???? ?????????????????????? ??????????'
                    ]
                ],
                'resetPassword' => [
                    'errors' => [
                        'wrongEmail' => '???????????????????????? ?? ?????????? email ???? ??????????????????????????????'
                    ],
                    'email' => [
                        'title' => '???????????????????????????? ????????????',
                        'body' => '?????????????????? ???? ????????????, ?????????? ???????????? ?????????? ???????????? :link',
                        'sent' => '???? ?????????????????? ???????? ?????????? ?????????????? ???????????? ???? ?????????????? ???? ???????????????????????????? ????????????',
                    ],
                    'success' => '???????????? ?????????????? ??????????????'
                ],
                'changeEmail' => [
                    'email' => [
                        'title' => '?????????????????????????? ?????????? ??????????',
                        'body' => '?????????????????????? ??????????, ?????????????? ???? ???????????? :link',
                        'sent' => '???? ?????????????????? ???????? ?????????? ?????????????? ???????????? ???? ?????????????? ???? ?????????????????????????? ??????????'
                    ]
                ],
                'changePassword' => [
                    'success' => '???????????? ?????????????? ??????????????',
                    'errors' => [
                        'notSamePasswords' => '???????????? ???? ??????????????????',
                        'passwordInUse' => '???????????? ?????? ????????????????????????'
                    ]
                ],
                'email' => [
                    'confirmed' => '?????????? ?????????????? ????????????????????????',
                    'alreadyConfirmed' => '???????????????????????? ?????? ???????????????????? ??????????',
                    'confirmSent' => '???? ?????????????????? ???????? ?????????? ?????????????? ???????????? ???? ?????????????? ???? ?????????????????????????? ??????????'
                ]
            ],
            'socialMedia' => [
                'errors' => [
                    'invalidProvider' => '?????????????????????? ?????????????????? ??????. ????????',
                    'emailDoesNotExist' => '?????????????????????? email',
                    'userNotConfirmedEmail' => '???????? email ?????????????? ??????????????????????????',
                    'cantCreateUser' => '???? ?????????????? ???????????????????????????????? ????????????????????????',
                    'cantShowForm' => '???????????? ?????????????????????? ?????????? ?????????????????????? ??????. ???????? :provider',
                    'cantGetUserInfo' => '???? ???????????? ???????????????? ???????????? ?? ???????????????????????? ??????. ???????? :provider',
                ],
                'success' => '???????????????? ??????????????????????'
            ],
            'validation' => [
                "in" => "???????????????? ???????????????? ????????????????",
                "integer" => "?????? ???????? ???????????? ?????????????????? ?????????????????????????? ????????????????",
                "same" => "?????? ???????? ???????????? ?????????????????? ?????????? ???? ????????????????, ?????? ???????? :field",
                "requiredField" => "???????? :field ?????????????????????? ?????? ????????????????????",
	            "required" => "?????? ???????? ?????????????????????? ?????? ????????????????????",
                "agreement" => "?????????? ?????????????????????? ?? ?????????????????? ",
                "dateFormat" => "???????? ???????????? ?????????? ???????????? :format",
                'fileModelExtension' => '?????????????????? ?????????????????? ??????????????: :extension',
                'required_without_all' => '???????? ???? ?????????? :fields ???????????? ???????? ??????????????????',
                'integerField' => '???????? :field ???????????? ???????? ????????????????',
                'activeUrlField' => '???????? :field ???????????? ???????? ??????????????',
                'maxStringField' => '???????????????? ???????? :field ???????????????? ?????????????? ?????????? ????????????????',
                'incorrectDate' => '?????????????? ???? ???????????????????? ????????',
                'exists' => "???????????? ?? ?????????? ?????????????????? ???????? ???? ????????????????????",
                'max' => [
                    'string' => "?????????????????? ?????????????????????? ???????????????????? ??????????",
                ],
                'min' => [
                    'stringField' => '?????????????????????? ???????????????????? ?????????? ???????????? :min ????????????????'
                ],
                'email' => '???????????????? ???????????? ??????????'
            ],
            'columns' => [
                "tests" => [
                    "title" => "????????????????",
                    "status" => "????????????"
                ],
                "users" => [
                    "email" => "??????????",
                    "password" => "????????????",
                    "agreement" => "???????????????? ?? ??????????????????",
                    "repeatPassword" => "?????????????????????????? ????????????"
                ],
                "lessons" => [
                    "title" => "???????????????? ??????????",
                    "status" => "????????????",
                    "priority" => "???????????????????? ??????????"
                ],
                "parishes" => [
                    "title" => "????????????????????????",
                    "status" => "????????????"
                ],
                "teachers" => [
                    "name" => "??????",
                    "photo" => "????????",
                    "status" => "????????????",
                    "description" => "??????????????????"
                ],
                "positions" => [
                    "title" => "??????????????????",
                    "status" => "????????????"
                ],
                "dateFormat" => [
                    "ddMmYyyy" => "????.????.????????"
                ],
                "lessonTests" => [
                    "status" => "????????????",
                    "test_id" => "????????",
                    "lesson_id" => "????????"
                ],
                "testAnswers" => [
                    "answer" => "??????????",
                    "is_correct" => "?????????????? ???????????????????????? ????????????",
                    "question_id" => "????????????"
                ],
                "lessonTopics" => [
                    "audio" => "??????????",
                    "title" => "????????????????",
                    "video_url" => "???????????? ???? ??????????",
                    "content_text" => "??????????"
                ],
                "mukhtasibats" => [
                    "title" => "????????????????????",
                    "status" => "????????????"
                ],
                "testQuestions" => [
                    "test_id" => "????????",
                    "priority" => "??????????????",
                    "question" => "????????????",
                    "question_id" => "????????????"
                ],
                "topic_user_progress" => [
                    'topic_id' => '??????????',
                    'user_id' => '????????????',
                    'lesson_id' => '????????',
                ],
                'lesson_user_progress' => [
                    'user_id' => '????????????',
                    'lesson_id' => '????????',
                ],
                'lesson_topics' => [
                    'title' => '????????????????',
                    'video_title' => '???????????????? ??????????',
                    'content_title' => '?????????????????? ????????????',
                    'audio_title' => '?????????????????? ??????????',
                ]
            ],
            'auth' => [
                'failed' => '???????????? ???????????????? ?????????? ?????? ????????????'
            ]
        ];
    }
}
