<?php

namespace CodeDelivery\Http\Controllers\Api\Client;


use CodeDelivery\Http\Controllers\Controller;
use CodeDelivery\Repositories\OrderRepository;
use CodeDelivery\Repositories\ProductRepository;
use CodeDelivery\Repositories\UserRepository;
use CodeDelivery\Services\OrderService;
use Illuminate\Http\Request;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;


class ClientCheckoutController extends Controller
{

    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var OrderService
     */
    private $orderService;

    public function  __construct(
        OrderRepository $repository,
        ProductRepository $productRepository,
        UserRepository $userRepository,
        OrderService $orderService
    )
    {
        $this->repository = $repository;
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
        $this->orderService = $orderService;
    }

    public function index(){

        $id = Authorizer::getResourceOwnerId();
        $clientId = $this->userRepository->find($id)->client->id;
        $orders = $this->repository->with('items')->scopeQuery(function ($query) use($clientId){
            return $query->where('client_id','=',$clientId);
        })->paginate();

        return $orders;
    }

    public function store(Request $request){

        $data = $request->all();
        $id = Authorizer::getResourceOwnerId();
        $clientId = $this->userRepository->find($id)->client->id;
        $data['client_id'] = $clientId;
        $order = $this->orderService->create($data);
        $order = $this->repository->with('items')->find($order->id);
        return $order;
    }

    public function show($id){
        $order = $this->repository->with(['client','items','cupom'])->find($id);
        $order->items->each(function ($item){
            $item->product;
        });
        return $order;
        /*return $this->repository
            ->skipPresenter(false)
            ->with($this->with)
            ->find($id);
        */
    }

}