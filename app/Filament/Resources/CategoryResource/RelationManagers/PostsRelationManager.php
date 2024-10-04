<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Post')->schema([
                TextInput::make('title')->required(),
                TextInput::make('slug')->required(),
                ColorPicker::make('color')->required(),
            ])->collapsible()->columnSpan(1),
            Section::make('Content')->schema([
                MarkdownEditor::make('content')->required(),
            ])->collapsible()->columnSpan(1),  
            Section::make('Thumbnail')->schema([
                FileUpload::make('thumbnail')->required()->disk('public')->directory('thumbnails')->visibility('public'),
            ])->collapsible()->columnSpan(1),  
           Section::make('Meta')->schema([
            Select::make('category_id')
            ->label('Category')
            ->options(Category::all()->pluck('name','id')),
            TagsInput::make('tags')->required(),
            Toggle::make('private')->label('do you wanna to make the post private ?'),
           ])->collapsible(),
           
        ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
