<?php
declare(strict_types=1);

namespace App\Services;

use Widmogrod\Monad\Either\{Either, Left, Right};
use Illuminate\Support\Facades\Log;

final class HighPeopleService
{
    public function dropCounter(): Either
    {
        try {
            $high = \App\Models\HighPeople::find(1);
            $high->count = rand(4, 20);
            $high->save();
        } catch (\Throwable $e) {
            return Left::of($e);
        }

        return Right::of(true);
    }
}
