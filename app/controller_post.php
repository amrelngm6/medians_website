<?php 

$returnData='';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// TWIG template engine
use Twig\Environment;

use Medians\Application as apps;
use Shared\dbaser;

use Medians\Infrastructure\Administrators\AdminRepository;


$params = $request->request->get('params');


switch ($request->request->get('type')) 
{
    case 'add_page':

        $page = new apps\Pages\Page(); 
    
        try {

            if (isset($page))
            {
                $returnData = $page->create($request->request);
            }

        } catch (Exception $e) {
            $returnData = array('error'=> $e->getMessage());
        }

        break;

    case 'edit_page':

        $page = new apps\Pages\Page(); 
    
        try {

            if (isset($page))
            {
                $returnData = $page->update($request->request);
            }

        } catch (Exception $e) {
            $returnData = array('error'=> $e->getMessage());
        }

        break;

    case 'delete_page':

        $page = new apps\Pages\Page(); 
    
        try {

            if (isset($page))
            {
                $returnData = $page->delete($request->request);
            }

        } catch (Exception $e) {
            $returnData = array('error'=> $e->getMessage());
        }

        break;

    case 'userLogin':
        
        $service = new apps\Auth\AuthService( new AdminRepository() ); 
        
        $requestData = $params;

        try {
            
            $checkUser = $service->checkLogin($requestData['email'], $service->encrypt($requestData['password']));

            if (!empty($checkUser->id()))
            {
                $service->setSession($checkUser);
            }

            $returnData = array('success'=>1, 'data'=>'Logged in', 'redirect'=>$app->CONF['url'].'provider_area/devices');

        } catch (Exception $e) {
            $returnData = array('error'=>$e->getMessage());
        }

        break;
    

    case 'add_device':
        
        $params = $params;
        $params['providerId'] = $app->providerSession->id();
        $service = new apps\Devices\Device($params); 

        try {

            if (!$service->validate())
            {
                
                $checkInsert = $service->saveItem(  ) ;

                if (!empty($checkInsert->id()))
                {
                    $returnData = array('success'=>1, 'data'=>'Added', 'reload'=>1);
                }
            }

        } catch (Exception $e) {
            $returnData = array('error'=>$e->getMessage());
        }

        break;

    case 'delete_device':
        
        $service = new apps\Devices\Device(null); 
    
        try {

            if ($service->getItem($params['id'])->id())
            {

                if ($app->providerSession->id() != $service->getItem($deviceId)->providerId())
                {

                   $returnData = array('error'=>'Not allowed');

                } else {

                    $service->setData($params['id']);

                    $checkDelete = $service->deleteItem( ) ;

                    if ($checkDelete)
                    {
                        $returnData = array('success'=>1, 'data'=>'Deleted', 'reload'=>1);
                    }
                }

            }

        } catch (Exception $e) {

            $returnData = array('error'=>$e->getMessage());
        }   
        break;
        
    case 'edit_device':
        
        $service = new apps\Devices\Device($params); 
        
        $deviceId = $params['id'];

        try {

            if ($service->getItem($deviceId)->id() )
            {

                if ($app->providerSession->id() != $service->getItem($deviceId)->providerId())
                {

                   $returnData = array('error'=>'Not allowed');

                } else {

                    $service->setData($deviceId);

                    $checkUpdate = $service->editItem( $deviceId) ;

                    if (!empty($checkUpdate->id()))
                    {
                    
                        apps\Prices\Prices::create($deviceId, $params['prices'])->saveItem();

                        $returnData = array('success'=>1, 'data'=>'Updated');
                    }
                }
            }

        } catch (Exception $e) {
            $returnData = array('error'=>$e->getMessage());
        }

        break;
    

    case 'add_device_type':
        
        $service = new apps\Devices\DeviceType($params); 

        try {

            if (!$service->validate())
            {

                $checkInsert = $service->saveItem( ) ;

                if (!empty($checkInsert->id()))
                {
                    $returnData = array('success'=>1, 'data'=>'Added', 'reload'=>1);
                }
            }

        } catch (Exception $e) {
            $returnData = array('error'=>$e->getMessage());
        }

        break;
    
    case 'delete_device_type':
        
        $service = new apps\Devices\DeviceType($params); 
    
        try {

            if ($service->getItem($params['id'])->id())
            {
                if ($service->deleteItem( ))
                {
                    $returnData = array('success'=>1, 'data'=>'Deleted', 'reload'=>1);
                }
            
            }
        } catch (Exception $e) {

            $returnData = array('error'=>$e->getMessage());
        }   
        
    case 'edit_device_type':
        
        $service = new apps\Devices\DeviceType($params); 

        try {

            if ($service->getItem($params['id'])->id())
            {
                $checkUpdate = $service->editItem( ) ;

                if (!empty($checkUpdate->id())) { $returnData = array('success'=>1, 'data'=>'Updated', 'reload'=>1); }
            }

        } catch (Exception $e) {
            $returnData = array('error'=>$e->getMessage());
        }

        break;
    
    case 'updateSettings':
        
        $service = new apps\Settings\Settings($params['settings']); 

        try {

            $service->updateSettings();
            if (isset($service->updated)) { $returnData = array('success'=>1, 'data'=>'Updated', 'reload'=>1); }

        } catch (Exception $e) {
            $returnData = array('error'=>$e->getMessage());
        }
        break;

    case 'newDeviceOrder':
        
        $service = new apps\Orders\DeviceOrder($params['id'], 'unlimited', isset($params['multiple']) ? 'multi' : 'single'); 
 
        try {

            $service->setDeviceId($params['id']);

            if (isset($params['hours']) )
            {
                $service->setEndTime($params['hours'], $params['minutes']);
            } else {
                $service->unsetEndTime();
            }


            $service->setInsertedBy($app->userSession->id());
            $service->setProviderId($app->providerSession->id());

            $checkOrder = $service->handle();

            $device = apps\Devices\Device::create(array('id' => $checkOrder->device()->id(),  'playing'=> 1, 'publish'=>1)); 
            $device->editItem( $checkOrder->device()->id() );

            if (!empty($checkOrder->status()) && $checkOrder->status() == 'active') { $returnData = array('success'=>1, 'reload'=>1, 'data'=>'Done'); }

        } catch (Exception $e) {
            $returnData = array('error'=>$e->getMessage());
        }
        break;
    
    case 'endDeviceOrder':

        $service = new apps\Orders\DeviceOrder($params['id']); 

        try {

            $service->setDeviceId($params['id']);

            $service->setProviderId($app->providerSession->id());

            $service->setOrderedBy($app->userSession->id());

            $checkDeviceOrder = $service->submitOrder();

            $device = apps\Devices\Device::create(array('id' => $checkDeviceOrder->device()->id(),  'playing'=> '0', 'publish'=>1)); 
            $device->editItem( $checkDeviceOrder->device()->id() );

            if (!empty($checkDeviceOrder->status()) ) { $returnData = array('success'=>1, 'redirect'=>$app->CONF['url'].'provider_area/order/'.$checkDeviceOrder->code(), 'data'=>1); }

        } catch (Exception $e) {
            $returnData = array('error'=>$e->getMessage());
        }
        break;

    case 'setOrderPaid':

        $code = $params['id'];

        $service = new apps\Orders\Order($code); 

        try {

            $service->setCode($params['id']);

            $orderData = $service->getByCode($code);
            $orderData->device = (new apps\Devices\Device)->getItem($orderData->device);
            $service->setModel($service->createModel($orderData));
            
            $service->setOrderedBy($app->userSession->id());

            $checkOrder = $service->setOrderPaid();

            if (!empty($checkOrder->status()) ) { $returnData = array('success'=>1, 'reload'=>1, 'data'=>1); }

        } catch (Exception $e) {
            $returnData = array('error'=>$e->getMessage());
        }
        break;


    case 'add_product':
        
        $service = new apps\Products\Product($params); 

        try {

            $service->setModel($service->createModel($params));

            if (!$service->validate())
            {

                $service->ProductModel->setProviderId($app->providerSession->id());
                $service->ProductModel->setPublish(1);

                $checkInsert = $service->saveItem( ) ;

                if (!empty($checkInsert->id()))
                {
                    $returnData = array('success'=>1, 'data'=>'Added', 'reload'=>1);
                }
            }

        } catch (Exception $e) {
            $returnData = array('error'=>$e->getMessage());
        }

        break;
    
    case 'edit_product':
        
        $service = new apps\Products\Product($params); 

        try {

            if ($service->getItem($params['id'])->id)
            {
                
                if ($app->providerSession->id() != $service->getItem($params['id'])->providerId)
                {
                   $returnData = array('error'=>'Not allowed');

                } else {

                    $service->ProductModel->setPublish(isset($params['publish']) ? 1 : 0 );
                    $service->ProductModel->setProviderId($app->providerSession->id());
                    $service->ProductModel->setStock($service->getItem($params['id'])->stock);
                    
                    $checkUpdate = $service->editItem( ) ;

                    if (!empty($checkUpdate->id())) { $returnData = array('success'=>1, 'data'=>'Updated', 'redirect'=>$app->CONF['url'].'provider_area/products'); }
                }

            }

        } catch (Exception $e) {
            $returnData = array('error'=>$e->getMessage());
        }

        break;
    
    case 'delete_product':
        
        $service = new apps\Products\Product(null); 
    
        try {

            $checkItem = $service->createModel($service->getItem($params['id']));

            if ($checkItem->id())
            {


                if ($app->providerSession->id() != $checkItem->providerId())
                {
                   $returnData = array('error'=>'Not allowed');

                } else {

                    if ($service->deleteItem( $checkItem->id() ))
                    {
                        $returnData = array('success'=>1, 'data'=>'Deleted', 'reload'=>1);
                    }
                }


            }

        } catch (Exception $e) {

            $returnData = array('error'=>$e->getMessage());
        }   
    
        break;
    

    case 'add_stock':
        

        $service = new apps\Products\Stock($params); 

        try {

            $checkItem = (new apps\Products\Product)->getItem($params['product']);

            if (empty($checkItem))
            {

               $returnData = array('error'=>'Not found');
            
            } else {

                if (!$service->validate())
                {

                    if (isset($checkItem->id) && $app->providerSession->id() != $checkItem->providerId)
                    {
                       $returnData = array('error'=>'Not allowed');

                    } else {

                        $checkInsert = $service->saveItem( ) ;

                        if (!empty($checkInsert->id()))
                        {
                            $returnData = array('success'=>1, 'data'=>'Added', 'reload'=>1);
                        }
                    }
                }
           }

        } catch (Exception $e) {
            $returnData = array('error'=>$e->getMessage());
        }

        break;
    
    case 'delete_stock':
        
        $service = new apps\Products\Stock(null); 
    
        try {

            $checkItem = $service->getItem($params['id']);

            if (isset($checkItem->id))
            {

                if ($service->deleteItem( $checkItem->id ))
                {
                    $returnData = array('success'=>1, 'data'=>'Deleted', 'reload'=>1);
                }

            }

        } catch (Exception $e) {

            $returnData = array('error'=>$e->getMessage());
        }   
    
        break;
    
    

    case 'order_product':
            
        $service = new apps\Orders\ProductOrder(null); 

        try {

            $checkItem = (new apps\Devices\Device)->getItem($params['id']);

            $params['products'] = $service->filterData($params['products']);
            $params['providerId'] = $app->providerSession->id();
            
            if ($service->handle( $params ))
            {
                $returnData = array('success'=>1, 'data'=>'Added', 'redirect'=>$app->CONF['url'].'provider_area/devices');
            }

        } catch (Exception $e) {

            $returnData = array('error'=>$e->getMessage());
        }   
    
        break;
    
    
    case 'add_discount':
        
        $service = new apps\Discounts\Discount($params); 

        try {

            if (!$service->validate())
            {

                $checkInsert = $service->saveItem( ) ;

                if (!empty($checkInsert->id()))
                {
                    $returnData = array('success'=>1, 'data'=>'Added', 'reload'=>1);
                }
            }

        } catch (Exception $e) {
            $returnData = array('error'=>$e->getMessage());
        }

        break;
    
    
    case 'order_discount':

        $code = $params['id'];
        $discountCode = $params['discountCode'];

        $service = new apps\Orders\Order($code); 

        try {

            $checkItem = $service->getByCode($code);

            if (isset($checkItem->id))
            {
                $checkItem->device = ((new apps\Devices\Device())->getItem($checkItem->device));
                $service->setModel($service->createModel($checkItem));

                if ($service->handleOrderDiscount($discountCode) )
                {
                    $returnData = array('success'=>1, 'data'=>'Updated', 'reload'=>1);
                }

            }  else { $returnData = array('error'=>'Order not found'); }

        } catch (Exception $e) { $returnData = array('error'=>$e->getMessage()); }   

        break;
    
    
    case 'delete_discount':
        
        $service = new apps\Discounts\Discount(null); 
    
        try {

            $checkItem = $service->getItem($params['id']);

            if (isset($checkItem->id))
            {

                if ($service->deleteItem( $checkItem->id ))
                {
                    $returnData = array('success'=>1, 'data'=>'Deleted', 'reload'=>1);
                }

            }

        } catch (Exception $e) {

            $returnData = array('error'=>$e->getMessage());
        }   
    
        break;
    
    

}



echo json_encode($returnData);

return true;

// $app->run();


    





