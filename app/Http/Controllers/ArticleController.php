<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:25:10')->except(['getArticleById']);
    }

    /**
     * Get article by id
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getArticleById(int $id): JsonResponse
    {
        try {
            return response()->json([
                'article' => Article::find($id)->toJson(),
            ], 200);
        } catch (\Throwable $e) {
            Log::error($e);
            return response()->json([
                'error' => 'Database error'
            ], 500);
        }
    }

    /**
     * Submit article
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function submitArticle(Request $request): JsonResponse
    {
        // TODO: validate / sanitize
        $body = $request->all();
        try {
            $article = new Article;
            $article->body = $body['body'];
            $article->type = $body['type'];
            $article->save();
            return response()->json([
                'status' => 'Success'
            ], 200);
        } catch (\Throwable $e) {
            Log::error($e);
            return response()->json([
                'error' => 'Database error'
            ], 500);
        }
    }

    /**
     * Admin. Set article to be available to mobile app
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setArticleAvailable(Request $request): JsonResponse
    {
        return response()->json(['status' => 'Unimplemented'], 200);
    }
}
