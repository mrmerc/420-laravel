<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\AdminApproveArticleRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Article;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Article\GetArticleRequest;
use App\Http\Requests\Article\SubmitArticleRequest;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:3:30')->only(['submitArticle']);
    }

    /**
     * Get article by id
     *
     * @param GetArticleRequest $request
     * @return JsonResponse
     */
    public function getArticleById(GetArticleRequest $request): JsonResponse
    {
        $articleId = $request->validated()['articleId'];
        try {
            $article = Article::find($articleId)
                ->toJson();
            return response()->json([
                'article' => $article,
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
     * @param SubmitArticleRequest $request
     * @return JsonResponse
     */
    public function submitArticle(SubmitArticleRequest $request): JsonResponse
    {
        $body = $request->validated();
        try {
            $article = new Article;
            $article->body = $body['body'];
            $article->type_id = $body['typeId'];
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
     * Admin. Set article to be available on frontend
     *
     * @param AdminApproveArticleRequest $request
     * @return JsonResponse
     */
    public function setArticleAvailable(AdminApproveArticleRequest $request): JsonResponse
    {
        $articleId = $request->validated()['articleId'];
        try {
            Article::whereId($articleId)
                ->update(['is_available' => true]);
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
}
