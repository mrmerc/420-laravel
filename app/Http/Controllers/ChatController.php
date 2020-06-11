<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Events\MessageReceived;
use App\Components\Attachments\AttachmentFactory;
use App\Http\Requests\Chat\AdminBanUserRequest;
use App\Http\Requests\Chat\BroadcastMessageRequest;
use App\Http\Requests\Chat\MessageHistoryRequest;
use App\Models\Message;
use Widmogrod\Monad\Either\{Left, Right, Either};
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    const PAGINATOR_PER_PAGE = 30;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('banned');
    }

    /**
     * Broadcasts and saves received message
     *
     * @param BroadcastMessageRequest $request
     *
     * @return JsonResponse
     */
    public function broadcastMessage(BroadcastMessageRequest $request): JsonResponse
    {
        $messageData = $request->validated();
        try {
            $result = $this->saveMessage($messageData);

            if ($result instanceof Left) {
                Log::error($result->extract());
                return response()->json([
                    'error' => 'Server error'
                ], 500);
            }

            broadcast(new MessageReceived($messageData['room_id']))->toOthers();

            return response()->json([], 200);
        } catch (\Throwable $e) {
            Log::error($e);
            return response()->json([
                'error' => 'Server error'
            ], 500);
        }
    }

    /**
     * Get paginated room message history
     *
     * @param MessageHistoryRequest $request
     *
     * @return Paginator|JsonResponse
     */
    public function getMessageHistory(MessageHistoryRequest $request)
    {
        $roomId = $request->validated()['roomId'];
        try {
            $messages = DB::table('messages')
                ->where('room_id', $roomId)
                ->orderBy('timestamp', 'desc')
                ->get()
                ->toArray();

            $currentPage = Paginator::resolveCurrentPage();
            $perPage = self::PAGINATOR_PER_PAGE;
            $messagesChunk = array_slice($messages, $perPage * ($currentPage - 1), $perPage);

            return new Paginator($messagesChunk, count($messages), $perPage, $currentPage);
        } catch (\Throwable $e) {
            Log::error($e);
            return response()->json([
                'error' => 'Server error'
            ], 500);
        }
    }

    /**
     * Admin. Bans user with chat history erasing (optional)
     *
     * @param AdminBanUserRequest $request
     * @return JsonResponse
     */
    public function banUser(AdminBanUserRequest $request): JsonResponse
    {
        $body = $request->validated();
        try {
            /**
             * @var \App\Models\User
             */
            $user = \App\Models\User::find($body['userId']);
            if ($user->isBanned()) {
                return response()->json([
                    'error' => 'User is already banned!'
                ], 400);
            }
            $bannedRole = \App\Models\Role::where('title', 'banned')->first();
            $user->roles()->attach($bannedRole->id);
            $user->save();

            if ($body['deleteMessageHistory']) {
                $result = $this->deleteUserMessageHistory($user->id);
                if ($result instanceof Left) {
                    throw new \Exception($result->extract());
                }
            }
            return response()->json([
                'message' => 'Success'
            ], 200);
        } catch (\Throwable $e) {
            Log::error($e);
            return response()->json([
                'error' => 'Database error'
            ], 500);
        }
    }

    /**
     * Saves message to database
     *
     * @param array messageData
     *
     * @return bool
     */
    private function saveMessage(array $messageData): Either
    {
        try {
            $message = new Message;
            $message->body = $messageData['body'];
            $message->timestamp = round(microtime(true) * 1000);
            $message->room_id = $messageData['room_id'];
            $message->user_id = $messageData['user_id'];

            foreach ($messageData['attachments'] as $attachment) {
                $attachmentClass = AttachmentFactory::create($attachment['type']);
                $result = $attachmentClass->processAttachment($attachment);

                if ($result instanceof Left) {
                    throw new \Exception($result->extract());
                }
                $attachment['source'] = $result->extract();
            }
            $message->attachments = $messageData['attachments'];
            $message->save();
        } catch (\Throwable $e) {
            return Left::of($e);
        }
        return Right::of(true);
    }

    /**
     * Deletes message history by user id
     *
     * @param int $userId
     * @return Either
     */
    private function deleteUserMessageHistory(int $userId): Either
    {
        try {
            Message::where('user_id', $userId)->delete();
            return Right::of(true);
        } catch (\Throwable $e) {
            Log::error($e);
            return Left::of($e);
        }
    }
}
