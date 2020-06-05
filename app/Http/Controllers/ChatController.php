<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Events\MessageReceived;
use App\Models\Message;
use App\Services\ImageProcessingService;
use Widmogrod\Monad\Either\{Left, Right, Either};
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use function Safe\{json_decode, json_encode};

class ChatController extends Controller
{
    const PAGINATOR_PER_PAGE = 30;

    /**
     * @var ImageProcessingService $imageProcessingService
     */
    private $imageProcessingService;

    public function __construct(ImageProcessingService $imageProcessingService)
    {
        $this->middleware('auth:api');
        $this->middleware('banned');

        $this->imageProcessingService = $imageProcessingService;
    }

    /**
     * Broadcasts and saves received message
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function broadcastMessage(Request $request): JsonResponse
    {
        try {
            $messageData = json_decode($request->getContent(), true);

            $result = $this->saveMessage($messageData);

            if ($result instanceof Left) {
                return response()->json([
                    'error' => 'Server error'
                ], 500);
            }

            broadcast(new MessageReceived($messageData['room_id']))->toOthers();

            return response()->json([], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Server error'
            ], 500);
        }
    }

    /**
     * Get paginated room message history
     *
     * @param int $roomId
     *
     * @return Paginator|void
     */
    public function getMessageHistory(int $roomId): Paginator
    {
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
            return abort(500, 'Database error');
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
            $message->body = $messageData['text'];
            $message->timestamp = $messageData['timestamp'];
            $message->room_id = $messageData['room_id'];
            $message->user_id = $messageData['user_id'];

            foreach ($messageData['attachments'] as $attachment) {
                if ($attachment['type'] === 'image') {
                    $imgUri = $this->imageProcessingService->saveImg($attachment['source']);

                    if ($imgUri instanceof Left) {
                        return $imgUri;
                    }
                    $attachment['source'] = $imgUri->extract();
                }
            }
            $message->attachments = json_encode($messageData['attachments']);

            $message->save();
        } catch (\Throwable $e) {
            return Left::of($e);
        }

        return Right::of(true);
    }
}
