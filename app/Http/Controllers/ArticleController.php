<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\AdminApproveArticleRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Article;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Article\GetArticleRequest;
use App\Http\Requests\Article\SubmitArticleRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:3:30')->only(['submitArticle']);
    }

    /**
     * @api {get} /article/:article_id          Request article by ID.
     * @apiName GetArticleById
     * @apiGroup Article
     *
     * @apiParam {Int} article_id               Article's unique ID.
     *
     * @apiSuccess {Object} article             Article.
     * @apiSuccess {Int} article.id             Article's unique ID.
     * @apiSuccess {String} article.title       Title.
     * @apiSuccess {String} article.body        Body.
     * @apiSuccess {Int} article.type_id        Type.
     *
     * @apiError (Error 404) ArticleNotFound    The <code>id</code> of the Article was not found.
     * @apiError (Error 500) DatabaseError
     *
     * @param GetArticleRequest $request
     * @return JsonResponse
     */
    public function getArticleById(GetArticleRequest $request): JsonResponse
    {
        $articleId = $request->validated()['article_id'];
        try
        {
            $article = Article::findOrFail($articleId)->toJson();
            return response()->json([
                'article' => $article,
            ], 200);
        }
        catch (ModelNotFoundException $e)
        {
            return response()->json([
                'error' => 'ArticleNotFound',
            ], 404);
        }
        catch (\Throwable $e)
        {
            Log::error($e);
            return response()->json([
                'error' => 'DatabaseError'
            ], 500);
        }
    }

    /**
     * @api {post} /article                 Submit article for further moderation.
     * @apiName SubmitArticle
     * @apiGroup Article
     *
     * @apiParam {String{24..9999}} body    Article's body.
     * @apiParam {Int} type_id              Article's type.
     *
     * @apiSuccess {String} status          Success message.
     * @apiSuccess {Int} article_id         ID of created article.
     *
     * @apiError (Error 500) DatabaseError
     *
     * @param SubmitArticleRequest $request
     * @return JsonResponse
     */
    public function submitArticle(SubmitArticleRequest $request): JsonResponse
    {
        $body = $request->validated();
        try
        {
            $article = new Article;
            $article->body = $body['body'];
            $article->type_id = $body['type_id'];
            $article->save();
            return response()->json([
                'status' => 'Success',
                'article_id' => $article->id,
            ], 200);
        }
        catch (\Throwable $e)
        {
            Log::error($e);
            return response()->json([
                'error' => 'DatabaseError'
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
        $articleId = $request->validated()['article_id'];
        try
        {
            Article::whereId($articleId)
                ->update(['is_available' => true]);
            return response()->json([
                'status' => 'Success'
            ], 200);
        }
        catch (\Throwable $e)
        {
            Log::error($e);
            return response()->json([
                'error' => 'DatabaseError'
            ], 500);
        }
    }
}
