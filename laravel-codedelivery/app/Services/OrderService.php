<?php


namespace CodeDelivery\Services;


use CodeDelivery\Models\Order;
use CodeDelivery\Repositories\AuxiliaryItemsRepository;
use CodeDelivery\Repositories\CupomRepository;
use CodeDelivery\Repositories\OrderRepository;
use CodeDelivery\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;

class OrderService{
    /**
     * @var OrderRepository
     */
    private $orderRepository;
    /**
     * @var CupomRepository
     */
    private $cupomRepository;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(
        OrderRepository $orderRepository,
        CupomRepository $cupomRepository,
        ProductRepository $productRepository

    )
    {

        $this->orderRepository = $orderRepository;
        $this->cupomRepository = $cupomRepository;
        $this->productRepository = $productRepository;

    }

    public function create(array $data){

        \DB::beginTransaction();

        try {
            $data['status'] = 0;

            if (isset($data['cupom_id'])){
                unset($data['cupom_id']);
            }
            if (isset($data['cupom_code'])){
                $cupom = $this->cupomRepository->findByField('code',$data['cupom_code'])->first();
                $data['cupom_id'] = $cupom->id;
                $cupom->used = 1;
                $cupom->save();
                unset($data['cupom_code']);
            }
            $items = $data['items'];
            unset($data['items']);

            $order = $this->orderRepository->create($data);
            $total = 0;

            foreach ($items as $item){
                $item['price'] = $this->productRepository->find($item['product_id'])->price;
                $order->items()->create($item);
                $total += $item['price'] * $item['qtd'];
            }

            $order->total = $total;

            if (isset($cupom)){
                $order->total = $total - $cupom->value;
            }
            $order->save();

            \DB::commit();

            return $order;
        } catch (\Exception $e){
            \DB::rollback();
            throw $e;
        }
    }

    /*public function updateStatus($id,$idDeliveryman,$status,$lat,$long,$service=null,$devolver=null){
        $order = $this->orderRepository->getByIDAndDeliveryman($id,$idDeliveryman);
        $order->status = $status;
        switch ((int)$status) {
            case 0:
                if($devolver==1){
                    $order->user_deliveryman_id = (int) $devolver;
                    $order->flag_sincronizado = 0;
                    $order->save();
                    break;
                }elseif ($order->visita==null){
                    $order->visita = date("d/m/Y h:i:s");
                    $order->flag_sincronizado = 0;
                    $order->geo_client_no_location = $lat.','.$long;
                }else{
                    $order->visita .= ','.date("d/m/Y h:i:s");
                    $order->geo_client_no_location = $lat.','.$long;
                    $order->flag_sincronizado = 0;
                }
                $order->save();
                break;
            case 1:
                if((int)($order->status == 1 && !$order->hash)){
                    $order->hash = md5((new \DateTime())->getTimestamp());
                }
                $order->geo = $lat.','.$long;
                $order->flag_sincronizado = 0;
                $order->save();
                break;
            case 2:
                $order->geo_final = $lat.','.$long;
                $order->service = $service;
                $order->flag_sincronizado = 0;
                $order->save();
                break;
        }

        return $order;

    }
*/

}
