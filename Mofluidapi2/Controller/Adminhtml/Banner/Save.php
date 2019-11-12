<?php
namespace Mofluid\Mofluidapi2\Controller\Adminhtml\Banner;
use Magento\Framework\App\Filesystem\DirectoryList;
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
	public function execute()
    {

        $data = $this->getRequest()->getParams();
        //echo "<pre>"; print_r($data); die('ddd');
        $new_mofluid_banner_action_type = $data['mofluid_image_action'];
        if(isset($data['mofluid_image_action_product'])){
			$new_mofluid_banner_product_action = $data['mofluid_image_action_product'];
		}
		if(isset($data['mofluid_image_action_category'])){
			$new_mofluid_banner_category_action = $data['mofluid_image_action_category'];
		}
        $mofluid_new_banner_action_data = "";
        if($new_mofluid_banner_action_type == "2") {
				$mofluid_new_banner_action_data_base  = "product";
				$mofluid_new_banner_action_data_base_id = $new_mofluid_banner_product_action;

				$product_ty = $this->_objectManager->get('\Magento\Catalog\Model\Category')->load($mofluid_new_banner_action_data_base_id);
				$productType = $product_ty->getTypeID();
				$name = $product_ty->getName();
				$mofluid_new_banner_action_data = json_encode(array("action"=> "open", "base" => $mofluid_new_banner_action_data_base,"id" => $mofluid_new_banner_action_data_base_id,"type"=>$productType));
		  }
		  else if ($new_mofluid_banner_action_type == "1"){
				$mofluid_new_banner_action_data_base  = "category";
				$mofluid_new_banner_action_data_base_id = $new_mofluid_banner_category_action;
				$cat_ty = $this->_objectManager->get('Magento\Catalog\Model\Product')->load($mofluid_new_banner_action_data_base_id);
				$name = $cat_ty->getName();
				 $mofluid_new_banner_action_data = json_encode(array("action"=> "open", "base" => $mofluid_new_banner_action_data_base,"id" => $mofluid_new_banner_action_data_base_id,'name'=>$name));
		  }
		  else {
				$mofluid_new_banner_action_data = "";
		  }
        //echo "<pre>"; print_r($data); die('ccc');
        if ($data) {
            $model = $this->_objectManager->create('Mofluid\Mofluidapi2\Model\Banner');
						$uploader = $this->_objectManager->create(
					  'Magento\MediaStorage\Model\File\Uploader',
					  ['fileId' => 'mofluid_image_value']
					 );
					 $res=$uploader->validateFile();
            if(isset($res)) {
				try {

						$uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
						//echo "<pre>"; print_r($_FILES['mofluid_image_value']['name']); die('ccc');
						$uploader->setAllowRenameFiles(true);
						$uploader->setFilesDispersion(true);
						$mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
							->getDirectoryRead(DirectoryList::MEDIA);
						$result = $uploader->save($mediaDirectory->getAbsolutePath('mofluidbanner/images'));
						unset($result['tmp_name']);
						unset($result['path']);
						$data['mofluid_image_value'] = 'mofluidbanner/images'.$result['file'];
				} catch (\Exception $e) {
					//$data['mofluid_image_value'] = $_FILES['mofluid_image_value']['name'];
				}
			}else{
				if (isset($data['mofluid_image_value']['value'])) {
                    if (isset($data['mofluid_image_value']['delete'])) {
                        $data['mofluid_image_value'] ="";
                        $data['delete_mofluid_image_value'] = true;
                    } elseif (isset($data['mofluid_image_value']['value'])) {
                        $data['mofluid_image_value'] = $data['mofluid_image_value']['value'];
                    } else {
                        $data['mofluid_image_value'] = "";
                    }
                }
			}
			$id = $this->getRequest()->getParam('mofluid_image_id');
            if ($id) {
                $model->load($id);
                $model->setMofluidThemeId(2);
                $model->setMofluidStoreId($data['mofluid_store_id']);
                $model->setMofluidImageType($data['mofluid_image_type']);
                $model->setMofluidImageLabel(basename($data['mofluid_image_action']));
                $model->setMofluidImageValue($data['mofluid_image_value']);
                $model->setMofluidImageHelptext('None');
                $model->setMofluidImageHelplink('None');
                $model->setMofluidImageIsrequired(0);
                $model->setMofluidImageSortOrder($data['mofluid_image_sort_order']);
                $model->setMofluidImageIsdefault($data['mofluid_image_isdefault']);
                $model->setMofluidImageAction(base64_encode($mofluid_new_banner_action_data));
                $model->setMofluidImageActionData('');
            }else{
				$image_data = array(
				      "mofluid_theme_id" => 2,
					  "mofluid_store_id" => $data['mofluid_store_id'],
					  "mofluid_image_type" => $data['mofluid_image_type'],
					  "mofluid_image_label" => basename($data['mofluid_image_action']),
					  "mofluid_image_value" => $data['mofluid_image_value'],
					  "mofluid_image_helptext" => 'None',
					  "mofluid_image_helplink" => 'None',
					  "mofluid_image_isrequired" => 0,
					  "mofluid_image_sort_order" => $data['mofluid_image_sort_order'],
					  "mofluid_image_isdefault" => $data['mofluid_image_isdefault'],
					  "mofluid_image_action" => base64_encode($mofluid_new_banner_action_data),
					  "mofluid_image_action_data" => ''
					);

				$model->setData($image_data);
			}
            try {
                $model->save();
                $this->messageManager->addSuccess(__('The Frist Grid Has been Saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('mofluid_image_id' => $model->getMofluidImageId(), '_current' => true));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the banner.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('mofluid_image_id' => $this->getRequest()->getParam('mofluid_image_id')));
            return;
        }
        $this->_redirect('*/*/');
    }
}
