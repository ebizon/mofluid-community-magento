<?php
/**
 * Copyright © 2015 Mofluid. All rights reserved.
 */
namespace Mofluid\Notifications\Controller\Adminhtml\Items;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Mofluid\Notifications\Controller\Adminhtml\Items
{
    public function execute()
    {
		if ($this->getRequest()->getPostValue()) {
            try {
					$model = $this->_objectManager->create('Mofluid\Notifications\Model\Items');
					$data = $this->getRequest()->getPostValue();
          $uploader = $this->_objectManager->create('Magento\MediaStorage\Model\File\Uploader',['fileId' => 'pemfile']);
          $res=$uploader->validateFile();
					  if(isset($res)) {
						try {

								$uploader->setAllowedExtensions(array('pem'));
								$uploader->setAllowRenameFiles(true);
								$uploader->setFilesDispersion(false);
								$mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')->getDirectoryRead(DirectoryList::MEDIA);
								$result = $uploader->save($mediaDirectory->getAbsolutePath('pemfile'));

								$data['pemfile'] = 'pemfile/'.$result['file'];
						} catch (\Exception $e) {
							$this->messageManager->addError(__('Incorrect File type'));
							return;
						}
					}
					$data = $this->getRequest()->getPostValue();
					$deviceToken = 'e5d2496979aa6d23a459fd667e6ab5f75024c3c127afd1a3cf6b87dccac8c86d';
					$passphrase =  $data['passphrase'];
					$message = $data['message'];
					$ctx = stream_context_create();

					stream_context_set_option($ctx, 'ssl', 'local_cert', '/var/www/html/mage3/pub/media/pemfile/emj.pem');
					stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
					$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
					$body['aps'] = array(
						'alert' => array(
							'body' => $message,
							'action-loc-key' => 'Bango App',
						),
						'badge' => 2,
						'sound' => 'oven.caf',
						);
					$payload = json_encode($body);
					$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
					$result = fwrite($fp, $msg, strlen($msg));
					fclose($fp);
					$this->_redirect('mofluid_notifications/*/');
					$inputFilter = new \Zend_Filter_Input(
							[],
							[],
							$data
					);
					$data = $inputFilter->getUnescaped();
					$id = $this->getRequest()->getParam('id');
					if ($id) {
						$model->load($id);
						if ($id != $model->getId()) {
							throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
						}
					}
						$model->setData($data);
						$session = $this->_objectManager->get('Magento\Backend\Model\Session');
						$session->setPageData($model->getData());
						$model->save();
						$this->messageManager->addSuccess(__('You send the notification.'));
						$session->setPageData(false);
						if ($this->getRequest()->getParam('back')) {
							$this->_redirect('mofluid_notifications/*/edit', ['id' => $model->getId()]);
							return;
						}
						$this->_redirect('mofluid_notifications/*/');
						return;
				} catch (\Exception $e) {
					$this->messageManager->addError(
						__('Something went wrong while saving the item data. Please review the error log.')
					);
					$this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
					$this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
					$this->_redirect('mofluid_notifications/*/edit', ['id' => $this->getRequest()->getParam('id')]);
					return;
				}
        }

    }
}
