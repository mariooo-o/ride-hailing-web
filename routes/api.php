use App\Http\Controllers\RatingController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/ratings', [RatingController::class, 'store']);
    Route::get('/ratings/{userId}', [RatingController::class, 'show']);
});