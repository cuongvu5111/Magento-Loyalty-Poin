<?php

namespace Smart\Loyalty\Controller\Cart;

class ApplyPoint extends \Magento\Framework\App\Action\Action
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
            $pointBalance = (int) $this->getLoyaltyPointBalance($customerId   = $quote->getCustomerId());
            $pointUsed = (int) $this->getRequest()->getParam('point_used');
            $itemsCount = $quote->getItemsCount();
            if ($itemsCount) {
                $escape = $this->_objectManager->get(\Magento\Framework\Escaper::class);
                $countTotal = $quote->getData('subtotal');

                // check
                if ($pointBalance < $pointUsed) {
                    $this->messageManager->addErrorMessage(__('Point apply không được lớn hơn số point hiện có'));
                    $resultRedirect->setPath('checkout/cart');
                    return $resultRedirect;
                }
                if ($pointUsed > $countTotal) {
                    $pointUsed = $countTotal;
                }
                $quote->setPointUsed($pointUsed)->collectTotals();
                $this->quoteRepository->save($quote);
                $this->messageManager->addSuccessMessage(
                    __(
                        'Apply success "%1" point.',
                        $escape->escapeHtml($pointUsed)
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

    public function getLoyaltyPointBalance($customerId)
    {
        $customer = $this->customerFactory->create()->load($customerId)->getDataModel();
        if($customer->getCustomAttribute('point_balance') != null){
            return $customer->getCustomAttribute('point_balance')->getValue();
        }
        return 0;
    }
}
