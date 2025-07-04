<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/articles",
     *     summary="Search and filter articles",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Keyword to search for in title, content, or author",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter by article category",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="source",
     *         in="query",
     *         description="Filter by article source",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Filter by publication date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of articles per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=20)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of articles",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="total", type="integer"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Article")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $search = $request->query('q');
        $perPage = $request->query('per_page', 20);

        if ($search) {
            $searchResults = Article::search($search)->get();
            $articleIds = $searchResults->pluck('id');
            
            $query = Article::whereIn('id', $articleIds);
        } else {
            $query = Article::query();
        }
        
        if ($category = $request->query('category')) {
            $query->where('category', $category);
        }
        if ($source = $request->query('source')) {
            $query->where('source', $source);
        }
        if ($date = $request->query('date')) {
            $query->where('published_at', '>=', $date)
                  ->where('published_at', '<', $date . 'T23:59:59');
        }

        $articles = $query->orderBy('published_at', 'desc')->paginate($perPage);

        return response()->json($articles);
    }

    /**
     * @OA\Get(
     *     path="/categories",
     *     summary="Get list of unique article categories",
     *     tags={"Articles"},
     *     @OA\Response(
     *         response=200,
     *         description="List of categories",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="categories", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function getCategories()
    {
        $categories = Article::query()->distinct()->pluck('category')->filter()->values();
        return response()->json(['categories' => $categories]);
    }

    /**
     * @OA\Get(
     *     path="/sources",
     *     summary="Get list of unique article sources",
     *     tags={"Articles"},
     *     @OA\Response(
     *         response=200,
     *         description="List of sources",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="sources", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function getSources()
    {
        $sources = Article::query()->distinct()->pluck('source')->filter()->values();
        return response()->json(['sources' => $sources]);
    }
} 