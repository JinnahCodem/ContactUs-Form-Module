<?php
declare(strict_types=1);

namespace Codem\ContactUs\Controller\Index;

use Codem\ContactUs\Api\ContactRepositoryInterface;
use Codem\ContactUs\Helper\Email;
use Codem\ContactUs\Model\ContactsFactory;
use Magento\Captcha\Helper\Data as CaptchaHelper;
use Magento\Captcha\Model\DefaultModel as CaptchaModel;
use Magento\Captcha\Observer\CaptchaStringResolver;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class of function to save the add new data in frontend
 */
class Submit implements HttpPostActionInterface
{
    protected $request;

    /**
     * @var ContactsFactory
     */
    protected $contactsFactory;

    /**
     * @var ContactRepositoryInterface
     */
    protected $contactRepository;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var Email
     */
    protected $helperEmail;

    /**
     * @var CaptchaHelper
     */
    private $captchaHelper;

    /**
     * @var CaptchaStringResolver
     */
    private $captchaStringResolver;

    /**
     * @param RequestInterface $request
     * @param ContactsFactory $contactsFactory
     * @param ContactRepositoryInterface $contactRepository
     * @param ManagerInterface $messageManager
     * @param RedirectFactory $resultRedirectFactory
     * @param Email $helperEmail
     * @param CaptchaStringResolver|null $captchaStringResolver
     * @param CaptchaHelper|null $captchaHelper
     */
    public function __construct(
        RequestInterface $request,
        ContactsFactory $contactsFactory,
        ContactRepositoryInterface $contactRepository,
        ManagerInterface $messageManager,
        RedirectFactory $resultRedirectFactory,
        Email $helperEmail,
        ?CaptchaStringResolver $captchaStringResolver = null,
        ?CaptchaHelper $captchaHelper = null
    ) {
        $this->request = $request;
        $this->contactsFactory = $contactsFactory;
        $this->contactRepository = $contactRepository;
        $this->messageManager = $messageManager;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->helperEmail = $helperEmail;
        $this->captchaHelper = $captchaHelper ?: ObjectManager::getInstance()->get(CaptchaHelper::class);
        $this->captchaStringResolver = $captchaStringResolver ?: ObjectManager::getInstance()->get(
            CaptchaStringResolver::class
        );
    }

    /**
     * @return Redirect
     */
    public function execute()
    {
        $post = $this->request->getPostValue();

        if ($this->isValidCaptcha()) {
            try {
                $rowData = $this->contactsFactory->create();
                $rowData->setData($post);
                $this->contactRepository->save($rowData);
                $this->helperEmail->sendEmail($post);

                $this->messageManager->addSuccessMessage(__('Contactus data has been saved successfully.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
            }
        }

        $result = $this->resultRedirectFactory->create();
        $result->setPath('*/*/index');
        return $result;
    }

    /**
     * Function to validate captcha and print incorrect message if mismatch
     * @return bool
     */
    public function isValidCaptcha()
    {
        $captchaForName = 'contactus_custom_form';
        /** @var CaptchaModel $captchaModel */
        $captchaModel = $this->captchaHelper->getCaptcha($captchaForName);

        $isCorrectCaptcha = $this->validateCaptcha($captchaModel, $captchaForName);

        if (!$isCorrectCaptcha) {
            $this->messageManager->addErrorMessage(__('Incorrect CAPTCHA'));
            return false;
        }
        return true;
    }

    /**
     * Function to validate captcha
     * @param CaptchaModel $captchaModel
     * @param string $captchaFormName
     * @return bool
     */
    private function validateCaptcha(CaptchaModel $captchaModel, string $captchaFormName) : bool
    {
        if ($captchaModel->isRequired()) {
            $word = $this->captchaStringResolver->resolve(
                $this->request,
                $captchaFormName
            );

            if (!$captchaModel->isCorrect($word)) {
                return false;
            }
        }

        return true;
    }
}
