<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;

        $sellerRole = $auth->createRole('seller');
        $auth->add($sellerRole);

        $adminRole = $auth->createRole('admin');
        $auth->add($adminRole);


        // add "createOrder" permission
        $createOrder = $auth->createPermission('createOrder');
        $createOrder->description = 'Create a order';
        $auth->add($createOrder);
        $auth->addChild($adminRole, $createOrder);
        $auth->addChild($sellerRole, $createOrder);


        // add "manageOrder" permission
        $manageOrder = $auth->createPermission('manageOrder');
        $manageOrder->description = 'Manage orders';
        $auth->add($manageOrder);
        $auth->addChild($adminRole, $manageOrder);



        // add "viewPackaging" permission
        $viewPackaging = $auth->createPermission('viewPackaging');
        $viewPackaging->description = 'View packaging';
        $auth->add($viewPackaging);
        $auth->addChild($adminRole, $viewPackaging);
        $auth->addChild($sellerRole, $viewPackaging);


        // add "managePackaging" permission
        $managePackaging = $auth->createPermission('managePackaging');
        $managePackaging->description = 'Manage packaging';
        $auth->add($managePackaging);
        $auth->addChild($adminRole, $managePackaging);


        // add "managePos" permission
        $managePos = $auth->createPermission('managePos');
        $managePos->description = 'Manage points of sale';
        $auth->add($managePos);
        $auth->addChild($adminRole, $managePos);

        // add "manageProduct" permission
        $manageProduct = $auth->createPermission('manageProduct');
        $manageProduct->description = 'Manage products';
        $auth->add($manageProduct);
        $auth->addChild($adminRole, $manageProduct);

        // add "manageSeller" permission
        $manageSeller = $auth->createPermission('manageSeller');
        $manageSeller->description = 'Manage sellers';
        $auth->add($manageSeller);
        $auth->addChild($adminRole, $manageSeller);

        // add "manageSupply" permission
        $manageSupply = $auth->createPermission('manageSupply');
        $manageSupply->description = 'Manage supply';
        $auth->add($manageSupply);
        $auth->addChild($adminRole, $manageSupply);

        // add "manageSysuser" permission
        $manageSysuser = $auth->createPermission('manageSysuser');
        $manageSysuser->description = 'Manage users';
        $auth->add($manageSysuser);
        $auth->addChild($adminRole, $manageSysuser);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        //$auth->assign($author, 2);
        //$auth->assign($admin, 1);
    }
}