<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            'goaliopasswordmanagement' => __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'goaliopasswordmanagement_change' => 'GoalioPasswordManagement\Controller\ChangeController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'zfcuser' => array(
                'child_routes' => array(
                    'forcepasswordchange' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/change-password/:userId/:token',
                            'defaults' => array(
                                'controller' => 'goaliopasswordmanagement_change',
                                'action'     => 'change',
                            ),
                            'constraints' => array(
                                'userId'  => '[A-Fa-f0-9]+',
                                'token' => '[A-F0-9]+',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);