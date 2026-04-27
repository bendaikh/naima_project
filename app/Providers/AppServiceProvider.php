<?php

namespace App\Providers;

use App\Models\ActionHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share company parameters with all views
        view()->composer('*', function ($view) {
            $companyParams = \App\Models\ParametresEntreprise::get();
            $view->with('companyParams', $companyParams);
        });

        $registerHistoryListener = function (string $eloquentEvent, string $actionType, string $titlePrefix, string $descriptionSuffix): void {
            Event::listen($eloquentEvent, function (string $eventName, array $data) use ($actionType, $titlePrefix, $descriptionSuffix): void {
                /** @var Model|null $model */
                $model = $data[0] ?? null;

                if (!$model instanceof Model) {
                    return;
                }

                $modelClass = $model::class;
                if (!str_starts_with($modelClass, 'App\\Models\\') || $modelClass === ActionHistory::class) {
                    return;
                }

                $modelName = class_basename($modelClass);
                $identifier = $model->getKey() !== null ? '#' . $model->getKey() : '';

                ActionHistory::query()->create([
                    'title' => "{$titlePrefix} {$modelName}",
                    'description' => "{$modelName} {$identifier} {$descriptionSuffix}.",
                    'user_id' => Auth::id(),
                    'action_type' => $actionType,
                    'actionable_type' => $modelClass,
                    'actionable_id' => $model->getKey(),
                    'action_date' => now(),
                ]);
            });
        };

        $registerHistoryListener('eloquent.created: *', 'create', 'Création de', 'a été créé');
        $registerHistoryListener('eloquent.updated: *', 'update', 'Mise à jour de', 'a été mis à jour');
        $registerHistoryListener('eloquent.deleted: *', 'delete', 'Suppression de', 'a été supprimé');
    }
}
