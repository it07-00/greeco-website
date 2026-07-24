<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Support\OptimizedImageUpload;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\ToolbarButtonGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('PostTabs')
                    ->tabs([
                        Tab::make('Thông tin chính')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Tiêu đề bài viết')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                                        $oldSlug = Str::slug($old ?? '');
                                        $currentSlug = $get('slug');

                                        if (blank($currentSlug) || $currentSlug === $oldSlug) {
                                            $set('slug', Str::slug($state ?? ''));
                                        }

                                        if (blank($get('meta_title'))) {
                                            $set('meta_title', self::suggestMetaTitle($state));
                                        }
                                    }),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                Select::make('post_category_id')
                                    ->label('Danh mục bài viết')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload(),
                                Textarea::make('excerpt')
                                    ->label('Mô tả ngắn/Trích dẫn')
                                    ->rows(3)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state): void {
                                        if (blank($get('meta_description'))) {
                                            $set('meta_description', self::suggestMetaDescription($state, $get('content')));
                                        }
                                    }),
                                TagsInput::make('tags')
                                    ->label('Thẻ (Tags)')
                                    ->placeholder('Nhập tag và ấn Enter')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set, $state): void {
                                        if (blank($get('meta_keywords'))) {
                                            $set('meta_keywords', self::suggestMetaKeywords($state, $get('title')));
                                        }
                                    })
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('Nội dung')
                            ->schema([
                                Select::make('content_type')
                                    ->label('Hình thức thể hiện nội dung')
                                    ->options([
                                        'text' => 'Bài viết văn bản thông thường',
                                        'pdf' => 'Hiển thị tệp PDF trực tuyến (Flipbook)',
                                    ])
                                    ->default('text')
                                    ->live()
                                    ->columnSpanFull(),

                                FileUpload::make('pdf_path')
                                    ->label('Tệp PDF nội dung')
                                    ->disk('public')
                                    ->directory('posts/pdfs')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->maxSize(20480) // 20MB
                                    ->visible(fn (Get $get) => $get('content_type') === 'pdf')
                                    ->required(fn (Get $get) => $get('content_type') === 'pdf')
                                    ->columnSpanFull(),

                                OptimizedImageUpload::configureRichEditor(
                                    RichEditor::make('content')->label('Nội dung bài viết'),
                                )
                                    ->toolbarButtons(self::postContentToolbarButtons())
                                    ->floatingToolbars(self::postContentFloatingToolbars())
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('posts/attachments')
                                    ->resizableImages()
                                    ->helperText('Dùng H2 cho các ý chính, H3-H4 cho ý con. Không dùng H1 trong nội dung vì tiêu đề bài viết đã là H1.')
                                    ->visible(fn (Get $get) => $get('content_type') === 'text')
                                    ->columnSpanFull(),
                            ]),
                        Tab::make('Hình ảnh')
                            ->schema([
                                OptimizedImageUpload::configure(
                                    FileUpload::make('thumbnail')->label('Ảnh đại diện'),
                                    2560,
                                    1440,
                                )
                                    ->disk('public')
                                    ->directory('posts'),
                                OptimizedImageUpload::configure(
                                    FileUpload::make('og_image')->label('Ảnh chia sẻ Facebook/Zalo'),
                                    1600,
                                    900,
                                )
                                    ->disk('public')
                                    ->directory('seo/og-images'),
                            ]),
                        Tab::make('SEO')
                            ->schema([
                                Actions::make([
                                    Action::make('generatePostSeo')
                                        ->label('Tạo gợi ý SEO')
                                        ->icon(Heroicon::OutlinedSparkles)
                                        ->color('success')
                                        ->button()
                                        ->outlined()
                                        ->action(function (Get $schemaGet, Set $schemaSet): void {
                                            $title = $schemaGet('title');
                                            $slug = $schemaGet('slug') ?: Str::slug($title ?? '');

                                            $schemaSet('meta_title', self::suggestMetaTitle($title));
                                            $schemaSet('meta_description', self::suggestMetaDescription($schemaGet('excerpt'), $schemaGet('content')));
                                            $schemaSet('meta_keywords', self::suggestMetaKeywords($schemaGet('tags'), $title));
                                            $schemaSet('canonical_url', self::suggestCanonicalUrl($slug));
                                        }),
                                    Action::make('fillCanonicalUrl')
                                        ->label('Điền canonical')
                                        ->icon(Heroicon::OutlinedLink)
                                        ->color('gray')
                                        ->button()
                                        ->outlined()
                                        ->action(function (Get $schemaGet, Set $schemaSet): void {
                                            $slug = $schemaGet('slug') ?: Str::slug($schemaGet('title') ?? '');

                                            $schemaSet('canonical_url', self::suggestCanonicalUrl($slug));
                                        }),
                                ])
                                    ->columnSpanFull(),
                                TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->placeholder(fn (Get $get): ?string => self::suggestMetaTitle($get('title')))
                                    ->helperText(fn (?string $state): string => self::seoLengthHint($state, 45, 60))
                                    ->live(onBlur: true)
                                    ->maxLength(255),
                                Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->placeholder(fn (Get $get): ?string => self::suggestMetaDescription($get('excerpt'), $get('content')))
                                    ->helperText(fn (?string $state): string => self::seoLengthHint($state, 120, 155))
                                    ->live(onBlur: true)
                                    ->rows(3),
                                TextInput::make('meta_keywords')
                                    ->label('Meta Keywords')
                                    ->placeholder(fn (Get $get): ?string => self::suggestMetaKeywords($get('tags'), $get('title')))
                                    ->helperText('Meta keywords không còn là yếu tố xếp hạng chính; nên đồng bộ với Tags để quản trị nội dung.')
                                    ->maxLength(255),
                                TextInput::make('canonical_url')
                                    ->label('Canonical URL')
                                    ->placeholder(fn (Get $get): ?string => self::suggestCanonicalUrl($get('slug') ?: Str::slug($get('title') ?? '')))
                                    ->helperText('Chỉ nhập khi muốn canonical sang URL khác. Để trống sẽ dùng URL bài viết hiện tại.')
                                    ->rules(['nullable', 'url'])
                                    ->maxLength(255),
                            ]),
                        Tab::make('Trạng thái')
                            ->schema([
                                Toggle::make('is_featured')
                                    ->label('Nổi bật')
                                    ->default(false),
                                Toggle::make('is_indexable')
                                    ->label('Cho Google index')
                                    ->default(true),
                                Toggle::make('is_published')
                                    ->label('Đã xuất bản')
                                    ->default(false),
                                DateTimePicker::make('published_at')
                                    ->label('Ngày xuất bản')
                                    ->default(now()),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function postContentToolbarButtons(): array
    {
        return [
            ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link', 'clearFormatting'],
            [
                ToolbarButtonGroup::make('Đoạn và heading', ['paragraph', 'lead', 'h2', 'h3', 'h4', 'h5', 'h6', 'small'])
                    ->textualButtons(),
            ],
            ['alignStart', 'alignCenter', 'alignEnd', 'alignJustify'],
            ['blockquote', 'code', 'codeBlock', 'bulletList', 'orderedList'],
            ['table', 'horizontalRule', 'attachFiles'],
            ['undo', 'redo'],
        ];
    }

    public static function postContentFloatingToolbars(): array
    {
        return [
            'paragraph' => ['bold', 'italic', 'underline', 'strike', 'link', 'code', 'highlight'],
            'heading' => ['paragraph', 'h2', 'h3', 'h4', 'h5', 'h6'],
            'table' => [
                'tableAddColumnBefore',
                'tableAddColumnAfter',
                'tableDeleteColumn',
                'tableAddRowBefore',
                'tableAddRowAfter',
                'tableDeleteRow',
                'tableMergeCells',
                'tableSplitCell',
                'tableToggleHeaderRow',
                'tableToggleHeaderCell',
                'tableDelete',
            ],
        ];
    }

    public static function suggestMetaTitle(?string $title): ?string
    {
        $title = self::cleanSeoText($title);

        if (blank($title)) {
            return null;
        }

        $suffix = ' | GREECO';
        $maxBaseLength = 60 - mb_strlen($suffix);

        return self::limitSeoText($title, $maxBaseLength).$suffix;
    }

    public static function suggestMetaDescription(?string $excerpt, ?string $content = null): ?string
    {
        $source = self::cleanSeoText($excerpt) ?: self::cleanSeoText(strip_tags((string) $content));

        if (blank($source)) {
            return null;
        }

        return self::limitSeoText($source, 155);
    }

    public static function suggestMetaKeywords($tags, ?string $title = null): ?string
    {
        $keywords = collect(is_iterable($tags) ? $tags : [])
            ->filter(fn ($tag) => filled($tag))
            ->map(fn ($tag) => self::cleanSeoText((string) $tag));

        if ($keywords->isEmpty() && filled($title)) {
            $keywords = collect(preg_split('/\s+/u', self::cleanSeoText($title)))
                ->filter(fn ($word) => mb_strlen($word) >= 4);
        }

        $keywords = $keywords
            ->filter()
            ->unique(fn ($keyword) => Str::lower($keyword))
            ->take(8);

        return $keywords->isEmpty() ? null : $keywords->implode(', ');
    }

    public static function suggestCanonicalUrl(?string $slug): ?string
    {
        if (blank($slug)) {
            return null;
        }

        return route('slug.show', ['slug' => $slug]);
    }

    public static function seoLengthHint(?string $value, int $min, int $max): string
    {
        $length = mb_strlen(self::cleanSeoText($value));

        $status = match (true) {
            $length === 0 => 'chưa nhập',
            $length < $min => 'hơi ngắn',
            $length > $max => 'hơi dài',
            default => 'tốt',
        };

        return "{$length} ký tự. Khuyến nghị {$min}-{$max} ký tự; trạng thái: {$status}.";
    }

    private static function cleanSeoText(?string $value): string
    {
        return trim((string) Str::of(html_entity_decode((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8'))
            ->replaceMatches('/\s+/u', ' '));
    }

    private static function limitSeoText(string $value, int $limit): string
    {
        if (mb_strlen($value) <= $limit) {
            return $value;
        }

        return rtrim(Str::limit($value, $limit, ''));
    }
}
