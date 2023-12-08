<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Service\ServiceManagement\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MessageController extends Controller
{
    public function __construct(public MessageRepositoryInterface $messageRepository, public UserServiceInterface $userService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = $this->userService->getUserId();
        $messages = $this->messageRepository->list(['user_id' => $userId]);
        return MessageCollection::make($messages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MessageRequest $request)
    {
        $messageData = $request->validated();
        $messageData['user_id'] = $this->userService->getUserId();
        $message = $this->messageRepository->create($messageData);
        return new MessageResource($message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
