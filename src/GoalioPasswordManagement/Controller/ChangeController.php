<?php

namespace GoalioPasswordManagement\Controller;

use GoalioPasswordManagement\Options\ModuleOptions;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;
use GoalioForgotPassword\Service\Password as PasswordService;
use ZfcUser\Controller\UserController;

class ChangeController extends AbstractActionController
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var PasswordService
     */
    protected $passwordService;

    /**
     * @var Form
     */
    protected $changePasswordForm;

    /**
     * @var ForgotControllerOptionsInterface
     */
    protected $options;

    public function forcechangeAction() {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(UserController::ROUTE_LOGIN);
        }

        $identity = $this->zfcUserAuthentication()->getIdentity();

        $service  = $this->getPasswordService();
        $password = $service->getPasswordMapper()->findByUser($identity);

        $redirect = $this->getRequest()->getRequestUri();
        return $this->redirect()->toUrl($this->url()->fromRoute('zfcuser/forcepasswordchange', array('userId' => $identity->getId(), 'token' => $password->getRequestKey())).($redirect ? '?redirect='.$redirect : ''));
    }


    public function changeAction()
    {
        $service = $this->getPasswordService();

        $userId    = $this->params()->fromRoute('userId', null);
        $token     = $this->params()->fromRoute('token', null);

        $passwordRequest = $service->getPasswordMapper()->findByUserIdRequestKey($userId, $token);

        //no request for a new password found
        if($passwordRequest === null || $passwordRequest == false) {
            return $this->redirect()->toRoute('zfcuser/login');
        }

        $form = $this->getChangePasswordForm();
        $prg = $this->prg(UserController::ROUTE_CHANGEPASSWD);

        $fm = $this->flashMessenger()->setNamespace('change-password')->getMessages();
        if (isset($fm[0])) {
            $status = $fm[0];
        } else {
            $status = null;
        }

        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg === false) {
            return array(
                'status' => $status,
                'changePasswordForm' => $form,
            );
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            return array(
                'status' => false,
                'changePasswordForm' => $form,
            );
        }

        if (!$this->getUserService()->changePassword($form->getData())) {
            return array(
                'status' => false,
                'changePasswordForm' => $form,
            );
        }

        $this->flashMessenger()->setNamespace('change-password')->addMessage(true);
        return $this->redirect()->toRoute(UserController::ROUTE_CHANGEPASSWD);
    }

    /**
     * Getters/setters for DI stuff
     */
    public function getUserService()
    {
        if (!$this->userService) {
            $this->userService = $this->getServiceLocator()->get('zfcuser_user_service');
        }
        return $this->userService;
    }

    public function setUserService(UserService $userService)
    {
        $this->userService = $userService;
        return $this;
    }

    public function getPasswordService()
    {
        if (!$this->passwordService) {
            $this->passwordService = $this->getServiceLocator()->get('goaliopasswordmanagement_password_service');
        }
        return $this->passwordService;
    }

    public function setPasswordService(PasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
        return $this;
    }

    public function getChangePasswordForm()
    {
        if (!$this->changePasswordForm) {
            $this->setChangePasswordForm($this->getServiceLocator()->get('goaliopasswordmanagement_change_form'));
        }
        return $this->changePasswordForm;
    }

    public function setChangePasswordForm(Form $changePasswordForm)
    {
        $this->changePasswordForm = $changePasswordForm;
        return $this;
    }

    public function setOptions(ForgotControllerOptionsInterface $options)
    {
        $this->options = $options;
        return $this;
    }

    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceLocator()->get('goaliopasswordmanagement_module_options'));
        }
        return $this->options;
    }
}
