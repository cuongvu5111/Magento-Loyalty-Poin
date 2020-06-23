<?php

namespace Smart\Loyalty\Controller\Cart;

class CancelPoint extends \Magento\Framework\App\Action\Action
{
    protected $quoteRepository;

    protected $customerSession;

    protected $customerFactory;

    protected $checkoutSession;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\CustomerFactory $customerFactory
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->customerFactory = $customerFactory;
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            $cartId = $this->checkoutSession->getQuote()->getId();
            $quote = $this->quoteRepository->getActive($cartId);
            $itemsCount = $quote->getItemsCount();
            if ($itemsCount) {
                $quote->setPointUsed(0)->collectTotals();
                $this->quoteRepository->save($quote);
                $this->messageManager->addSuccessMessage(
                    __(
                        'Cancel success loyalty point.'

                    )
                );
                $resultRedirect->setPath('checkout/cart');
                return $resultRedirect;
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('We cannot apply the loyalty point.'));
            $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
        }

    }

}
