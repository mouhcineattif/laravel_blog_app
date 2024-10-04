<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\RelationManagers\AuthorsRelationManager;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Models\Category;
use App\Models\Post;
use Faker\Core\Color;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color as ColorsColor;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Create Post')->tabs([
                    Tab::make('Post Basic Informations')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        TextInput::make('title')->required()
                        ->live(onBlur:true)
                        ->afterStateUpdated(function($state,$set,$operation){
                            if($operation == 'create'){
                                $set('slug',Str::slug($state));
                            }
                        }),
                        TextInput::make('slug')->required(),
                        ColorPicker::make('color')->required(),
                    ]),
                    Tab::make('Post Content')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->
                    schema([
                        MarkdownEditor::make('content')->required(),
                    ]),
                    Tab::make('Post Thumbnail')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('thumbnail')->required()->disk('public')->directory('thumbnails')->visibility('public'),
                    ]),
                    Tab::make('Meta')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->schema([
                        Select::make('category_id')
                ->label('Category')
                ->options(Category::all()->pluck('name','id')),
                Select::make('authors')
                ->multiple()
                ->relationship('authors','name'),
                TagsInput::make('tags')->required(),
                Toggle::make('private')->label('do you wanna to make the post private ?'),
                    ])
                ])->activeTab(1)->persistTabInQueryString(),       
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->searchable()->sortable()->toggleable(),
                TextColumn::make('title')->searchable()->sortable()->toggleable(),
                TextColumn::make('category.name')->searchable()->sortable()->toggleable()
                ->badge()->color(function($state){
                    return match($state){
                        'Symfony' => 'info',
                        'JavaScript Language' => 'warning',
                        'C++' => 'danger'
                    };
                }),
                ColorColumn::make('color')->toggleable(),
                ImageColumn::make('thumbnail')->toggleable(),
                // TextColumn::make('content'),
                TextColumn::make('tags')->searchable()->sortable()->toggleable(),
                ToggleColumn::make('private')->toggleable(),
                TextColumn::make('created_at')->date()->label('Posted On')->searchable()->sortable()->toggleable(),
                TextColumn::make('updated_at')->date()->label('Updated On')->searchable()->sortable()->toggleable(),
            ])
            ->filters([
                Filter::make('private')->query(function($query){
                    return $query->where('private',true);
                }),
                Filter::make('id')->query(function($query){
                    return $query->where('private',true);
                })
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

    public static function getRelations(): array
    {
        return [
            AuthorsRelationManager::class,
            CommentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
