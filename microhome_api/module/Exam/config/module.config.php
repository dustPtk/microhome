<?php


return array(
    'di'=>array(
        'services'=>array(

            'Exam\Model\ExamContentTable'=>'Exam\Model\ExamContentTable',
            'Exam\Model\ExamPaperTable'=>'Exam\Model\ExamPaperTable',
            'Exam\Model\ExamTopicTable'=>'Exam\model\ExamTopicTable',
            'Exam\Model\UserScoreTable'=>'Exam\Model\UserScoreTable'
        ),
    ),
    'router'=>array(
        'routes'=>array(
            //考试系统管理
            'exam'=>array(
                'type'=>'Segment',
                'options'=>array(
                    'route'=>'/api',
                    'constraints'=>array(
                        'id'=>'\d+',
                    ),
                    'defaults'=>array(
                        'controller'=>'Exam\Controller\Exam',
                    )
                ),
                'may_terminate' => true,

                'child_routes' => array(

                    //考试内容删除
                    'exam_content_del' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/exam_content_del',
                            'defaults' => array(
                                'controller'=>'Exam\Controller\Exam'
                            ),
                        ),

                    ),
                    //考试内容创建
                    'exam_content_cre' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/exam_content_cre',
                            'defaults' => array(
                                'controller'=>'Exam\Controller\Exam'
                            ),
                        ),

                    ),
                    //考试内容更新
                    'exam_content_up' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/exam_content_up',
                            'defaults' => array(
                                'controller'=>'Exam\Controller\Exam'
                            ),
                        ),
                    ),

                    //考试内容查询
                    'exam_content_sel' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/exam_content_sel[/:id]',
                            'defaults' => array(
                                'controller'=>'Exam\Controller\Exam'
                            ),
                        ),

                    ),
                    //题目删除
                    'exam_topic_del' => array(

                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/exam_topic_del',
                            'defaults' => array(
                                'controller'=>'Exam\Controller\TopicManage'
                            ),
                        ),

                    ),
                    //题目创建
                    'exam_topic_cre' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/exam_topic_cre',
                            'defaults' => array(
                                'controller'=>'Exam\Controller\TopicManage'
                            ),
                        ),

                    ),
                    //题目更新
                    'exam_topic_up' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/exam_topic_up',
                            'defaults' => array(
                                'controller'=>'Exam\Controller\TopicManage'
                            ),
                        ),

                    ),
                    //题目查询
                    'exam_topic_sel' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/exam_topic_sel[/:id]',
                            'defaults' => array(
//                                'controller'=>'Exam\Controller\Exam'
                                'controller'=>'Exam\Controller\TopicManage'
                            ),
                        ),
                    ),
                    //成绩删除
                    'exam_userscore_del' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/exam_userscore_del',
                            'defaults' => array(
                                'controller'=>'Exam\Controller\UserScore',                            ),
                        ),

                    ),
                    //成绩生成
                    'exam_userscore_cre' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/exam_userscore_cre',
                            'defaults' => array(
                                'controller'=>'Exam\Controller\UserScore',                            ),
                        ),

                    ),
                    //成绩更新
                    'exam_userscore_up' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/exam_userscore_up',
                            'defaults' => array(
                                'controller'=>'Exam\Controller\UserScore',                            ),
                        ),

                    ),
                    //成绩查询
                    'exam_userscore_sel' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/exam_userscore_sel[/:id]',
                            'defaults' => array(
                                'controller'=>'Exam\Controller\UserScore',                            ),
                        ),
                    ),

                    //试卷删除
                    'exam_paper_del' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/exam_paper_del',
                            'defaults' => array(
                                'controller'=>'Exam\Controller\PaperManage'
                            ),
                        ),

                    ),
                    //试卷创建
                    'exam_paper_cre' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/exam_paper_cre',
                            'defaults' => array(
                                'controller'=>'Exam\Controller\PaperManage'
                            ),
                        ),

                    ),
                    //试卷更新
                    'exam_paper_up' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/exam_paper_up',
                            'defaults' => array(
                                'controller'=>'Exam\Controller\PaperManage'
                            ),
                        ),
                    ),

                    //试卷查询
                    'exam_paper_sel' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/exam_paper_sel[/:id]',
                            'defaults' => array(
                                'controller'=>'Exam\Controller\PaperManage'
                            ),
                        ),

                    ),
                ),
            ),
        ),
    ),
    'controllers'=>array(
        'invokables'=>array(
            'Exam\Controller\Exam'=>'Exam\Controller\ExamController',
            'Exam\Controller\TopicManage'=>'Exam\Controller\TopicManageController',
            'Exam\Controller\PaperManage'=>'Exam\Controller\PaperManageController',
            'Exam\Controller\UserScore'=>'Exam\Controller\UserScoreController',
        ),
    ),
);