<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Events\MessageReceived;
use App\Components\Attachments\AttachmentFactory;
use App\Http\Requests\Chat\AdminBanUserRequest;
use App\Http\Requests\Chat\BroadcastMessageRequest;
use App\Http\Requests\Chat\MessageHistoryRequest;
use App\Models\Message;
use Widmogrod\Monad\Either\{Left, Right, Either};
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use DB;
use Log;

/**
 * @group Chat
 *
 * Chat APIs
 */
class ChatController extends Controller
{
    const PAGINATOR_PER_PAGE = 30;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('banned');
    }

    /**
     * @api {post} /chat/message                Send a message.
     * @apiName SendMessage
     * @apiGroup Chat
     *
     * @apiParam {String} provider              Provider name
     * @apiParam {String{1..1024}} body         Message body.
     * @apiParam {Array{1..6}} [attachments]    Message attachments.
     * @apiParam {String} attachments.type      Message attachment type.
     * @apiParam {String} attachments.source    Message attachment data.
     * @apiParam {Int} timestamp                Message UTC timestamp (in milliseconds).
     * @apiParam {Int{1..}} user_id             User who sent the message.
     * @apiParam {Int{1..}} room_id             Room the message has been sent from.
     *
     * @apiSuccess {String} status              Success message.
     *
     * @apiError (Error 500) DatabaseError
     *
     * @param BroadcastMessageRequest $request
     *
     * @return JsonResponse
     */
    public function broadcastMessage(BroadcastMessageRequest $request): JsonResponse
    {
        $messageData = $request->validated();
        try
        {
            $result = $this->saveMessage($messageData);

            if ($result instanceof Left) {
                throw new \Exception($result->extract());
            }

            broadcast(new MessageReceived($messageData['room_id']))->toOthers();

            return response()->json([
                'status' => 'Success'
            ], 200);
        }
        catch (\Throwable $e)
        {
            return response()->json([
                'error' => 'DatabaseError'
            ], 500);
        }
    }

    /**
     * @api {get} /chat/message/history/:room_id    Get a room's paginated message history.
     * @apiName GetMessageHistory
     * @apiGroup Chat
     *
     * @apiParam {Int{1..}} room_id                 Room to get the history from.
     *
     * @apiSuccess {Int} total                      Message counter
     * @apiSuccess {Int} per_page                   Message per page
     * @apiSuccess {Int} current_page               Current page Int
     * @apiSuccess {Int/Null} last_page             Last page Int
     * @apiSuccess {String/Null} first_page_url     First page url
     * @apiSuccess {String/Null} last_page_url      Last page url
     * @apiSuccess {String/Null} next_page_url      Next page url
     * @apiSuccess {String/Null} prev_page_url      Previous page url
     * @apiSuccess {String/Null} path               Absolute path
     * @apiSuccess {Int} from                       Number of message to start with
     * @apiSuccess {Int} to                         Number of message to end with
     * @apiSuccess {Array} data                     Array of messages
     *
     * @apiError (Error 500) DatabaseError
     *
     * @param MessageHistoryRequest $request
     *
     * @return Paginator|JsonResponse
     */
    public function getMessageHistory(MessageHistoryRequest $request)
    {
        $roomId = $request->validated()['room_id'];
        try
        {
            $messages = DB::table('messages')
                ->where('room_id', $roomId)
                ->orderBy('timestamp', 'desc')
                ->get()
                ->toArray();

            $currentPage = Paginator::resolveCurrentPage();
            $perPage = self::PAGINATOR_PER_PAGE;
            $messagesChunk = array_slice($messages, $perPage * ($currentPage - 1), $perPage);

            return new Paginator($messagesChunk, count($messages), $perPage, $currentPage);
        }
        catch (\Throwable $e)
        {
            return response()->json([
                'error' => 'DatabaseError'
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
        try
        {
            /**
             * @var \App\Models\User
             */
            $user = \App\Models\User::find($body['user_id']);
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
                'status' => 'Success'
            ], 200);
        }
        catch (\Throwable $e)
        {
            return response()->json([
                'error' => 'DatabaseError'
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
        try
        {
            $message = new Message;
            $message->body = $messageData['body'];
            $message->timestamp = round(microtime(true) * 1000);
            $message->room_id = $messageData['room_id'];
            $message->user_id = $messageData['user_id'];

            if (isset($messageData['attachments'])) {
                foreach ($messageData['attachments'] as $attachment)
                {
                    $attachmentClass = AttachmentFactory::create($attachment['type']);
                    $result = $attachmentClass->processAttachment($attachment);

                    if ($result instanceof Left) {
                        throw new \Exception($result->extract());
                    }
                    $attachment['source'] = $result->extract();
                }
                $message->attachments = $messageData['attachments'];
            }
            $message->save();
            return Right::of(true);
        }
        catch (\Throwable $e)
        {
            return Left::of($e);
        }
    }

    /**
     * Deletes message history by user id
     *
     * @param int $userId
     * @return Either
     */
    private function deleteUserMessageHistory(int $userId): Either
    {
        try
        {
            Message::where('user_id', $userId)->delete();
            return Right::of(true);
        }
        catch (\Throwable $e)
        {
            return Left::of($e);
        }
    }
}
