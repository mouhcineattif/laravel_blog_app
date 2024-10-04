<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array{
        return [
            'all' => Tab::make(),
            'published' => Tab::make('Private')->modifyQueryUsing(function (Builder $query){
                return $query->where('private',true);
            }),
            'private' => Tab::make('Public')->modifyQueryUsing(function (Builder $query){
                return $query->where('private',false);
            }),
        ];
    }
}
