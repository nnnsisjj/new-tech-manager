<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/groups', function(Request $request) {
    $query = \App\Models\Group::query();
    
    if ($request->direction_id) {
        $query->where('direction_id', $request->direction_id);
    }
    
    return $query->get();
});

Route::get('/subjects', function(Request $request) {
    $query = \App\Models\Subject::query();
    
    if ($request->direction_id) {
        $query->where('direction_id', $request->direction_id);
    }
    
    return $query->get();
});
Route::middleware('auth:api')->group(function() {
    Route::get('/groups', function(Request $request) {
        return \App\Models\Group::when($request->direction_id, function($query, $directionId) {
            $query->where('direction_id', $directionId);
        })->get();
    });
    
    Route::get('/courses', function(Request $request) {
        return \App\Models\Course::when($request->direction_id, function($query, $directionId) {
            $query->whereHas('subject', function($q) use ($directionId) {
                $q->where('direction_id', $directionId)->where('is_active', true);
            });
        })->get();
    });
    
    Route::get('/courses', function(Request $request) {
        return \App\Models\Course::when($request->direction_id, function($query) use ($request) {
            $query->where('direction_id', $request->direction_id)
                  ->where('is_active', true);
        })->get();
    });
    
    Route::get('/occupied-classrooms', function(Request $request) {
        return \App\Models\Schedule::where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start, $request->end])
                      ->orWhereBetween('end_time', [$request->start, $request->end])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<', $request->start)
                            ->where('end_time', '>', $request->end);
                      });
            })
            ->pluck('classroom')
            ->unique()
            ->values();
    });

    Route::get('/directions/{direction}/paid-amount', function(Direction $direction) {
        return response()->json([
            'paid' => $direction->getPaidAmount(auth()->id())
        ]);
    });
});
